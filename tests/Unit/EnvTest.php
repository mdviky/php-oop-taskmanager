<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Env;
use PHPUnit\Framework\TestCase;

final class EnvTest extends TestCase
{
    public function testItLoadsEnvFile(): void
    {
        putenv('TEST_FOO');
        putenv('TEST_EMPTY');
        putenv('TEST_QUOTED');

        Env::load(__DIR__ . '/../fixtures/test.env');

        $this->assertSame('bar', getenv('TEST_FOO'));
        $this->assertSame('', getenv('TEST_EMPTY'));
        $this->assertSame('quoted value', getenv('TEST_QUOTED'));
    }
}
