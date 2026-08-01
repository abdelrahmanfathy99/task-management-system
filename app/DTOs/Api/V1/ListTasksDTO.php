<?php

namespace App\DTOs\Api\V1;

use App\DTOs\Pagination\PaginationParamsDTO;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;

class ListTasksDTO
{
    public function __construct(
        public int $projectId,
        public int $userId,
        public PaginationParamsDTO $pagination,
        public ?string $search = null,
        public ?TaskStatus $status = null,
        public ?TaskPriority $priority = null
    ) {}
}
