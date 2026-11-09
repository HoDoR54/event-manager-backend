<?php

declare(strict_types=1);

use HponeTuakNyi\EventManagerBackend\Config\Database;
use HponeTuakNyi\EventManagerBackend\Controllers\EventController;
use HponeTuakNyi\EventManagerBackend\Repositories\PostgresEventRepository;
use HponeTuakNyi\EventManagerBackend\Services\EventService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

return function (App $app): void {
    $getController = function (): EventController {
        $pdo = Database::connect();
        $repository = new PostgresEventRepository($pdo);
        $service = new EventService($repository);
        return new EventController($service);
    };

    $app->post('/api/events/upload', function (
        ServerRequestInterface $request,
        ResponseInterface $response
    ) use ($getController): ResponseInterface {
        return $getController()->upload($request, $response);
    });

    $app->get('/api/events', function (
        ServerRequestInterface $request,
        ResponseInterface $response
    ) use ($getController): ResponseInterface {
        return $getController()->list($request, $response);
    });

    $app->get('/api/events/{id}', function (
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ) use ($getController): ResponseInterface {
        return $getController()->get($request, $response, $args);
    });

    $app->delete('/api/events/{id}', function (
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ) use ($getController): ResponseInterface {
        return $getController()->delete($request, $response, $args);
    });
};
