<?php

namespace App\DTOs\Api\V1;

final readonly class DashboardStatsDTO
{
    public function __construct(
        public int $totalProjects,
        public int $activeProjects,
        public int $totalTasks,
        public int $completedTasks,
        public int $pendingTasks,
        public int $overdueTasks
    ) {}
}
