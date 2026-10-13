<?php

declare(strict_types=1);

use Slim\App;

return function (App $app): void {
    (require __DIR__ . '/Routes/api.php')($app);
    (require __DIR__ . '/Routes/HealthRoute.php')($app);
};