<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Repositories;

use HponeTuakNyi\EventManagerBackend\DTOs\EventDto;

interface EventRepositoryInterface
{
    /**
     * Synchronizes a batch of events and their nested sessions.
     *
     * @param list<EventDto> $events
     * @return array{eventsSynced: int, sessionsSynced: int}
     */
    public function syncEvents(array $events): array;

    /**
     * Retrieves all events along with their nested sessions.
     *
     * @return list<array<string, mixed>>
     */
    public function getAllEventsWithSessions(): array;

    /**
     * Retrieves a single event with its nested sessions.
     *
     * @return array<string, mixed>|null
     */
    public function getEventWithSessions(int $id): ?array;

    /**
     * Deletes an event by its ID.
     */
    public function deleteEvent(int $id): bool;

    /**
     * Clears all events and cascaded sessions.
     */
    public function clearAll(): void;
}
