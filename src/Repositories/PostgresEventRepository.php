<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Repositories;

use HponeTuakNyi\EventManagerBackend\DTOs\EventDto;
use PDO;
use Throwable;

final class PostgresEventRepository implements EventRepositoryInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @param list<EventDto> $events
     * @return array{eventsSynced: int, sessionsSynced: int}
     */
    public function syncEvents(array $events): array
    {
        $eventsSynced = 0;
        $sessionsSynced = 0;

        $this->pdo->beginTransaction();

        try {
            $selectStmt = $this->pdo->prepare(
                'SELECT id FROM events WHERE LOWER(name) = LOWER(:name) AND date = :date'
            );

            $updateStmt = $this->pdo->prepare(
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

            $insertStmt = $this->pdo->prepare(
                'INSERT INTO events (
                    client_id, name, date, location, organiser, type, status, description, contact_email, contact_phone
                ) VALUES (
                    :client_id, :name, :date, :location, :organiser, :type, :status, :description, :contact_email, :contact_phone
                ) RETURNING id'
            );

            $deleteSessionsStmt = $this->pdo->prepare(
                'DELETE FROM sessions WHERE event_id = :event_id'
            );

            $insertSessionStmt = $this->pdo->prepare(
                'INSERT INTO sessions (
                    event_id, session_id, name, time, type, speaker, room, description
                ) VALUES (
                    :event_id, :session_id, :name, :time, :type, :speaker, :room, :description
                )'
            );

            foreach ($events as $event) {
                $selectStmt->execute([
                    ':name' => $event->name,
                    ':date' => $event->date,
                ]);
                $existing = $selectStmt->fetch(PDO::FETCH_ASSOC);

                if ($existing !== false && isset($existing['id'])) {
                    $eventId = (int) $existing['id'];
                    $updateStmt->execute([
                        ':id' => $eventId,
                        ':client_id' => $event->id,
                        ':location' => $event->location,
                        ':organiser' => $event->organiser,
                        ':type' => $event->type,
                        ':status' => $event->status,
                        ':description' => $event->description,
                        ':contact_email' => $event->contactEmail,
                        ':contact_phone' => $event->contactPhone,
                    ]);
                } else {
                    $insertStmt->execute([
                        ':client_id' => $event->id,
                        ':name' => $event->name,
                        ':date' => $event->date,
                        ':location' => $event->location,
                        ':organiser' => $event->organiser,
                        ':type' => $event->type,
                        ':status' => $event->status,
                        ':description' => $event->description,
                        ':contact_email' => $event->contactEmail,
                        ':contact_phone' => $event->contactPhone,
                    ]);
                    $inserted = $insertStmt->fetch(PDO::FETCH_ASSOC);
                    $eventId = (int) $inserted['id'];
                }

                $eventsSynced++;

                // Clear prior sessions for this event to avoid duplicates
                $deleteSessionsStmt->execute([':event_id' => $eventId]);

                foreach ($event->sessions as $session) {
                    $insertSessionStmt->execute([
                        ':event_id' => $eventId,
                        ':session_id' => $session->sessionId,
                        ':name' => $session->name,
                        ':time' => $session->time,
                        ':type' => $session->type,
                        ':speaker' => $session->speaker,
                        ':room' => $session->room,
                        ':description' => $session->description,
                    ]);
                    $sessionsSynced++;
                }
            }

            $this->pdo->commit();

            return [
                'eventsSynced' => $eventsSynced,
                'sessionsSynced' => $sessionsSynced,
            ];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAllEventsWithSessions(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM events ORDER BY date ASC, id ASC');
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($events)) {
            return [];
        }

        $sessionStmt = $this->pdo->prepare('SELECT * FROM sessions WHERE event_id = :event_id ORDER BY id ASC');

        $result = [];
        foreach ($events as $event) {
            $sessionStmt->execute([':event_id' => $event['id']]);
            $sessions = $sessionStmt->fetchAll(PDO::FETCH_ASSOC);

            $result[] = $this->formatEventWithSessions($event, $sessions);
        }

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getEventWithSessions(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            return null;
        }

        $sessionStmt = $this->pdo->prepare('SELECT * FROM sessions WHERE event_id = :event_id ORDER BY id ASC');
        $sessionStmt->execute([':event_id' => $id]);
        $sessions = $sessionStmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->formatEventWithSessions($event, $sessions);
    }

    public function deleteEvent(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM events WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function clearAll(): void
    {
        $this->pdo->exec('TRUNCATE TABLE sessions, events RESTART IDENTITY CASCADE');
    }

    /**
     * Formats database rows into clean camelCase payload representation.
     *
     * @param array<string, mixed> $event
     * @param list<array<string, mixed>> $sessions
     * @return array<string, mixed>
     */
    private function formatEventWithSessions(array $event, array $sessions): array
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
            'sessions' => array_map(function (array $s) {
                return [
                    'id' => (int) $s['id'],
                    'eventId' => (int) $s['event_id'],
                    'sessionId' => (string) $s['session_id'],
                    'name' => (string) $s['name'],
                    'time' => (string) $s['time'],
                    'type' => (string) $s['type'],
                    'speaker' => $s['speaker'] !== null ? (string) $s['speaker'] : null,
                    'room' => $s['room'] !== null ? (string) $s['room'] : null,
                    'description' => $s['description'] !== null ? (string) $s['description'] : null,
                ];
            }, $sessions),
        ];
    }
}
