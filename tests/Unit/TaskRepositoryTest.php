<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Repositories\TaskRepository;
use Tests\Support\DatabaseTestCase;

final class TaskRepositoryTest extends DatabaseTestCase
{
    public function testItCreatesAndFindsTask(): void
    {
        $repo = new TaskRepository($this->pdo);

        $id = $repo->create([
            'title' => 'Test Task',
            'description' => 'Test Description',
            'is_completed' => false,
        ]);

        $task = $repo->find($id);

        $this->assertNotNull($task);
        $this->assertSame('Test Task', $task->title);
    }

    public function testItUpdatesAndDeletesTask(): void
    {
        $repo = new TaskRepository($this->pdo);

        $id = $repo->create([
            'title' => 'Original',
            'description' => null,
            'is_completed' => false,
        ]);

        $repo->update($id, [
            'title' => 'Updated',
            'description' => 'Updated Desc',
            'is_completed' => true,
        ]);

        $task = $repo->find($id);
        $this->assertNotNull($task);
        $this->assertSame('Updated', $task->title);
        $this->assertSame('Updated Desc', $task->description);
        $this->assertTrue($task->isCompleted);

        $repo->delete($id);
        $this->assertNull($repo->find($id));
    }

    public function testItReturnsAllTasks(): void
    {
        $repo = new TaskRepository($this->pdo);

        $repo->create([
            'title' => 'First',
            'description' => null,
            'is_completed' => false,
        ]);
        $repo->create([
            'title' => 'Second',
            'description' => null,
            'is_completed' => false,
        ]);

        $tasks = $repo->all();
        $this->assertCount(2, $tasks);
        $titles = array_map(static fn ($task) => $task->title, $tasks);
        sort($titles);
        $this->assertSame(['First', 'Second'], $titles);
    }
}
