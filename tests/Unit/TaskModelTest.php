<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Task;
use PHPUnit\Framework\TestCase;

final class TaskModelTest extends TestCase
{
    public function testFromArrayMapsFields(): void
    {
        $task = Task::fromArray([
            'id' => '5',
            'title' => 'Title',
            'description' => 'Desc',
            'is_completed' => 1,
            'created_at' => '2024-01-01 10:00:00',
            'updated_at' => '2024-01-02 10:00:00',
        ]);

        $this->assertSame(5, $task->id);
        $this->assertSame('Title', $task->title);
        $this->assertSame('Desc', $task->description);
        $this->assertTrue($task->isCompleted);
        $this->assertSame('2024-01-01 10:00:00', $task->createdAt);
    }
}
