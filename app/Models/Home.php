<?php

declare(strict_types=1);

namespace App\Models;

final class Home
{
    public function __construct(
        public ?int $id,
        public string $title,
        public ?string $description,
        public bool $isCompleted,
        public string $createdAt,
        public string $updatedAt
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            (string) ($data['title'] ?? ''),
            $data['description'] ?? null,
            (bool) ($data['is_completed'] ?? false),
            (string) ($data['created_at'] ?? ''),
            (string) ($data['updated_at'] ?? '')
        );
    }
}
