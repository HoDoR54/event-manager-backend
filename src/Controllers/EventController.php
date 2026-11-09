<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Controllers;

use HponeTuakNyi\EventManagerBackend\DTOs\UploadPayloadDto;
use HponeTuakNyi\EventManagerBackend\Services\EventService;
use HponeTuakNyi\EventManagerBackend\Support\Logger;
use HponeTuakNyi\EventManagerBackend\Validators\UploadPayloadValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Throwable;

final class EventController
{
    public function __construct(
        private readonly EventService $eventService,
        private readonly UploadPayloadValidator $validator = new UploadPayloadValidator()
    ) {
    }

    public function upload(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $rawBody = (string) $request->getBody();
        $data = json_decode($rawBody, true);

        if (!is_array($data)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Malformed JSON body.',
            ], 400);
        }

        $validation = $this->validator->validate($data);
        if (!$validation['isValid']) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validation['errors'],
            ], 422);
        }

        try {
            $payload = UploadPayloadDto::fromArray($data);
            $result = $this->eventService->upload($payload);
            return $this->jsonResponse($result, 200);
        } catch (Throwable $e) {
            // Logger::error('Event upload failed', $this->exceptionContext($e));
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'An error occurred while uploading events.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $events = $this->eventService->listEvents();
            return $this->jsonResponse([
                'status' => 'success',
                'count' => count($events),
                'events' => $events,
            ], 200);
        } catch (Throwable $e) {
            // Logger::error('Listing events failed', $this->exceptionContext($e));
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'An error occurred while retrieving events.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param array<string, string> $args
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = isset($args['id']) ? (int) $args['id'] : 0;
        if ($id <= 0) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid event ID.',
            ], 400);
        }

        try {
            $event = $this->eventService->getEvent($id);
            if ($event === null) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => "Event with ID {$id} not found.",
                ], 404);
            }

            return $this->jsonResponse([
                'status' => 'success',
                'event' => $event,
            ], 200);
        } catch (Throwable $e) {
                // Logger::error('Retrieving event failed', $this->exceptionContext($e) + ['id' => $id]);
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the event.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param array<string, string> $args
     */
    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = isset($args['id']) ? (int) $args['id'] : 0;
        if ($id <= 0) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid event ID.',
            ], 400);
        }

        try {
            $deleted = $this->eventService->deleteEvent($id);
            if (!$deleted) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => "Event with ID {$id} not found.",
                ], 404);
            }

            return $this->jsonResponse([
                'status' => 'success',
                'message' => "Event with ID {$id} deleted successfully.",
            ], 200);
        } catch (Throwable $e) {
            // Logger::error('Deleting event failed', $this->exceptionContext($e) + ['id' => $id]);
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'An error occurred while deleting the event.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function exceptionContext(Throwable $e): array
    {
        return [
            'exception' => $e::class,
            'message' => $e->getMessage(),
            'file' => $e->getFile() . ':' . $e->getLine(),
            'previous' => $e->getPrevious()?->getMessage(),
            'trace' => $e->getTraceAsString(),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function jsonResponse(array $data, int $status): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
