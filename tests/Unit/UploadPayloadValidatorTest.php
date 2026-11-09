<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Tests\Unit;

use HponeTuakNyi\EventManagerBackend\Validators\UploadPayloadValidator;
use PHPUnit\Framework\TestCase;

final class UploadPayloadValidatorTest extends TestCase
{
    private UploadPayloadValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new UploadPayloadValidator();
    }

    public function testValidPayloadPassesValidation(): void
    {
        $payload = [
            'uploadTimestamp' => '2026-11-09T10:00:00Z',
            'appVersion' => '1.0',
            'eventCount' => 1,
            'events' => [
                [
                    'id' => 1,
                    'name' => 'Tech Summit 2026',
                    'date' => '2026-12-10',
                    'location' => 'Tech Hall',
                    'organiser' => 'Tech Society',
                    'type' => 'CONFERENCE',
                    'status' => 'UPCOMING',
                    'description' => 'Annual conference',
                    'contactEmail' => 'info@tech.org',
                    'contactPhone' => '+441234567890',
                    'sessions' => [
                        [
                            'sessionId' => 'S01',
                            'name' => 'Keynote',
                            'time' => '09:00 - 10:00',
                            'type' => 'KEYNOTE',
                            'speaker' => 'Dr. Alan',
                            'room' => 'Hall 1',
                        ],
                    ],
                ],
            ],
        ];

        $result = $this->validator->validate($payload);

        $this->assertTrue($result['isValid']);
        $this->assertEmpty($result['errors']);
    }

    public function testMissingEventsFieldFails(): void
    {
        $result = $this->validator->validate([]);

        $this->assertFalse($result['isValid']);
        $this->assertContains("Field 'events' is required and must be an array.", $result['errors']);
    }

    public function testMissingRequiredEventFieldsFails(): void
    {
        $payload = [
            'events' => [
                [
                    'description' => 'Missing all required fields',
                ],
            ],
        ];

        $result = $this->validator->validate($payload);

        $this->assertFalse($result['isValid']);
        $this->assertContains("Event #0: 'name' is required.", $result['errors']);
        $this->assertContains("Event #0: 'date' must be in YYYY-MM-DD format.", $result['errors']);
        $this->assertContains("Event #0: 'location' is required.", $result['errors']);
        $this->assertContains("Event #0: 'organiser' is required.", $result['errors']);
        $this->assertContains("Event #0: 'type' must be one of: CONFERENCE, WORKSHOP, SEMINAR, SOCIAL, OTHER", $result['errors']);
    }

    public function testInvalidDateFormatFails(): void
    {
        $payload = [
            'events' => [
                [
                    'name' => 'Event',
                    'date' => '10/12/2026',
                    'location' => 'Hall',
                    'organiser' => 'Org',
                    'type' => 'WORKSHOP',
                ],
            ],
        ];

        $result = $this->validator->validate($payload);

        $this->assertFalse($result['isValid']);
        $this->assertContains("Event #0: 'date' must be in YYYY-MM-DD format.", $result['errors']);
    }

    public function testInvalidEmailFails(): void
    {
        $payload = [
            'events' => [
                [
                    'name' => 'Event',
                    'date' => '2026-12-10',
                    'location' => 'Hall',
                    'organiser' => 'Org',
                    'type' => 'WORKSHOP',
                    'contactEmail' => 'not-an-email',
                ],
            ],
        ];

        $result = $this->validator->validate($payload);

        $this->assertFalse($result['isValid']);
        $this->assertContains("Event #0: 'contactEmail' is invalid.", $result['errors']);
    }

    public function testInvalidSessionFieldsFails(): void
    {
        $payload = [
            'events' => [
                [
                    'name' => 'Event',
                    'date' => '2026-12-10',
                    'location' => 'Hall',
                    'organiser' => 'Org',
                    'type' => 'WORKSHOP',
                    'sessions' => [
                        [
                            'description' => 'Missing session required fields',
                        ],
                    ],
                ],
            ],
        ];

        $result = $this->validator->validate($payload);

        $this->assertFalse($result['isValid']);
        $this->assertContains("Event #0 Session #0: 'sessionId' is required.", $result['errors']);
        $this->assertContains("Event #0 Session #0: 'name' is required.", $result['errors']);
        $this->assertContains("Event #0 Session #0: 'time' is required.", $result['errors']);
        $this->assertContains("Event #0 Session #0: 'type' must be one of: WORKSHOP, PRESENTATION, PANEL_DISCUSSION, NETWORKING, KEYNOTE, OTHERS", $result['errors']);
    }
}
