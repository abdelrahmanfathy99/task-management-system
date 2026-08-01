<?php

namespace App\DTOs\Api\V1;

use App\Enums\ProjectStatus;

class UpdateProjectDTO
{
    public function __construct(
        public int $projectId,
        public int $userId,
        public ?string $name,
        public ?string $description,
        public ?string $status
    ) {}
}
