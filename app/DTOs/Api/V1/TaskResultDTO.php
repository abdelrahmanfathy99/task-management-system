<?php

namespace App\DTOs\Api\V1;

final readonly class TaskResultDTO
{
    public function __construct(
        public int $id,
        public int $projectId,
        public string $title,
        public ?string $description,
        public string $priority,
        public string $status,
        public ?string $dueDate,
        public string $createdAt,
        public string $updatedAt
    ) {}
}
