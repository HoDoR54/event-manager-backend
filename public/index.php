<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use HponeTuakNyi\EventManagerBackend\Middleware\RequestLoggingMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$app = AppFactory::create();

(require __DIR__ . '/../src/routes.php')($app);

$displayErrorDetails = ($_ENV['APP_ENV'] ?? 'production') !== 'production';
$app->addErrorMiddleware($displayErrorDetails, true, true);
$app->add(new RequestLoggingMiddleware());

$app->run();