<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\DTOs;

final class UploadPayloadDto
{
    /**
     * @param list<EventDto> $events
     */
    public function __construct(
        public readonly string $uploadTimestamp,
        public readonly string $appVersion,
        public readonly int $eventCount,
        public readonly array $events = []
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $events = [];
        if (isset($data['events']) && is_array($data['events'])) {
            foreach ($data['events'] as $eventData) {
                if (is_array($eventData)) {
                    $events[] = EventDto::fromArray($eventData);
                }
            }
        }

        return new self(
            uploadTimestamp: (string) ($data['uploadTimestamp'] ?? date('c')),
            appVersion: (string) ($data['appVersion'] ?? '1.0'),
            eventCount: (int) ($data['eventCount'] ?? count($events)),
            events: $events
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'uploadTimestamp' => $this->uploadTimestamp,
            'appVersion' => $this->appVersion,
            'eventCount' => $this->eventCount,
            'events' => array_map(fn (EventDto $e) => $e->toArray(), $this->events),
        ];
    }
}
