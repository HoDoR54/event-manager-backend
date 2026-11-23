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
 * Path to the local JSON file storage (used when PostgreSQL is offline or in development).
 */
function getJsonStorePath(): string
{
    $dir = dirname(__DIR__) . '/data';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    return $dir . '/events_store.json';
}

/**
 * Returns default initial seed events for testing and instant out-of-the-box operation.
 *
 * @return list<array<string, mixed>>
 */
function getDefaultSeedEvents(): array
{
    return [
        [
            'id' => 1,
            'clientId' => 1,
            'name' => 'Google I/O Extended 2026',
            'date' => '2026-11-20',
            'location' => 'Tech Park Auditorium, Hall A',
            'organiser' => 'Google Developer Group',
            'type' => 'CONFERENCE',
            'status' => 'UPCOMING',
            'description' => 'Annual community-led developer conference covering modern Android, Jetpack Compose, Gemini AI, and Cloud Architecture.',
            'contactEmail' => 'gdg-events@techhub.org',
            'contactPhone' => '+1 555-0199',
            'createdAt' => '2026-11-01 09:00:00',
            'updatedAt' => '2026-11-01 09:00:00',
            'sessions' => [
                [
                    'id' => 1,
                    'eventId' => 1,
                    'sessionId' => 'SES-01',
                    'name' => 'Keynote: Next-Gen AI & Mobile',
                    'time' => '09:00 - 10:15',
                    'type' => 'KEYNOTE',
                    'speaker' => 'Dr. Sarah Lin (Google)',
                    'room' => 'Main Hall',
                    'description' => 'Opening keynote on generative AI models running directly on device.',
                ],
                [
                    'id' => 2,
                    'eventId' => 1,
                    'sessionId' => 'SES-02',
                    'name' => 'Hands-on Kotlin Multiplatform',
                    'time' => '10:30 - 12:00',
                    'type' => 'WORKSHOP',
                    'speaker' => 'Marcus Vance',
                    'room' => 'Lab 3',
                    'description' => 'Building shared business logic across Android and iOS.',
                ],
                [
                    'id' => 3,
                    'eventId' => 1,
                    'sessionId' => 'SES-03',
                    'name' => 'Networking & Partner Showcase',
                    'time' => '12:00 - 13:30',
                    'type' => 'NETWORKING',
                    'speaker' => null,
                    'room' => 'Atrium',
                    'description' => 'Lunch, project demonstrations, and partner career fair.',
                ],
            ],
            'images' => [],
        ],
        [
            'id' => 2,
            'clientId' => 2,
            'name' => 'KotlinConf Global Summit',
            'date' => '2026-11-25',
            'location' => 'Amsterdam Convention Hall',
            'organiser' => 'JetBrains Community',
            'type' => 'CONFERENCE',
            'status' => 'UPCOMING',
            'description' => 'The premier conference for developers building with Kotlin on Android, server-side, and cross-platform stacks.',
            'contactEmail' => 'contact@kotlinconf.com',
            'contactPhone' => '+31 20 555 1234',
            'createdAt' => '2026-11-02 10:00:00',
            'updatedAt' => '2026-11-02 10:00:00',
            'sessions' => [
                [
                    'id' => 4,
                    'eventId' => 2,
                    'sessionId' => 'SES-10',
                    'name' => 'Deep Dive: Compose Multiplatform 2.0',
                    'time' => '10:00 - 11:00',
                    'type' => 'PRESENTATION',
                    'speaker' => 'Elena Rostova',
                    'room' => 'Track 1',
                    'description' => 'Architecting high-performance UI across desktop, web, and mobile.',
                ],
                [
                    'id' => 5,
                    'eventId' => 2,
                    'sessionId' => 'SES-11',
                    'name' => 'Coroutines Internals and Best Practices',
                    'time' => '11:15 - 12:30',
                    'type' => 'WORKSHOP',
                    'speaker' => 'Roman Elizarov',
                    'room' => 'Workshop Studio',
                    'description' => 'Structuring asynchronous pipelines and error propagation.',
                ],
            ],
            'images' => [],
        ],
        [
            'id' => 3,
            'clientId' => 3,
            'name' => 'Advanced React Native Masterclass',
            'date' => '2026-12-02',
            'location' => 'Design & Code Lab, Room 402',
            'organiser' => 'Mobile Creators Guild',
            'type' => 'WORKSHOP',
            'status' => 'UPCOMING',
            'description' => 'Intensive hands-on workshop covering the New Architecture, Expo Router SDK 58, and native modules integration.',
            'contactEmail' => 'workshops@mobileguild.dev',
            'contactPhone' => '+1 555-0422',
            'createdAt' => '2026-11-03 14:00:00',
            'updatedAt' => '2026-11-03 14:00:00',
            'sessions' => [
                [
                    'id' => 6,
                    'eventId' => 3,
                    'sessionId' => 'SES-20',
                    'name' => 'JSI and TurboModules from Scratch',
                    'time' => '13:00 - 15:00',
                    'type' => 'WORKSHOP',
                    'speaker' => 'David Kim',
                    'room' => 'Room 402',
                    'description' => 'Direct C++ communication without serializing JSON over the bridge.',
                ],
            ],
            'images' => [],
        ],
        [
            'id' => 4,
            'clientId' => 4,
            'name' => 'Cybersecurity In Cloud Operations',
            'date' => '2026-10-15',
            'location' => 'Metropolitan Center',
            'organiser' => 'InfoSec Alliance',
            'type' => 'SEMINAR',
            'status' => 'COMPLETED',
            'description' => 'Executive seminar examining zero-trust security postures and compliance for enterprise workloads.',
            'contactEmail' => 'info@infosecalliance.org',
            'contactPhone' => null,
            'createdAt' => '2026-10-01 08:30:00',
            'updatedAt' => '2026-10-01 08:30:00',
            'sessions' => [],
            'images' => [],
        ],
    ];
}

