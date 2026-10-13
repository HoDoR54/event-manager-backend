<?php

declare(strict_types=1);

use Slim\App;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

return function (App $app): void {
    $app->get('/', function (
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $response->getBody()->write('Event Manager API');

        return $response;
    });
};