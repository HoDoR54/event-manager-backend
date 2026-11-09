<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Validators;

final class UploadPayloadValidator
{
    private const VALID_EVENT_TYPES = [
        'CONFERENCE',
        'WORKSHOP',
        'SEMINAR',
        'SOCIAL',
        'OTHER',
    ];

    private const VALID_EVENT_STATUSES = [
        'UPCOMING',
        'COMPLETED',
        'CANCELLED',
    ];

    private const VALID_SESSION_TYPES = [
        'WORKSHOP',
        'PRESENTATION',
        'PANEL_DISCUSSION',
        'NETWORKING',
        'KEYNOTE',
        'OTHERS',
    ];

    /**
     * Validates upload payload raw array.
     *
     * @param array<string, mixed> $data
     * @return array{isValid: bool, errors: list<string>}
     */
    public function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['events']) || !is_array($data['events'])) {
            $errors[] = "Field 'events' is required and must be an array.";
            return ['isValid' => false, 'errors' => $errors];
        }

        foreach ($data['events'] as $index => $event) {
            $prefix = "Event #{$index}";

            if (!is_array($event)) {
                $errors[] = "{$prefix} must be an object.";
                continue;
            }

            // Name validation
            if (!isset($event['name']) || trim((string) $event['name']) === '') {
                $errors[] = "{$prefix}: 'name' is required.";
            }

            // Date validation (YYYY-MM-DD)
            if (!isset($event['date']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $event['date'])) {
                $errors[] = "{$prefix}: 'date' must be in YYYY-MM-DD format.";
            }

            // Location validation
            if (!isset($event['location']) || trim((string) $event['location']) === '') {
                $errors[] = "{$prefix}: 'location' is required.";
            }

            // Organiser validation
            if (!isset($event['organiser']) || trim((string) $event['organiser']) === '') {
                $errors[] = "{$prefix}: 'organiser' is required.";
            }

            // Type validation
            if (!isset($event['type']) || !in_array(strtoupper((string) $event['type']), self::VALID_EVENT_TYPES, true)) {
                $errors[] = "{$prefix}: 'type' must be one of: " . implode(', ', self::VALID_EVENT_TYPES);
            }

            // Status validation (optional, defaults to UPCOMING)
            if (isset($event['status']) && !in_array(strtoupper((string) $event['status']), self::VALID_EVENT_STATUSES, true)) {
                $errors[] = "{$prefix}: 'status' must be one of: " . implode(', ', self::VALID_EVENT_STATUSES);
            }

            // Email format if provided
            if (isset($event['contactEmail']) && trim((string) $event['contactEmail']) !== '') {
                if (!filter_var($event['contactEmail'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "{$prefix}: 'contactEmail' is invalid.";
                }
            }

            // Sessions validation
            if (isset($event['sessions'])) {
                if (!is_array($event['sessions'])) {
                    $errors[] = "{$prefix}: 'sessions' must be an array.";
                } else {
                    foreach ($event['sessions'] as $sessionIndex => $session) {
                        $sPrefix = "{$prefix} Session #{$sessionIndex}";
                        if (!is_array($session)) {
                            $errors[] = "{$sPrefix} must be an object.";
                            continue;
                        }

                        if (!isset($session['sessionId']) || trim((string) $session['sessionId']) === '') {
                            $errors[] = "{$sPrefix}: 'sessionId' is required.";
                        }
                        if (!isset($session['name']) || trim((string) $session['name']) === '') {
                            $errors[] = "{$sPrefix}: 'name' is required.";
                        }
                        if (!isset($session['time']) || trim((string) $session['time']) === '') {
                            $errors[] = "{$sPrefix}: 'time' is required.";
                        }
                        if (!isset($session['type']) || !in_array(strtoupper((string) $session['type']), self::VALID_SESSION_TYPES, true)) {
                            $errors[] = "{$sPrefix}: 'type' must be one of: " . implode(', ', self::VALID_SESSION_TYPES);
                        }
                    }
                }
            }
        }

        return [
            'isValid' => empty($errors),
            'errors' => $errors,
        ];
    }
}
