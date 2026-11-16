<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/db.php';

/**
 * Sends a JSON response with status code and exits.
 */
function sendJson(mixed $data, int $status = 200): void
{
    http_response_code($status);
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Validates the bulk upload payload.
 *
 * @param array<string, mixed> $data
 * @return array{isValid: bool, errors: list<string>}
 */
function validateUploadPayload(array $data): array
{
    $validEventTypes = ['CONFERENCE', 'WORKSHOP', 'SEMINAR', 'SOCIAL', 'OTHER'];
    $validEventStatuses = ['UPCOMING', 'COMPLETED', 'CANCELLED'];
    $validSessionTypes = ['WORKSHOP', 'PRESENTATION', 'PANEL_DISCUSSION', 'NETWORKING', 'KEYNOTE', 'OTHERS'];

    $errors = [];

    if (!isset($data['events']) || !is_array($data['events'])) {
        return [
            'isValid' => false,
            'errors' => ["Field 'events' is required and must be an array."],
        ];
    }

    foreach ($data['events'] as $index => $event) {
        $prefix = "Event #{$index}";

        if (!is_array($event)) {
            $errors[] = "{$prefix} must be an object.";
            continue;
        }

        if (!isset($event['name']) || trim((string) $event['name']) === '') {
            $errors[] = "{$prefix}: 'name' is required.";
        }

        if (!isset($event['date']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $event['date'])) {
            $errors[] = "{$prefix}: 'date' must be in YYYY-MM-DD format.";
        }

        if (!isset($event['location']) || trim((string) $event['location']) === '') {
            $errors[] = "{$prefix}: 'location' is required.";
        }

        if (!isset($event['organiser']) || trim((string) $event['organiser']) === '') {
            $errors[] = "{$prefix}: 'organiser' is required.";
        }

        if (!isset($event['type']) || !in_array(strtoupper((string) $event['type']), $validEventTypes, true)) {
            $errors[] = "{$prefix}: 'type' must be one of: " . implode(', ', $validEventTypes);
        }

        if (isset($event['status']) && !in_array(strtoupper((string) $event['status']), $validEventStatuses, true)) {
            $errors[] = "{$prefix}: 'status' must be one of: " . implode(', ', $validEventStatuses);
        }

        if (isset($event['contactEmail']) && trim((string) $event['contactEmail']) !== '') {
            if (!filter_var($event['contactEmail'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "{$prefix}: 'contactEmail' is invalid.";
            }
        }

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
                    if (!isset($session['type']) || !in_array(strtoupper((string) $session['type']), $validSessionTypes, true)) {
                        $errors[] = "{$sPrefix}: 'type' must be one of: " . implode(', ', $validSessionTypes);
                    }
                }
            }
        }

        if (isset($event['images'])) {
            if (!is_array($event['images'])) {
                $errors[] = "{$prefix}: 'images' must be an array.";
            } else {
                foreach ($event['images'] as $imgIndex => $img) {
                    $iPrefix = "{$prefix} Image #{$imgIndex}";
                    if (!is_array($img)) {
                        $errors[] = "{$iPrefix} must be an object.";
                        continue;
                    }
                    $hasUrl = isset($img['imageUrl']) && trim((string) $img['imageUrl']) !== '';
                    $hasData = isset($img['imageData']) && trim((string) $img['imageData']) !== '';
                    if (!$hasUrl && !$hasData) {
                        $errors[] = "{$iPrefix}: either 'imageUrl' or 'imageData' must be provided.";
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

// Parse request
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/') ?: '/';

// Preflight CORS support
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization');
    http_response_code(204);
    exit;
}

// Helper: Extracts Bearer token from headers
function getBearerToken(): ?string
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if ($header === '' && function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        $header = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    }
    if (preg_match('/Bearer\s+(\S+)/i', $header, $matches)) {
        return $matches[1];
    }
    return null;
}

// Helper: Retrieves currently authenticated user
function getAuthenticatedUser(): ?array
{
    $token = getBearerToken();
    if ($token === null) {
        return null;
    }
    $payload = verifyAuthToken($token);
    if ($payload === null || !isset($payload['uid'])) {
        return null;
    }
    return findUserById((int) $payload['uid']);
}

// GET /
if ($method === 'GET' && $path === '/') {
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Event Manager API';
    exit;
}

// GET /api/health
if ($method === 'GET' && $path === '/api/health') {
    $dbUp = checkDbHealth();
    sendJson([
        'status' => $dbUp ? 'ok' : 'error',
        'database' => $dbUp ? 'up' : 'down',
    ], $dbUp ? 200 : 503);
}

// ==========================================
// AUTHENTICATION ROUTES
// ==========================================

// POST /api/auth/login
if ($method === 'POST' && $path === '/api/auth/login') {
    $rawInput = file_get_contents(PHP_SAPI === 'cli' ? 'php://stdin' : 'php://input');
    $data = json_decode($rawInput ?: '', true);

    $email = isset($data['email']) ? trim((string) $data['email']) : '';
    $password = isset($data['password']) ? (string) $data['password'] : '';

    if ($email === '' || $password === '') {
        sendJson([
            'status' => 'error',
            'message' => "Both 'email' and 'password' are required.",
        ], 400);
    }

    $user = authenticateUser($email, $password);
    if ($user === null) {
        sendJson([
            'status' => 'error',
            'message' => 'Invalid email or password.',
        ], 401);
    }

    $token = generateAuthToken($user);
    sendJson([
        'status' => 'success',
        'message' => 'Login successful.',
        'token' => $token,
        'user' => $user,
    ], 200);
}

// POST /api/auth/register
if ($method === 'POST' && $path === '/api/auth/register') {
    $rawInput = file_get_contents(PHP_SAPI === 'cli' ? 'php://stdin' : 'php://input');
    $data = json_decode($rawInput ?: '', true);

    $name = isset($data['name']) ? trim((string) $data['name']) : '';
    $email = isset($data['email']) ? trim((string) $data['email']) : '';
    $password = isset($data['password']) ? (string) $data['password'] : '';
    $role = isset($data['role']) ? trim((string) $data['role']) : 'USER';

    $errors = [];
    if ($name === '') {
        $errors[] = "'name' is required.";
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid 'email' is required.";
    }
    if (strlen($password) < 4) {
        $errors[] = "'password' must be at least 4 characters.";
    }

    if (!empty($errors)) {
        sendJson([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $errors,
        ], 422);
    }

    try {
        $newUser = registerUser($name, $email, $password, $role);
        $token = generateAuthToken($newUser);
        sendJson([
            'status' => 'success',
            'message' => 'User registered successfully.',
            'token' => $token,
            'user' => $newUser,
        ], 201);
    } catch (Throwable $e) {
        sendJson([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 409);
    }
}

// GET /api/auth/me
if ($method === 'GET' && $path === '/api/auth/me') {
    $user = getAuthenticatedUser();
    if ($user === null) {
        sendJson([
            'status' => 'error',
            'message' => 'Unauthorized. Please provide a valid Bearer token.',
        ], 401);
    }

    sendJson([
        'status' => 'success',
        'user' => $user,
    ], 200);
}

// ==========================================
// USER FAVORITES ROUTES
// ==========================================

// GET /api/favorites
if ($method === 'GET' && $path === '/api/favorites') {
    $user = getAuthenticatedUser();
    if ($user === null) {
        sendJson([
            'status' => 'error',
            'message' => 'Unauthorized. Please provide a valid Bearer token.',
        ], 401);
    }

    $favIds = getUserFavoriteEventIds((int) $user['id']);
    sendJson([
        'status' => 'success',
        'favoriteEventIds' => $favIds,
        'count' => count($favIds),
    ], 200);
}

// POST /api/favorites
if ($method === 'POST' && $path === '/api/favorites') {
    $user = getAuthenticatedUser();
    if ($user === null) {
        sendJson([
            'status' => 'error',
            'message' => 'Unauthorized. Please provide a valid Bearer token.',
        ], 401);
    }

    $rawInput = file_get_contents(PHP_SAPI === 'cli' ? 'php://stdin' : 'php://input');
    $data = json_decode($rawInput ?: '', true);
    $eventId = isset($data['eventId']) ? (int) $data['eventId'] : 0;

    if ($eventId <= 0) {
        sendJson([
            'status' => 'error',
            'message' => "'eventId' is required and must be a positive integer.",
        ], 422);
    }

    addUserFavorite((int) $user['id'], $eventId);
    sendJson([
        'status' => 'success',
        'message' => 'Event favorited.',
        'eventId' => $eventId,
        'isFavorite' => true,
    ], 200);
}

// DELETE /api/favorites/{eventId}
if ($method === 'DELETE' && preg_match('#^/api/favorites/(\d+)$#', $path, $matches)) {
    $user = getAuthenticatedUser();
    if ($user === null) {
        sendJson([
            'status' => 'error',
            'message' => 'Unauthorized. Please provide a valid Bearer token.',
        ], 401);
    }

    $eventId = (int) $matches[1];
    removeUserFavorite((int) $user['id'], $eventId);
    sendJson([
        'status' => 'success',
        'message' => 'Event removed from favorites.',
        'eventId' => $eventId,
        'isFavorite' => false,
    ], 200);
}

// POST /api/events/upload
if ($method === 'POST' && $path === '/api/events/upload') {
    $rawInput = file_get_contents(PHP_SAPI === 'cli' ? 'php://stdin' : 'php://input');
    $data = json_decode($rawInput ?: '', true);

    if (!is_array($data)) {
        sendJson([
            'status' => 'error',
            'message' => 'Malformed JSON body.',
        ], 400);
    }

    $validation = validateUploadPayload($data);
    if (!$validation['isValid']) {
        sendJson([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $validation['errors'],
        ], 422);
    }

    try {
        $result = syncEvents($data['events']);
        sendJson([
            'status' => 'success',
            'message' => sprintf(
                'Successfully uploaded %d events, %d sessions, and %d images.',
                $result['eventsSynced'],
                $result['sessionsSynced'],
                $result['imagesSynced']
            ),
            'uploadedCount' => $result['eventsSynced'],
            'sessionCount' => $result['sessionsSynced'],
            'imageCount' => $result['imagesSynced'],
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
        ], 200);
    } catch (Throwable $e) {
        sendJson([
            'status' => 'error',
            'message' => 'An error occurred while uploading events.',
            'details' => $e->getMessage(),
        ], 500);
    }
}

// GET /api/events
if ($method === 'GET' && $path === '/api/events') {
    try {
        $events = getAllEvents();
        sendJson([
            'status' => 'success',
            'count' => count($events),
            'events' => $events,
        ], 200);
    } catch (Throwable $e) {
        sendJson([
            'status' => 'error',
            'message' => 'An error occurred while retrieving events.',
            'details' => $e->getMessage(),
        ], 500);
    }
}

// POST /api/events/{id}/images
if ($method === 'POST' && preg_match('#^/api/events/(\d+)/images$#', $path, $matches)) {
    $id = (int) $matches[1];
    $rawInput = file_get_contents(PHP_SAPI === 'cli' ? 'php://stdin' : 'php://input');
    $data = json_decode($rawInput ?: '', true);

    $imageUrl = isset($data['imageUrl']) && $data['imageUrl'] !== '' ? (string) $data['imageUrl'] : null;
    $imageData = isset($data['imageData']) && $data['imageData'] !== '' ? (string) $data['imageData'] : null;
    $caption = isset($data['caption']) && $data['caption'] !== '' ? (string) $data['caption'] : null;

    if ($imageUrl === null && $imageData === null) {
        sendJson([
            'status' => 'error',
            'message' => "Either 'imageUrl' or 'imageData' is required.",
        ], 422);
    }

    try {
        $imageId = addEventImage($id, $imageUrl, $imageData, $caption);
        sendJson([
            'status' => 'success',
            'message' => 'Image added successfully.',
            'imageId' => $imageId,
            'eventId' => $id,
        ], 201);
    } catch (Throwable $e) {
        sendJson([
            'status' => 'error',
            'message' => 'An error occurred while adding the image.',
            'details' => $e->getMessage(),
        ], 500);
    }
}

// DELETE /api/images/{id}
if ($method === 'DELETE' && preg_match('#^/api/images/(\d+)$#', $path, $matches)) {
    $imageId = (int) $matches[1];
    try {
        $deleted = deleteEventImage($imageId);
        if (!$deleted) {
            sendJson([
                'status' => 'error',
                'message' => "Image with ID {$imageId} not found.",
            ], 404);
        }

        sendJson([
            'status' => 'success',
            'message' => "Image with ID {$imageId} deleted successfully.",
        ], 200);
    } catch (Throwable $e) {
        sendJson([
            'status' => 'error',
            'message' => 'An error occurred while deleting the image.',
            'details' => $e->getMessage(),
        ], 500);
    }
}

// GET /api/events/{id}
if ($method === 'GET' && preg_match('#^/api/events/(\d+)$#', $path, $matches)) {
    $id = (int) $matches[1];
    if ($id <= 0) {
        sendJson([
            'status' => 'error',
            'message' => 'Invalid event ID.',
        ], 400);
    }

    try {
        $event = getEventById($id);
        if ($event === null) {
            sendJson([
                'status' => 'error',
                'message' => "Event with ID {$id} not found.",
            ], 404);
        }

        sendJson([
            'status' => 'success',
            'event' => $event,
        ], 200);
    } catch (Throwable $e) {
        sendJson([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the event.',
            'details' => $e->getMessage(),
        ], 500);
    }
}

// DELETE /api/events/{id}
if ($method === 'DELETE' && preg_match('#^/api/events/(\d+)$#', $path, $matches)) {
    $id = (int) $matches[1];
    if ($id <= 0) {
        sendJson([
            'status' => 'error',
            'message' => 'Invalid event ID.',
        ], 400);
    }

    try {
        $deleted = deleteEventById($id);
        if (!$deleted) {
            sendJson([
                'status' => 'error',
                'message' => "Event with ID {$id} not found.",
            ], 404);
        }

        sendJson([
            'status' => 'success',
            'message' => "Event with ID {$id} deleted successfully.",
        ], 200);
    } catch (Throwable $e) {
        sendJson([
            'status' => 'error',
            'message' => 'An error occurred while deleting the event.',
            'details' => $e->getMessage(),
        ], 500);
    }
}

// Fallback: 404
sendJson([
    'status' => 'error',
    'message' => 'Endpoint not found.',
], 404);
