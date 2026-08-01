<?php

namespace App\DTOs\Api\V1;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;

class CreateTaskDTO
{
    public function __construct(
        public int $projectId,
        public int $userId,
        public string $title,
        public ?string $description,
        public TaskPriority $priority,
        public TaskStatus $status,
        public ?string $dueDate
    ) {}
}
