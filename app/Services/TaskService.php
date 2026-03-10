<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\TaskRepository;

final class TaskService
{
    public function __construct(private TaskRepository $repository)
    {
    }

    public function listTasks(): array
    {
        return $this->repository->all();
    }

    public function getTask(int $id): ?array
    {
        $task = $this->repository->find($id);
        return $task ? get_object_vars($task) : null;
    }

    public function createTask(array $data): array
    {
        $errors = $this->validate($data);
        if ($errors) {
            return ['errors' => $errors];
        }

        $id = $this->repository->create([
            'title' => trim($data['title']),
            'description' => $data['description'] ?? null,
            'is_completed' => !empty($data['is_completed']),
        ]);

        return ['id' => $id];
    }

    public function updateTask(int $id, array $data): array
    {
        $errors = $this->validate($data);
        if ($errors) {
            return ['errors' => $errors];
        }

        $this->repository->update($id, [
            'title' => trim($data['title']),
            'description' => $data['description'] ?? null,
            'is_completed' => !empty($data['is_completed']),
        ]);

        return ['id' => $id];
    }

    public function deleteTask(int $id): void
    {
        $this->repository->delete($id);
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty(trim($data['title'] ?? ''))) {
            $errors['title'] = 'Title is required.';
        }

        return $errors;
    }
}
