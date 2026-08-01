<?php

namespace App\DTOs\Pagination;

final readonly class PaginationParamsDTO
{
    public function __construct(
        public ?string $cursor = null,
        public int $perPage = 15
    ) {}
}
