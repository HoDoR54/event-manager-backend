<?php

declare(strict_types=1);

/**
 * Loads key-value pairs from a .env file into $_ENV, $_SERVER, and putenv.
 */
function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $key = trim($parts[0]);
        $val = trim($parts[1]);

        if (preg_match('/^([\'"])(.*)\1$/', $val, $matches)) {
            $val = $matches[2];
        }

        if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
            putenv("{$key}={$val}");
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }
    }
}

/**
 * Returns a shared PDO instance connected to PostgreSQL.
 */
function getDb(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    loadEnv(dirname(__DIR__) . '/.env');

    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $port = $_ENV['DB_PORT'] ?? '5432';
    $dbname = $_ENV['DB_DATABASE'] ?? '';
    $user = $_ENV['DB_USERNAME'] ?? '';
    $pass = $_ENV['DB_PASSWORD'] ?? '';

    if ($dbname === '' || $user === '') {
        throw new RuntimeException('Database configuration is missing.');
    }

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

/**
 * Checks if the PostgreSQL database is reachable.
 */
function checkDbHealth(): bool
{
    try {
        $pdo = getDb();
        return $pdo->query('SELECT 1') !== false;
    } catch (Throwable) {
        return false;
    }
}

/**
 * Synchronizes events and replaces their sessions inside a single transaction.
 *
 * @param list<array<string, mixed>> $events
 * @return array{eventsSynced: int, sessionsSynced: int}
 */
function syncEvents(array $events): array
{
    $pdo = getDb();
    $eventsSynced = 0;
    $sessionsSynced = 0;

    $pdo->beginTransaction();

    try {
        $selectStmt = $pdo->prepare(
            'SELECT id FROM events WHERE LOWER(name) = LOWER(:name) AND date = :date'
        );

        $updateStmt = $pdo->prepare(
            'UPDATE events SET 
                client_id = :client_id,
                location = :location,
                organiser = :organiser,
                type = :type,
                status = :status,
                description = :description,
                contact_email = :contact_email,
                contact_phone = :contact_phone,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id'
        );

        $insertStmt = $pdo->prepare(
            'INSERT INTO events (
                client_id, name, date, location, organiser, type, status, description, contact_email, contact_phone
            ) VALUES (
                :client_id, :name, :date, :location, :organiser, :type, :status, :description, :contact_email, :contact_phone
            ) RETURNING id'
        );

        $deleteSessionsStmt = $pdo->prepare(
            'DELETE FROM sessions WHERE event_id = :event_id'
        );

        $insertSessionStmt = $pdo->prepare(
            'INSERT INTO sessions (
                event_id, session_id, name, time, type, speaker, room, description
            ) VALUES (
                :event_id, :session_id, :name, :time, :type, :speaker, :room, :description
            )'
        );

        foreach ($events as $event) {
            $name = (string) ($event['name'] ?? '');
            $date = (string) ($event['date'] ?? '');
            $clientId = isset($event['id']) ? (int) $event['id'] : null;
            $location = (string) ($event['location'] ?? '');
            $organiser = (string) ($event['organiser'] ?? '');
            $type = (string) ($event['type'] ?? '');
            $status = (string) ($event['status'] ?? 'UPCOMING');
            $description = isset($event['description']) && $event['description'] !== '' ? (string) $event['description'] : null;
            $contactEmail = isset($event['contactEmail']) && $event['contactEmail'] !== '' ? (string) $event['contactEmail'] : null;
            $contactPhone = isset($event['contactPhone']) && $event['contactPhone'] !== '' ? (string) $event['contactPhone'] : null;

            $selectStmt->execute([
                ':name' => $name,
                ':date' => $date,
            ]);
            $existing = $selectStmt->fetch();

            if ($existing !== false && isset($existing['id'])) {
                $eventId = (int) $existing['id'];
                $updateStmt->execute([
                    ':id' => $eventId,
                    ':client_id' => $clientId,
                    ':location' => $location,
                    ':organiser' => $organiser,
                    ':type' => $type,
                    ':status' => $status,
                    ':description' => $description,
                    ':contact_email' => $contactEmail,
                    ':contact_phone' => $contactPhone,
                ]);
            } else {
                $insertStmt->execute([
                    ':client_id' => $clientId,
                    ':name' => $name,
                    ':date' => $date,
                    ':location' => $location,
                    ':organiser' => $organiser,
                    ':type' => $type,
                    ':status' => $status,
                    ':description' => $description,
                    ':contact_email' => $contactEmail,
                    ':contact_phone' => $contactPhone,
                ]);
                $inserted = $insertStmt->fetch();
                $eventId = (int) $inserted['id'];
            }

            $eventsSynced++;

            // Replace sessions for this event
            $deleteSessionsStmt->execute([':event_id' => $eventId]);

            if (isset($event['sessions']) && is_array($event['sessions'])) {
                foreach ($event['sessions'] as $session) {
                    if (!is_array($session)) {
                        continue;
                    }

                    $insertSessionStmt->execute([
                        ':event_id' => $eventId,
                        ':session_id' => (string) ($session['sessionId'] ?? ''),
                        ':name' => (string) ($session['name'] ?? ''),
                        ':time' => (string) ($session['time'] ?? ''),
                        ':type' => (string) ($session['type'] ?? ''),
                        ':speaker' => isset($session['speaker']) && $session['speaker'] !== '' ? (string) $session['speaker'] : null,
                        ':room' => isset($session['room']) && $session['room'] !== '' ? (string) $session['room'] : null,
                        ':description' => isset($session['description']) && $session['description'] !== '' ? (string) $session['description'] : null,
                    ]);
                    $sessionsSynced++;
                }
            }
        }

        $pdo->commit();

        return [
            'eventsSynced' => $eventsSynced,
            'sessionsSynced' => $sessionsSynced,
        ];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

/**
 * Formats event and nested session rows into the API response format.
 *
 * @param array<string, mixed> $event
 * @param list<array<string, mixed>> $sessions
 * @return array<string, mixed>
 */
function formatEvent(array $event, array $sessions): array
{
    return [
        'id' => (int) $event['id'],
        'clientId' => isset($event['client_id']) ? (int) $event['client_id'] : null,
        'name' => (string) $event['name'],
        'date' => (string) $event['date'],
        'location' => (string) $event['location'],
        'organiser' => (string) $event['organiser'],
        'type' => (string) $event['type'],
        'status' => (string) $event['status'],
        'description' => $event['description'] !== null ? (string) $event['description'] : null,
        'contactEmail' => $event['contact_email'] !== null ? (string) $event['contact_email'] : null,
        'contactPhone' => $event['contact_phone'] !== null ? (string) $event['contact_phone'] : null,
        'createdAt' => (string) $event['created_at'],
        'updatedAt' => (string) $event['updated_at'],
        'sessions' => array_map(static fn (array $s): array => [
            'id' => (int) $s['id'],
            'eventId' => (int) $s['event_id'],
            'sessionId' => (string) $s['session_id'],
            'name' => (string) $s['name'],
            'time' => (string) $s['time'],
            'type' => (string) $s['type'],
            'speaker' => $s['speaker'] !== null ? (string) $s['speaker'] : null,
            'room' => $s['room'] !== null ? (string) $s['room'] : null,
            'description' => $s['description'] !== null ? (string) $s['description'] : null,
        ], $sessions),
    ];
}

/**
 * Retrieves all events along with their nested sessions.
 *
 * @return list<array<string, mixed>>
 */
function getAllEvents(): array
{
    $pdo = getDb();
    $stmt = $pdo->query('SELECT * FROM events ORDER BY date ASC, id ASC');
    $events = $stmt->fetchAll();

    if (empty($events)) {
        return [];
    }

    $sessionStmt = $pdo->prepare('SELECT * FROM sessions WHERE event_id = :event_id ORDER BY id ASC');

    $result = [];
    foreach ($events as $event) {
        $sessionStmt->execute([':event_id' => $event['id']]);
        $sessions = $sessionStmt->fetchAll();
        $result[] = formatEvent($event, $sessions);
    }

    return $result;
}

/**
 * Retrieves a single event with its sessions by ID.
 *
 * @return array<string, mixed>|null
 */
function getEventById(int $id): ?array
{
    $pdo = getDb();
    $stmt = $pdo->prepare('SELECT * FROM events WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $event = $stmt->fetch();

    if (!$event) {
        return null;
    }

    $sessionStmt = $pdo->prepare('SELECT * FROM sessions WHERE event_id = :event_id ORDER BY id ASC');
    $sessionStmt->execute([':event_id' => $id]);
    $sessions = $sessionStmt->fetchAll();

    return formatEvent($event, $sessions);
}

/**
 * Deletes an event by its ID. Sessions cascade delete in database.
 */
function deleteEventById(int $id): bool
{
    $pdo = getDb();
    $stmt = $pdo->prepare('DELETE FROM events WHERE id = :id');
    $stmt->execute([':id' => $id]);
    return $stmt->rowCount() > 0;
}
