<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Tests\Unit;

use HponeTuakNyi\EventManagerBackend\DTOs\EventDto;
use HponeTuakNyi\EventManagerBackend\DTOs\SessionDto;
use HponeTuakNyi\EventManagerBackend\DTOs\UploadPayloadDto;
use PHPUnit\Framework\TestCase;

final class EventDtoTest extends TestCase
{
    public function testEventDtoFromArrayAndToArray(): void
    {
        $data = [
            'id' => 1,
            'name' => 'Kotlin Conf',
            'date' => '2026-11-20',
            'location' => 'Main Auditorium',
            'organiser' => 'JetBrains',
            'type' => 'CONFERENCE',
            'status' => 'UPCOMING',
            'description' => 'Great conference',
            'contactEmail' => 'kotlin@example.com',
            'contactPhone' => '1234567890',
            'sessions' => [
                [
                    'sessionId' => 'S1',
                    'name' => 'Compose Multiplatform',
                    'time' => '10:00 - 11:00',
                    'type' => 'PRESENTATION',
                    'speaker' => 'Roman',
                    'room' => 'Room 101',
                    'description' => 'Intro to CMP',
                ],
            ],
        ];

        $event = EventDto::fromArray($data);

        $this->assertSame(1, $event->id);
        $this->assertSame('Kotlin Conf', $event->name);
        $this->assertSame('2026-11-20', $event->date);
        $this->assertSame('Main Auditorium', $event->location);
        $this->assertSame('JetBrains', $event->organiser);
        $this->assertSame('CONFERENCE', $event->type);
        $this->assertSame('UPCOMING', $event->status);
        $this->assertSame('Great conference', $event->description);
        $this->assertSame('kotlin@example.com', $event->contactEmail);
        $this->assertSame('1234567890', $event->contactPhone);
        $this->assertCount(1, $event->sessions);

        $session = $event->sessions[0];
        $this->assertSame('S1', $session->sessionId);
        $this->assertSame('Compose Multiplatform', $session->name);
        $this->assertSame('10:00 - 11:00', $session->time);
        $this->assertSame('PRESENTATION', $session->type);
        $this->assertSame('Roman', $session->speaker);
        $this->assertSame('Room 101', $session->room);
        $this->assertSame('Intro to CMP', $session->description);

        $array = $event->toArray();
        $this->assertSame($data['name'], $array['name']);
        $this->assertSame($data['date'], $array['date']);
        $this->assertCount(1, $array['sessions']);
    }

    public function testUploadPayloadDtoFromArrayAndToArray(): void
    {
        $data = [
            'uploadTimestamp' => '2026-11-09T12:00:00Z',
            'appVersion' => '1.0',
            'eventCount' => 1,
            'events' => [
                [
                    'id' => 10,
                    'name' => 'Workshop 1',
                    'date' => '2026-12-01',
                    'location' => 'Lab B',
                    'organiser' => 'Uni',
                    'type' => 'WORKSHOP',
                    'status' => 'UPCOMING',
                    'sessions' => [],
                ],
            ],
        ];

        $payload = UploadPayloadDto::fromArray($data);

        $this->assertSame('2026-11-09T12:00:00Z', $payload->uploadTimestamp);
        $this->assertSame('1.0', $payload->appVersion);
        $this->assertSame(1, $payload->eventCount);
        $this->assertCount(1, $payload->events);
        $this->assertSame('Workshop 1', $payload->events[0]->name);
    }
}
