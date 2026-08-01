<?php

namespace App\DTOs\Api\V1;

use App\Enums\ProjectStatus;

class CreateProjectDTO
{
    public function __construct(
        public int $userId,
        public string $name,
        public ?string $description,
        public ProjectStatus $status
    ) {}
}
