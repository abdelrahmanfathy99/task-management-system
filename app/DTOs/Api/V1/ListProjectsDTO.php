<?php

namespace App\DTOs\Api\V1;

use App\DTOs\Pagination\PaginationParamsDTO;
use App\Enums\ProjectStatus;

class ListProjectsDTO
{
    public function __construct(
        public int $userId,
        public PaginationParamsDTO $pagination,
        public ?string $search = null,
        public ?ProjectStatus $status = null
    ) {}
}
