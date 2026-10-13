<?php

declare(strict_types=1);

namespace HponeTuakNyi\EventManagerBackend\Services;

use HponeTuakNyi\EventManagerBackend\Config\Database;
use PDO;
use PDOException;
use RuntimeException;

final class HealthService
{
    public function checkDatabase(): bool
    {
        try {
            $pdo = Database::connect();
            $stmt = $pdo->query('SELECT 1');
            return $stmt !== false;
        } catch (PDOException | RuntimeException) {
            return false;
        }
    }
}