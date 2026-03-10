<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Core\Config;
use App\Core\Database;
use App\Core\Env;
use PDO;
use PHPUnit\Framework\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    protected PDO $pdo;
    private static bool $booted = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pdo = self::getConnection();
        $this->ensureSchema();
        $this->pdo->exec('DELETE FROM tasks');
    }

    private static function getConnection(): PDO
    {
        if (!self::$booted) {
            Env::load(__DIR__ . '/../../.env');

            $testDb = getenv('DB_TEST_NAME') ?: getenv('DB_NAME');
            if ($testDb) {
                putenv('DB_NAME=' . $testDb);
                $_ENV['DB_NAME'] = $testDb;
            }

            Config::load(__DIR__ . '/../../config/db.php', 'db');
            self::$booted = true;
        }

        $db = new Database(Config::get('db'));
        return $db->getConnection();
    }

    private function ensureSchema(): void
    {
        $migration = __DIR__ . '/../../database/migrations/001_create_tasks_table.sql';
        $sql = file_get_contents($migration);
        if ($sql === false) {
            throw new \RuntimeException('Failed to read migration for tests.');
        }

        $this->pdo->exec($sql);
    }
}
