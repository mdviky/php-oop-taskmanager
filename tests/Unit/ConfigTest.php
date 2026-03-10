<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    public function testItLoadsConfigAndReadsValues(): void
    {
        putenv('DB_NAME=config_test_db');
        $_ENV['DB_NAME'] = 'config_test_db';

        Config::load(__DIR__ . '/../../config/db.php', 'db_test');

        $this->assertSame('config_test_db', Config::get('db_test.database'));
        $this->assertSame('utf8mb4', Config::get('db_test.charset'));
        $this->assertSame('fallback', Config::get('db_test.missing', 'fallback'));
    }
}
