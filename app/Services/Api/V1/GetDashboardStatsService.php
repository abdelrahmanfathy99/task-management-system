<?php

namespace App\Services\Api\V1;

use App\DTOs\Api\V1\DashboardStatsDTO;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;

final class GetDashboardStatsService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function execute(int $userId): DashboardStatsDTO
    {
        $projectStats = $this->projectRepository->dashboardStatsForUser($userId);
        $taskStats = $this->taskRepository->dashboardStatsForUser($userId);

        return new DashboardStatsDTO(
            totalProjects: $projectStats->total,
            activeProjects: $projectStats->active,
            totalTasks: $taskStats->total,
            completedTasks: $taskStats->completed,
            pendingTasks: $taskStats->pending,
            overdueTasks: $taskStats->overdue
        );
    }
}
