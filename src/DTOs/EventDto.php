<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\DTOs;

final class EventDto
{
    /**
     * @param list<SessionDto> $sessions
     */
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $date,
        public readonly string $location,
        public readonly string $organiser,
        public readonly string $type,
        public readonly string $status = 'UPCOMING',
        public readonly ?string $description = null,
        public readonly ?string $contactEmail = null,
        public readonly ?string $contactPhone = null,
        public readonly array $sessions = []
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $sessions = [];
        if (isset($data['sessions']) && is_array($data['sessions'])) {
            foreach ($data['sessions'] as $sessionData) {
                if (is_array($sessionData)) {
                    $sessions[] = SessionDto::fromArray($sessionData);
                }
            }
        }

        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            name: (string) ($data['name'] ?? ''),
            date: (string) ($data['date'] ?? ''),
            location: (string) ($data['location'] ?? ''),
            organiser: (string) ($data['organiser'] ?? ''),
            type: (string) ($data['type'] ?? ''),
            status: (string) ($data['status'] ?? 'UPCOMING'),
            description: isset($data['description']) && $data['description'] !== '' ? (string) $data['description'] : null,
            contactEmail: isset($data['contactEmail']) && $data['contactEmail'] !== '' ? (string) $data['contactEmail'] : null,
            contactPhone: isset($data['contactPhone']) && $data['contactPhone'] !== '' ? (string) $data['contactPhone'] : null,
            sessions: $sessions
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date' => $this->date,
            'location' => $this->location,
            'organiser' => $this->organiser,
            'type' => $this->type,
            'status' => $this->status,
            'description' => $this->description,
            'contactEmail' => $this->contactEmail,
            'contactPhone' => $this->contactPhone,
            'sessions' => array_map(fn (SessionDto $s) => $s->toArray(), $this->sessions),
        ];
    }
}
