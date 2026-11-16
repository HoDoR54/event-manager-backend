<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/db.php';

try {
    getDb();
    echo "Database connection successful.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage() . "\n");
    exit(1);
}
