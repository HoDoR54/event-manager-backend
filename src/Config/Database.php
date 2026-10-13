<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Config;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    public static function connect(): PDO
    {
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '5432';
        $database = $_ENV['DB_DATABASE'] ?? '';
        $username = $_ENV['DB_USERNAME'] ?? '';
        $password = $_ENV['DB_PASSWORD'] ?? '';

        if ($database === '' || $username === '') {
            throw new RuntimeException('Database configuration is missing.');
        }

        $dsn = "pgsql:host={$host};port={$port};dbname={$database}";

        try {
            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Could not connect to the database.',
                previous: $exception
            );
        }
    }
}