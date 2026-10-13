<?php

declare(strict_types=1);

use HponeTuakNyi\EventManagerBackend\Controllers\HealthController;
use Slim\App;

return function (App $app): void {
    $healthController = new HealthController(new \HponeTuakNyi\EventManagerBackend\Services\HealthService());
    $app->get('/api/health', $healthController);
};