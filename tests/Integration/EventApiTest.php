<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Tests\Integration;

use HponeTuakNyi\EventManagerBackend\Controllers\EventController;
use HponeTuakNyi\EventManagerBackend\Repositories\EventRepositoryInterface;
use HponeTuakNyi\EventManagerBackend\Services\EventService;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;

final class EventApiTest extends TestCase
{
    public function testUploadValidPayloadReturns200WithSuccessResponse(): void
    {
        $repositoryMock = $this->createMock(EventRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('syncEvents')
            ->willReturn(['eventsSynced' => 1, 'sessionsSynced' => 1]);

        $controller = new EventController(new EventService($repositoryMock));

        $body = [
            'uploadTimestamp' => '2026-11-09T10:00:00Z',
            'appVersion' => '1.0',
            'eventCount' => 1,
            'events' => [
                [
                    'name' => 'Integration Conference',
                    'date' => '2026-12-15',
                    'location' => 'Main Hall',
                    'organiser' => 'Tech Corp',
                    'type' => 'CONFERENCE',
                    'status' => 'UPCOMING',
                    'sessions' => [
                        [
                            'sessionId' => 'INT-1',
                            'name' => 'Session 1',
                            'time' => '11:00',
                            'type' => 'KEYNOTE',
                        ],
                    ],
                ],
            ],
        ];

        $request = (new ServerRequestFactory())->createServerRequest('POST', '/api/events/upload');
        $request->getBody()->write((string) json_encode($body));
        $request->getBody()->rewind();

        $response = $controller->upload($request, new Response());

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));

        $responseData = json_decode((string) $response->getBody(), true);
        $this->assertSame('success', $responseData['status']);
        $this->assertSame(1, $responseData['uploadedCount']);
        $this->assertSame(1, $responseData['sessionCount']);
    }

    public function testUploadInvalidJsonReturns400(): void
    {
        $stub = $this->createStub(EventRepositoryInterface::class);
        $controller = new EventController(new EventService($stub));

        $request = (new ServerRequestFactory())->createServerRequest('POST', '/api/events/upload');
        $request->getBody()->write('not a json');
        $request->getBody()->rewind();

        $response = $controller->upload($request, new Response());

        $this->assertSame(400, $response->getStatusCode());
        $responseData = json_decode((string) $response->getBody(), true);
        $this->assertSame('error', $responseData['status']);
        $this->assertSame('Malformed JSON body.', $responseData['message']);
    }

    public function testUploadValidationErrorReturns422(): void
    {
        $stub = $this->createStub(EventRepositoryInterface::class);
        $controller = new EventController(new EventService($stub));

        $request = (new ServerRequestFactory())->createServerRequest('POST', '/api/events/upload');
        $request->getBody()->write((string) json_encode(['events' => [['name' => '']]]));
        $request->getBody()->rewind();

        $response = $controller->upload($request, new Response());

        $this->assertSame(422, $response->getStatusCode());
        $responseData = json_decode((string) $response->getBody(), true);
        $this->assertSame('error', $responseData['status']);
        $this->assertNotEmpty($responseData['errors']);
    }

    public function testListEventsReturns200(): void
    {
        $repositoryMock = $this->createMock(EventRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('getAllEventsWithSessions')
            ->willReturn([
                ['id' => 1, 'name' => 'Event 1', 'sessions' => []],
            ]);

        $controller = new EventController(new EventService($repositoryMock));

        $request = (new ServerRequestFactory())->createServerRequest('GET', '/api/events');
        $response = $controller->list($request, new Response());

        $this->assertSame(200, $response->getStatusCode());
        $responseData = json_decode((string) $response->getBody(), true);
        $this->assertSame('success', $responseData['status']);
        $this->assertSame(1, $responseData['count']);
        $this->assertCount(1, $responseData['events']);
    }
}
