<?php

namespace App\DTOs\Api\V1;

final readonly class ProjectDashboardStatsDTO
{
    public function __construct(
        public int $total,
        public int $active
    ) {}
}
