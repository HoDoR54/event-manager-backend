<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\DTOs;

final class SessionDto
{
    public function __construct(
        public readonly string $sessionId,
        public readonly string $name,
        public readonly string $time,
        public readonly string $type,
        public readonly ?string $speaker = null,
        public readonly ?string $room = null,
        public readonly ?string $description = null
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            sessionId: (string) ($data['sessionId'] ?? ''),
            name: (string) ($data['name'] ?? ''),
            time: (string) ($data['time'] ?? ''),
            type: (string) ($data['type'] ?? ''),
            speaker: isset($data['speaker']) && $data['speaker'] !== '' ? (string) $data['speaker'] : null,
            room: isset($data['room']) && $data['room'] !== '' ? (string) $data['room'] : null,
            description: isset($data['description']) && $data['description'] !== '' ? (string) $data['description'] : null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'sessionId' => $this->sessionId,
            'name' => $this->name,
            'time' => $this->time,
            'type' => $this->type,
            'speaker' => $this->speaker,
            'room' => $this->room,
            'description' => $this->description,
        ];
    }
}
