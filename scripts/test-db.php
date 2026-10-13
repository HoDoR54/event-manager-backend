<?php

declare(strict_types=1);

use HponeTuakNyi\EventManagerBackend\Config\Database;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

try {
    Database::connect();
    echo "Database connection successful.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage() . "\n");
    exit(1);
}
