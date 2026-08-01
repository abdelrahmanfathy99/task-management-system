<?php

namespace App\DTOs\Api\V1;

class UpdateTaskDTO
{
    public function __construct(
        public int $taskId,
        public int $projectId,
        public int $userId,
        public ?string $title,
        public ?string $description,
        public ?string $priority,
        public ?string $status,
        public ?string $dueDate
    ) {}
}
