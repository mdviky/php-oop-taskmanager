<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Config;
use App\Core\Database;
use App\Core\Env;

Env::load(__DIR__ . '/../.env');
Config::load(__DIR__ . '/../config/db.php', 'db');

$db = new Database(Config::get('db'));
$pdo = $db->getConnection();

$migrationsDir = __DIR__ . '/migrations';
$files = glob($migrationsDir . '/*.sql');
if ($files === false) {
    throw new RuntimeException('Failed to read migrations directory.');
}

sort($files);

$pdo->exec('CREATE TABLE IF NOT EXISTS migrations (filename VARCHAR(255) PRIMARY KEY, applied_at DATETIME NOT NULL)');

foreach ($files as $file) {
    $filename = basename($file);

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM migrations WHERE filename = :filename');
    $stmt->execute(['filename' => $filename]);

    if ((int) $stmt->fetchColumn() > 0) {
        continue;
    }

    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("Failed to read migration: {$filename}");
    }

    $pdo->exec($sql);

    $insert = $pdo->prepare('INSERT INTO migrations (filename, applied_at) VALUES (:filename, NOW())');
    $insert->execute(['filename' => $filename]);

    echo "Applied: {$filename}\n";
}
