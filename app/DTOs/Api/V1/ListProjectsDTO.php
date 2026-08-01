<?php

namespace App\DTOs\Api\V1;

use App\Enums\ProjectStatus;

class ListProjectsDTO
{
    public function __construct(
        public int $userId,
        public ?string $search = null,
        public ?ProjectStatus $status = null
    ) {}
}
