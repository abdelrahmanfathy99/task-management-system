<?php

namespace App\DTOs\Api\V1;

final readonly class ProjectResultDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public string $status,
        public string $createdAt,
        public string $updatedAt
    ) {}
}
