<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Tests\Support\DatabaseTestCase;

final class TaskServiceTest extends DatabaseTestCase
{
    public function testItValidatesTitle(): void
    {
        $repo = new TaskRepository($this->pdo);
        $service = new TaskService($repo);

        $result = $service->createTask(['title' => '']);

        $this->assertArrayHasKey('errors', $result);
        $this->assertArrayHasKey('title', $result['errors']);
    }

    public function testItCreatesAndGetsTask(): void
    {
        $repo = new TaskRepository($this->pdo);
        $service = new TaskService($repo);

        $result = $service->createTask([
            'title' => 'My Task',
            'description' => 'Desc',
            'is_completed' => '1',
        ]);

        $this->assertArrayHasKey('id', $result);

        $task = $service->getTask($result['id']);
        $this->assertNotNull($task);
        $this->assertSame('My Task', $task['title']);
        $this->assertSame('Desc', $task['description']);
        $this->assertTrue($task['isCompleted']);
    }

    public function testItUpdatesAndDeletesTask(): void
    {
        $repo = new TaskRepository($this->pdo);
        $service = new TaskService($repo);

        $created = $service->createTask(['title' => 'Initial']);
        $id = $created['id'];

        $updated = $service->updateTask($id, [
            'title' => 'Updated',
            'description' => 'Updated Desc',
            'is_completed' => true,
        ]);

        $this->assertSame($id, $updated['id']);

        $task = $service->getTask($id);
        $this->assertSame('Updated', $task['title']);

        $service->deleteTask($id);
        $this->assertNull($service->getTask($id));
    }
}
