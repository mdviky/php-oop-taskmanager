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

$seedFile = __DIR__ . '/seeds/tasks.sql';
$sql = file_get_contents($seedFile);
if ($sql === false) {
    throw new RuntimeException('Failed to read seed file.');
}

$pdo->exec($sql);

echo "Seeded: tasks.sql\n";
