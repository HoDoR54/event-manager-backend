<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Services;

use HponeTuakNyi\EventManagerBackend\DTOs\UploadPayloadDto;
use HponeTuakNyi\EventManagerBackend\Repositories\EventRepositoryInterface;

final class EventService
{
    public function __construct(private readonly EventRepositoryInterface $repository)
    {
    }

    /**
     * @return array{
     *     status: string,
     *     message: string,
     *     uploadedCount: int,
     *     sessionCount: int,
     *     timestamp: string
     * }
     */
    public function upload(UploadPayloadDto $payload): array
    {
        $syncResult = $this->repository->syncEvents($payload->events);

        return [
            'status' => 'success',
            'message' => sprintf(
                'Successfully uploaded %d events and %d sessions.',
                $syncResult['eventsSynced'],
                $syncResult['sessionsSynced']
            ),
            'uploadedCount' => $syncResult['eventsSynced'],
            'sessionCount' => $syncResult['sessionsSynced'],
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listEvents(): array
    {
        return $this->repository->getAllEventsWithSessions();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getEvent(int $id): ?array
    {
        return $this->repository->getEventWithSessions($id);
    }

    public function deleteEvent(int $id): bool
    {
        return $this->repository->deleteEvent($id);
    }
}
