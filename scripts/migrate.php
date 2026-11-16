<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/db.php';

try {
    $pdo = getDb();
    echo "Connected to database.\n";

    $migrationsDir = __DIR__ . '/../database/migrations';
    $files = glob($migrationsDir . '/*.sql');
    sort($files);

    if (empty($files)) {
        echo "No migrations found in {$migrationsDir}.\n";
        exit(0);
    }

    foreach ($files as $file) {
        $filename = basename($file);
        echo "Applying migration: {$filename} ... ";
        $sql = file_get_contents($file);
        if ($sql === false || trim($sql) === '') {
            echo "Skipped (empty file).\n";
            continue;
        }

        $pdo->beginTransaction();
        $pdo->exec($sql);
        $pdo->commit();
        echo "Done.\n";
    }

    echo "All migrations executed successfully.\n";
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, "Migration failed: " . $e->getMessage() . "\n");
    exit(1);
}
