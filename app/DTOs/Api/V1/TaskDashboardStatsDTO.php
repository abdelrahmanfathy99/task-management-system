<?php

namespace App\DTOs\Api\V1;

final readonly class TaskDashboardStatsDTO
{
    public function __construct(
        public int $total,
        public int $completed,
        public int $pending,
        public int $overdue
    ) {}
}