/**
 * Loads events from local JSON file storage.
 *
 * @return list<array<string, mixed>>
 */
function readJsonStore(): array
{
    $path = getJsonStorePath();
    if (!file_exists($path)) {
        $seeds = getDefaultSeedEvents();
        file_put_contents($path, json_encode($seeds, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return $seeds;
    }

    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

/**
 * Writes events to local JSON file storage atomically.
 *
 * @param list<array<string, mixed>> $events
 */
function writeJsonStore(array $events): void
{
    $path = getJsonStorePath();
    file_put_contents($path, json_encode($events, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
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
        PDO::ATTR_TIMEOUT => 2,
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
 * Synchronizes events, replaces their sessions and images inside a transaction.
 *
 * @param list<array<string, mixed>> $events
 * @return array{eventsSynced: int, sessionsSynced: int, imagesSynced: int}
 */
function syncEvents(array $events): array
{
    if (checkDbHealth()) {
        return syncEventsPostgres($events);
    }
    return syncEventsJson($events);
}

/**
 * Synchronizes events into PostgreSQL.
 *
 * @param list<array<string, mixed>> $events
 * @return array{eventsSynced: int, sessionsSynced: int, imagesSynced: int}
 */
function syncEventsPostgres(array $events): array
{
    $pdo = getDb();
    $eventsSynced = 0;
    $sessionsSynced = 0;
    $imagesSynced = 0;

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

        $deleteImagesStmt = $pdo->prepare(
            'DELETE FROM event_images WHERE event_id = :event_id'
        );

        $insertImageStmt = $pdo->prepare(
            'INSERT INTO event_images (
                event_id, image_url, image_data, caption
            ) VALUES (
                :event_id, :image_url, :image_data, :caption
            )'
        );

        $syncedIds = [];
        foreach ($events as $event) {
            $selectStmt->execute([
                ':name' => $event['name'],
                ':date' => $event['date'],
            ]);
            $existing = $selectStmt->fetch();

            $clientId = isset($event['id']) ? (int) $event['id'] : null;
            $params = [
                ':client_id' => $clientId,
                ':location' => $event['location'],
                ':organiser' => $event['organiser'],
                ':type' => strtoupper((string) $event['type']),
                ':status' => strtoupper((string) ($event['status'] ?? 'UPCOMING')),
                ':description' => $event['description'] ?? null,
                ':contact_email' => $event['contactEmail'] ?? null,
                ':contact_phone' => $event['contactPhone'] ?? null,
            ];

            if ($existing) {
                $eventId = (int) $existing['id'];
                $updateStmt->execute(array_merge($params, [':id' => $eventId]));
            } else {
                $insertStmt->execute(array_merge($params, [
                    ':name' => $event['name'],
                    ':date' => $event['date'],
                ]));
                $eventId = (int) $insertStmt->fetchColumn();
            }

            $syncedIds[] = $eventId;
            $eventsSynced++;

            $deleteSessionsStmt->execute([':event_id' => $eventId]);

            if (isset($event['sessions']) && is_array($event['sessions'])) {
                foreach ($event['sessions'] as $session) {
                    if (!is_array($session)) {
                        continue;
                    }
                    $insertSessionStmt->execute([
                        ':event_id' => $eventId,
                        ':session_id' => $session['sessionId'],
                        ':name' => $session['name'],
                        ':time' => $session['time'],
                        ':type' => strtoupper((string) $session['type']),
                        ':speaker' => $session['speaker'] ?? null,
                        ':room' => $session['room'] ?? null,
                        ':description' => $session['description'] ?? null,
                    ]);
                    $sessionsSynced++;
                }
            }

            $deleteImagesStmt->execute([':event_id' => $eventId]);

            if (isset($event['images']) && is_array($event['images'])) {
                foreach ($event['images'] as $image) {
                    if (!is_array($image)) {
                        continue;
                    }

                    $imageUrl = isset($image['imageUrl']) && $image['imageUrl'] !== '' ? (string) $image['imageUrl'] : null;
                    $imageData = isset($image['imageData']) && $image['imageData'] !== '' ? (string) $image['imageData'] : null;
                    $caption = isset($image['caption']) && $image['caption'] !== '' ? (string) $image['caption'] : null;

                    if ($imageUrl !== null || $imageData !== null) {
                        $insertImageStmt->execute([
                            ':event_id' => $eventId,
                            ':image_url' => $imageUrl,
                            ':image_data' => $imageData,
                            ':caption' => $caption,
                        ]);
                        $imagesSynced++;
                    }
                }
            }
        }

        if (!empty($syncedIds)) {
            $inClause = implode(',', array_map('intval', $syncedIds));
            $pdo->exec("DELETE FROM events WHERE id NOT IN ($inClause)");
        }

        $pdo->commit();

        return [
            'eventsSynced' => $eventsSynced,
            'sessionsSynced' => $sessionsSynced,
            'imagesSynced' => $imagesSynced,
        ];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

/**
 * Synchronizes events into the local JSON file store (fallback mode).
 *
 * @param list<array<string, mixed>> $events
 * @return array{eventsSynced: int, sessionsSynced: int, imagesSynced: int}
 */
function syncEventsJson(array $events): array
{
    $store = readJsonStore();
    $eventsSynced = 0;
    $sessionsSynced = 0;
    $imagesSynced = 0;

    $maxId = 0;
    foreach ($store as $item) {
        if (isset($item['id']) && $item['id'] > $maxId) {
            $maxId = (int) $item['id'];
        }
    }

    $now = gmdate('Y-m-d H:i:s');
    $syncedKeys = [];

    foreach ($events as $incoming) {
        $name = (string) $incoming['name'];
        $date = (string) $incoming['date'];
        $syncedKeys[] = strtolower(trim($name)) . '|' . trim($date);

        $existingIndex = null;
        foreach ($store as $idx => $ev) {
            if (strcasecmp($ev['name'], $name) === 0 && $ev['date'] === $date) {
                $existingIndex = $idx;
                break;
            }
        }

        $formattedSessions = [];
        if (isset($incoming['sessions']) && is_array($incoming['sessions'])) {
            $sId = 1;
            foreach ($incoming['sessions'] as $s) {
                if (!is_array($s)) continue;
                $formattedSessions[] = [
                    'id' => $sId++,
                    'eventId' => $existingIndex !== null ? (int)$store[$existingIndex]['id'] : ($maxId + 1),
                    'sessionId' => (string) ($s['sessionId'] ?? "SES-{$sId}"),
                    'name' => (string) ($s['name'] ?? ''),
                    'time' => (string) ($s['time'] ?? ''),
                    'type' => strtoupper((string) ($s['type'] ?? 'WORKSHOP')),
                    'speaker' => $s['speaker'] ?? null,
                    'room' => $s['room'] ?? null,
                    'description' => $s['description'] ?? null,
                ];
                $sessionsSynced++;
            }
        }

        $formattedImages = [];
        if (isset($incoming['images']) && is_array($incoming['images'])) {
            $imgId = 1;
            foreach ($incoming['images'] as $img) {
                if (!is_array($img)) continue;
                $formattedImages[] = [
                    'id' => $imgId++,
                    'eventId' => $existingIndex !== null ? (int)$store[$existingIndex]['id'] : ($maxId + 1),
                    'imageUrl' => $img['imageUrl'] ?? null,
                    'imageData' => $img['imageData'] ?? null,
                    'caption' => $img['caption'] ?? null,
                    'createdAt' => $now,
                ];
                $imagesSynced++;
            }
        }

        if ($existingIndex !== null) {
            $eventId = (int) $store[$existingIndex]['id'];
            $store[$existingIndex] = array_merge($store[$existingIndex], [
                'clientId' => isset($incoming['id']) ? (int) $incoming['id'] : null,
                'location' => (string) $incoming['location'],
                'organiser' => (string) $incoming['organiser'],
                'type' => strtoupper((string) $incoming['type']),
                'status' => strtoupper((string) ($incoming['status'] ?? 'UPCOMING')),
                'description' => $incoming['description'] ?? null,
                'contactEmail' => $incoming['contactEmail'] ?? null,
                'contactPhone' => $incoming['contactPhone'] ?? null,
                'updatedAt' => $now,
                'sessions' => $formattedSessions,
                'images' => $formattedImages,
            ]);
        } else {
            $maxId++;
            $eventId = $maxId;
            $store[] = [
                'id' => $eventId,
                'clientId' => isset($incoming['id']) ? (int) $incoming['id'] : null,
                'name' => $name,
                'date' => $date,
                'location' => (string) $incoming['location'],
                'organiser' => (string) $incoming['organiser'],
                'type' => strtoupper((string) $incoming['type']),
                'status' => strtoupper((string) ($incoming['status'] ?? 'UPCOMING')),
                'description' => $incoming['description'] ?? null,
                'contactEmail' => $incoming['contactEmail'] ?? null,
                'contactPhone' => $incoming['contactPhone'] ?? null,
                'createdAt' => $now,
                'updatedAt' => $now,
                'sessions' => $formattedSessions,
                'images' => $formattedImages,
            ];
        }

        $eventsSynced++;
    }

    if (!empty($syncedKeys)) {
        $store = array_values(array_filter($store, function ($item) use ($syncedKeys) {
            $key = strtolower(trim((string)($item['name'] ?? ''))) . '|' . trim((string)($item['date'] ?? ''));
            return in_array($key, $syncedKeys, true);
        }));
    }

    writeJsonStore($store);

    return [
        'eventsSynced' => $eventsSynced,
        'sessionsSynced' => $sessionsSynced,
        'imagesSynced' => $imagesSynced,
    ];
}

/**
 * Retrieves all events along with their nested sessions and images.
 *
 * @return list<array<string, mixed>>
 */
function getAllEvents(): array
{
    if (checkDbHealth()) {
        return getAllEventsPostgres();
    }
    return readJsonStore();
}

/**
 * Retrieves all events from PostgreSQL.
 *
 * @return list<array<string, mixed>>
 */
function getAllEventsPostgres(): array
{
    $pdo = getDb();
    $stmt = $pdo->query('SELECT * FROM events ORDER BY date ASC, id ASC');
    $events = $stmt->fetchAll();

    if (empty($events)) {
        return [];
    }

    $sessionStmt = $pdo->prepare('SELECT * FROM sessions WHERE event_id = :event_id ORDER BY id ASC');
    $imageStmt = $pdo->prepare('SELECT * FROM event_images WHERE event_id = :event_id ORDER BY id ASC');

    $result = [];
    foreach ($events as $event) {
        $sessionStmt->execute([':event_id' => $event['id']]);
        $sessions = $sessionStmt->fetchAll();

        $imageStmt->execute([':event_id' => $event['id']]);
        $images = $imageStmt->fetchAll();

        $result[] = formatEvent($event, $sessions, $images);
    }

    return $result;
}

/**
 * Formats event, nested session rows, and image rows into the API response format.
 *
 * @param array<string, mixed> $event
 * @param list<array<string, mixed>> $sessions
 * @param list<array<string, mixed>> $images
 * @return array<string, mixed>
 */
function formatEvent(array $event, array $sessions, array $images = []): array
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
        'images' => array_map(static fn (array $img): array => [
            'id' => (int) $img['id'],
            'eventId' => (int) $img['event_id'],
            'imageUrl' => $img['image_url'] !== null ? (string) $img['image_url'] : null,
            'imageData' => $img['image_data'] !== null ? (string) $img['image_data'] : null,
            'caption' => $img['caption'] !== null ? (string) $img['caption'] : null,
            'createdAt' => (string) $img['created_at'],
        ], $images),
    ];
}

/**
 * Retrieves a single event with its sessions and images by ID.
 *
 * @return array<string, mixed>|null
 */
function getEventById(int $id): ?array
{
    if (checkDbHealth()) {
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

        $imageStmt = $pdo->prepare('SELECT * FROM event_images WHERE event_id = :event_id ORDER BY id ASC');
        $imageStmt->execute([':event_id' => $id]);
        $images = $imageStmt->fetchAll();

        return formatEvent($event, $sessions, $images);
    }

    $store = readJsonStore();
    foreach ($store as $ev) {
        if ((int)$ev['id'] === $id) {
            return $ev;
        }
    }
    return null;
}

/**
 * Adds an image record to an existing event.
 */
function addEventImage(int $eventId, ?string $imageUrl, ?string $imageData, ?string $caption): int
{
    if (checkDbHealth()) {
        $pdo = getDb();
        $stmt = $pdo->prepare(
            'INSERT INTO event_images (event_id, image_url, image_data, caption)
             VALUES (:event_id, :image_url, :image_data, :caption) RETURNING id'
        );
        $stmt->execute([
            ':event_id' => $eventId,
            ':image_url' => $imageUrl,
            ':image_data' => $imageData,
            ':caption' => $caption,
        ]);
        return (int) $stmt->fetchColumn();
    }

    $store = readJsonStore();
    $foundIndex = null;
    foreach ($store as $idx => $ev) {
        if ((int)$ev['id'] === $eventId) {
            $foundIndex = $idx;
            break;
        }
    }
    if ($foundIndex === null) {
        throw new RuntimeException("Event #{$eventId} not found.");
    }

    $images = $store[$foundIndex]['images'] ?? [];
    $newId = count($images) + 1;
    $images[] = [
        'id' => $newId,
        'eventId' => $eventId,
        'imageUrl' => $imageUrl,
        'imageData' => $imageData,
        'caption' => $caption,
        'createdAt' => gmdate('Y-m-d H:i:s'),
    ];
    $store[$foundIndex]['images'] = $images;
    writeJsonStore($store);
    return $newId;
}

/**
 * Deletes a single image record by ID.
 */
function deleteEventImage(int $imageId): bool
{
    if (checkDbHealth()) {
        $pdo = getDb();
        $stmt = $pdo->prepare('DELETE FROM event_images WHERE id = :id');
        $stmt->execute([':id' => $imageId]);
        return $stmt->rowCount() > 0;
    }

    $store = readJsonStore();
    $modified = false;
    foreach ($store as &$ev) {
        if (isset($ev['images']) && is_array($ev['images'])) {
            $filtered = array_filter($ev['images'], fn ($img) => (int)$img['id'] !== $imageId);
            if (count($filtered) !== count($ev['images'])) {
                $ev['images'] = array_values($filtered);
                $modified = true;
            }
        }
    }
    if ($modified) {
        writeJsonStore($store);
    }
    return $modified;
}

/**
 * Deletes an event by ID (cascades sessions and images).
 */
function deleteEventById(int $id): bool
{
    if (checkDbHealth()) {
        $pdo = getDb();
        $stmt = $pdo->prepare('DELETE FROM events WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    $store = readJsonStore();
    $filtered = array_filter($store, fn ($ev) => (int)$ev['id'] !== $id);
    if (count($filtered) !== count($store)) {
        writeJsonStore(array_values($filtered));
        return true;
    }
    return false;
}

// ==========================================
// AUTHENTICATION & USER MANAGEMENT
// ==========================================

function getSecretKey(): string
{
    loadEnv(dirname(__DIR__) . '/.env');
    return $_ENV['APP_SECRET'] ?? 'event-manager-demo-secret-key-2026';
}

/**
 * Path to users JSON storage.
 */
function getUsersJsonStorePath(): string
{
    $dir = dirname(__DIR__) . '/data';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    return $dir . '/users_store.json';
}

/**
 * Path to user favorites JSON storage.
 */
function getFavoritesJsonStorePath(): string
{
    $dir = dirname(__DIR__) . '/data';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    return $dir . '/favorites_store.json';
}

/**
 * Returns default initial users.
 *
 * @return list<array<string, mixed>>
 */
function getDefaultSeedUsers(): array
{
    $now = gmdate('Y-m-d H:i:s');
    return [
        [
            'id' => 1,
            'name' => 'Admin Manager',
            'email' => 'admin@eventmanager.com',
            'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
            'role' => 'ADMIN',
            'createdAt' => $now,
            'updatedAt' => $now,
        ],
        [
            'id' => 2,
            'name' => 'Alex User',
            'email' => 'user@eventmanager.com',
            'password_hash' => password_hash('user123', PASSWORD_BCRYPT),
            'role' => 'USER',
            'createdAt' => $now,
            'updatedAt' => $now,
        ],
    ];
}

/**
 * Reads users from JSON file storage.
 *
 * @return list<array<string, mixed>>
 */
function readUsersJsonStore(): array
{
    $path = getUsersJsonStorePath();
    if (!file_exists($path)) {
        $seeds = getDefaultSeedUsers();
        file_put_contents($path, json_encode($seeds, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return $seeds;
    }

    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

/**
 * Writes users to JSON file storage.
 *
 * @param list<array<string, mixed>> $users
 */
function writeUsersJsonStore(array $users): void
{
    $path = getUsersJsonStorePath();
    file_put_contents($path, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
}

/**
 * Reads user favorites from JSON storage.
 *
 * @return list<array<string, mixed>>
 */
function readFavoritesJsonStore(): array
{
    $path = getFavoritesJsonStorePath();
    if (!file_exists($path)) {
        // Initial seed favorite for Alex User -> Google I/O (event 1)
        $seeds = [
            [
                'id' => 1,
                'userId' => 2,
                'eventId' => 1,
                'createdAt' => gmdate('Y-m-d H:i:s'),
            ],
        ];
        file_put_contents($path, json_encode($seeds, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return $seeds;
    }

    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

/**
 * Writes user favorites to JSON storage.
 *
 * @param list<array<string, mixed>> $favorites
 */
function writeFavoritesJsonStore(array $favorites): void
{
    $path = getFavoritesJsonStorePath();
    file_put_contents($path, json_encode($favorites, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
}

/**
 * Generates a signed authentication token for a user.
 */
function generateAuthToken(array $user): string
{
    $payload = [
        'uid' => (int) $user['id'],
        'email' => (string) $user['email'],
        'role' => (string) ($user['role'] ?? 'USER'),
        'exp' => time() + (86400 * 30), // 30 days
    ];

    $encoded = rtrim(strtr(base64_encode((string) json_encode($payload)), '+/', '-_'), '=');
    $sig = hash_hmac('sha256', $encoded, getSecretKey());
    return "{$encoded}.{$sig}";
}

/**
 * Verifies and decodes an authentication token.
 *
 * @return array<string, mixed>|null
 */
function verifyAuthToken(string $token): ?array
{
    $parts = explode('.', $token);
    if (count($parts) !== 2) {
        return null;
    }

    [$encoded, $sig] = $parts;
    $expectedSig = hash_hmac('sha256', $encoded, getSecretKey());
    if (!hash_equals($expectedSig, $sig)) {
        return null;
    }

    $remainder = strlen($encoded) % 4;
    if ($remainder) {
        $encoded .= str_repeat('=', 4 - $remainder);
    }
    $json = base64_decode(strtr($encoded, '-_', '+/'));
    if ($json === false) {
        return null;
    }

    $payload = json_decode($json, true);
    if (!is_array($payload) || !isset($payload['uid'], $payload['exp'])) {
        return null;
    }

    if ($payload['exp'] < time()) {
        return null; // Expired
    }

    return $payload;
}

/**
 * Finds a user by email address.
 *
 * @return array<string, mixed>|null
 */
function findUserByEmail(string $email): ?array
{
    $cleanEmail = strtolower(trim($email));

    if (checkDbHealth()) {
        $pdo = getDb();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE LOWER(email) = :email LIMIT 1');
        $stmt->execute([':email' => $cleanEmail]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    $users = readUsersJsonStore();
    foreach ($users as $user) {
        if (strtolower((string) $user['email']) === $cleanEmail) {
            return $user;
        }
    }
    return null;
}

/**
 * Finds a user by ID.
 *
 * @return array<string, mixed>|null
 */
function findUserById(int $id): ?array
{
    if (checkDbHealth()) {
        $pdo = getDb();
        $stmt = $pdo->prepare('SELECT id, name, email, role, created_at FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    $users = readUsersJsonStore();
    foreach ($users as $user) {
        if ((int) $user['id'] === $id) {
            return [
                'id' => (int) $user['id'],
                'name' => (string) $user['name'],
                'email' => (string) $user['email'],
                'role' => (string) ($user['role'] ?? 'USER'),
                'created_at' => $user['createdAt'] ?? gmdate('Y-m-d H:i:s'),
            ];
        }
    }
    return null;
}

/**
 * Registers a new user.
 *
 * @return array{id: int, name: string, email: string, role: string}
 */
function registerUser(string $name, string $email, string $password, string $role = 'USER'): array
{
    $cleanEmail = strtolower(trim($email));
    $cleanName = trim($name);
    $cleanRole = in_array(strtoupper(trim($role)), ['ADMIN', 'USER'], true) ? strtoupper(trim($role)) : 'USER';
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $now = gmdate('Y-m-d H:i:s');

    if (findUserByEmail($cleanEmail) !== null) {
        throw new RuntimeException("A user with email '{$cleanEmail}' already exists.");
    }

    if (checkDbHealth()) {
        $pdo = getDb();
        $stmt = $pdo->prepare(
            'INSERT INTO users (name, email, password_hash, role, created_at, updated_at) ' .
            'VALUES (:name, :email, :password_hash, :role, :created_at, :updated_at) RETURNING id'
        );
        $stmt->execute([
            ':name' => $cleanName,
            ':email' => $cleanEmail,
            ':password_hash' => $passwordHash,
            ':role' => $cleanRole,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);
        $newId = (int) $stmt->fetchColumn();
        return [
            'id' => $newId,
            'name' => $cleanName,
            'email' => $cleanEmail,
            'role' => $cleanRole,
        ];
    }

    $users = readUsersJsonStore();
    $maxId = 0;
    foreach ($users as $u) {
        if (isset($u['id']) && $u['id'] > $maxId) {
            $maxId = (int) $u['id'];
        }
    }
    $newId = $maxId + 1;
    $users[] = [
        'id' => $newId,
        'name' => $cleanName,
        'email' => $cleanEmail,
        'password_hash' => $passwordHash,
        'role' => $cleanRole,
        'createdAt' => $now,
        'updatedAt' => $now,
    ];
    writeUsersJsonStore($users);

    return [
        'id' => $newId,
        'name' => $cleanName,
        'email' => $cleanEmail,
        'role' => $cleanRole,
    ];
}

/**
 * Authenticates a user with email and password.
 *
 * @return array{id: int, name: string, email: string, role: string}|null
 */
function authenticateUser(string $email, string $password): ?array
{
    $user = findUserByEmail($email);
    if ($user === null) {
        return null;
    }

    $hash = (string) ($user['password_hash'] ?? '');
    if (!password_verify($password, $hash)) {
        return null;
    }

    return [
        'id' => (int) $user['id'],
        'name' => (string) $user['name'],
        'email' => (string) $user['email'],
        'role' => (string) ($user['role'] ?? 'USER'),
    ];
}

// ==========================================
// USER FAVORITES MANAGEMENT
// ==========================================

/**
 * Retrieves list of favorite event IDs for a given user.
 *
 * @return list<int>
 */
function getUserFavoriteEventIds(int $userId): array
{
    if (checkDbHealth()) {
        $pdo = getDb();
        $stmt = $pdo->prepare('SELECT event_id FROM user_favorites WHERE user_id = :uid ORDER BY created_at DESC');
        $stmt->execute([':uid' => $userId]);
        $rows = $stmt->fetchAll();
        return array_map(fn ($r) => (int) $r['event_id'], $rows);
    }

    $favorites = readFavoritesJsonStore();
    $ids = [];
    foreach ($favorites as $f) {
        if ((int) ($f['userId'] ?? 0) === $userId && isset($f['eventId'])) {
            $ids[] = (int) $f['eventId'];
        }
    }
    return $ids;
}

/**
 * Adds an event to a user's favorites.
 */
function addUserFavorite(int $userId, int $eventId): bool
{
    if (checkDbHealth()) {
        $pdo = getDb();
        $stmt = $pdo->prepare(
            'INSERT INTO user_favorites (user_id, event_id, created_at) ' .
            'VALUES (:uid, :eid, CURRENT_TIMESTAMP) ON CONFLICT (user_id, event_id) DO NOTHING'
        );
        $stmt->execute([':uid' => $userId, ':eid' => $eventId]);
        return true;
    }

    $favorites = readFavoritesJsonStore();
    foreach ($favorites as $f) {
        if ((int) ($f['userId'] ?? 0) === $userId && (int) ($f['eventId'] ?? 0) === $eventId) {
            return true; // Already favorited
        }
    }

    $maxId = 0;
    foreach ($favorites as $f) {
        if (isset($f['id']) && $f['id'] > $maxId) {
            $maxId = (int) $f['id'];
        }
    }

    $favorites[] = [
        'id' => $maxId + 1,
        'userId' => $userId,
        'eventId' => $eventId,
        'createdAt' => gmdate('Y-m-d H:i:s'),
    ];
    writeFavoritesJsonStore($favorites);
    return true;
}

/**
 * Removes an event from a user's favorites.
 */
function removeUserFavorite(int $userId, int $eventId): bool
{
    if (checkDbHealth()) {
        $pdo = getDb();
        $stmt = $pdo->prepare('DELETE FROM user_favorites WHERE user_id = :uid AND event_id = :eid');
        $stmt->execute([':uid' => $userId, ':eid' => $eventId]);
        return $stmt->rowCount() > 0;
    }

    $favorites = readFavoritesJsonStore();
    $filtered = array_filter($favorites, function ($f) use ($userId, $eventId) {
        return !((int) ($f['userId'] ?? 0) === $userId && (int) ($f['eventId'] ?? 0) === $eventId);
    });

    if (count($filtered) !== count($favorites)) {
        writeFavoritesJsonStore(array_values($filtered));
        return true;
    }
    return false;
}
