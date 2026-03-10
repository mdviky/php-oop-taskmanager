<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\View;
use PHPUnit\Framework\TestCase;

final class ViewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        View::setBasePath(__DIR__ . '/../../app/Views');
    }

    public function testItRendersWithoutLayout(): void
    {
        $html = View::render('tasks/index', ['tasks' => []], '');

        $this->assertStringContainsString('<h2>Tasks</h2>', $html);
        $this->assertStringNotContainsString('Task Manager', $html);
    }

    public function testItRendersWithLayout(): void
    {
        $html = View::render('tasks/index', ['tasks' => []]);

        $this->assertStringContainsString('Task Manager', $html);
        $this->assertStringContainsString('<h2>Tasks</h2>', $html);
    }

    public function testItThrowsWhenViewMissing(): void
    {
        $this->expectException(\RuntimeException::class);
        View::render('missing/view');
    }
}
