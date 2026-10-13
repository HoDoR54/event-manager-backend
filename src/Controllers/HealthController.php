<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Controllers;

use HponeTuakNyi\EventManagerBackend\Services\HealthService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

final class HealthController
{
    public function __construct(private readonly HealthService $healthService)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $databaseUp = $this->healthService->checkDatabase();

        $status = $databaseUp ? 'ok' : 'error';
        $databaseStatus = $databaseUp ? 'up' : 'down';
        $httpStatus = $databaseUp ? 200 : 503;

        $payload = [
            'status' => $status,
            'database' => $databaseStatus,
        ];

        $response = new Response();
        $response->getBody()->write(json_encode($payload, JSON_THROW_ON_ERROR));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($httpStatus);
    }
}