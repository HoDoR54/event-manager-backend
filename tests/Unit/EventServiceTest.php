<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Tests\Unit;

use HponeTuakNyi\EventManagerBackend\DTOs\EventDto;
use HponeTuakNyi\EventManagerBackend\DTOs\UploadPayloadDto;
use HponeTuakNyi\EventManagerBackend\Repositories\EventRepositoryInterface;
use HponeTuakNyi\EventManagerBackend\Services\EventService;
use PHPUnit\Framework\TestCase;

final class EventServiceTest extends TestCase
{
    public function testUploadCoordinatesRepositorySync(): void
    {
        $repository = $this->createMock(EventRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('syncEvents')
            ->willReturn(['eventsSynced' => 2, 'sessionsSynced' => 5]);

        $service = new EventService($repository);

        $payload = new UploadPayloadDto(
            uploadTimestamp: '2026-11-09T10:00:00Z',
            appVersion: '1.0',
            eventCount: 2,
            events: [
                new EventDto(1, 'E1', '2026-12-01', 'L1', 'O1', 'CONFERENCE'),
                new EventDto(2, 'E2', '2026-12-02', 'L2', 'O2', 'WORKSHOP'),
            ]
        );

        $result = $service->upload($payload);

        $this->assertSame('success', $result['status']);
        $this->assertSame(2, $result['uploadedCount']);
        $this->assertSame(5, $result['sessionCount']);
        $this->assertStringContainsString('Successfully uploaded 2 events and 5 sessions', $result['message']);
    }

    public function testListEventsDelegatesToRepository(): void
    {
        $mockData = [
            ['id' => 1, 'name' => 'Event 1', 'sessions' => []],
        ];

        $repository = $this->createMock(EventRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('getAllEventsWithSessions')
            ->willReturn($mockData);

        $service = new EventService($repository);
        $this->assertSame($mockData, $service->listEvents());
    }

    public function testGetEventDelegatesToRepository(): void
    {
        $mockEvent = ['id' => 5, 'name' => 'Event 5', 'sessions' => []];

        $repository = $this->createMock(EventRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('getEventWithSessions')
            ->with(5)
            ->willReturn($mockEvent);

        $service = new EventService($repository);
        $this->assertSame($mockEvent, $service->getEvent(5));
    }

    public function testDeleteEventDelegatesToRepository(): void
    {
        $repository = $this->createMock(EventRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('deleteEvent')
            ->with(3)
            ->willReturn(true);

        $service = new EventService($repository);
        $this->assertTrue($service->deleteEvent(3));
    }
}
