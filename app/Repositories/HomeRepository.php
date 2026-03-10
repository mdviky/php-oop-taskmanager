<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Home;
use DateTimeImmutable;
use PDO;

final class HomeRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM tasks ORDER BY created_at DESC');
        $rows = $stmt->fetchAll();

        return array_map(fn ($row) => Home::fromArray($row), $rows);
    }

    public function find(int $id): ?Home
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Home::fromArray($row) : null;
    }

    public function create(array $data): int
    {
        $now = (new DateTimeImmutable())->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare(
            'INSERT INTO tasks (title, description, is_completed, created_at, updated_at)
             VALUES (:title, :description, :is_completed, :created_at, :updated_at)'
        );

        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'is_completed' => (int) $data['is_completed'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $now = (new DateTimeImmutable())->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare(
            'UPDATE tasks
             SET title = :title, description = :description, is_completed = :is_completed, updated_at = :updated_at
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'],
            'is_completed' => (int) $data['is_completed'],
            'updated_at' => $now,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM tasks WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
