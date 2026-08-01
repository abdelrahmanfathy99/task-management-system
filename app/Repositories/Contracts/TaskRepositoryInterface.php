<?php

namespace App\Repositories\Contracts;

use App\DTOs\Api\V1\TaskDashboardStatsDTO;
use App\DTOs\Pagination\PaginatedResultDTO;
use App\DTOs\Pagination\PaginationParamsDTO;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;

interface TaskRepositoryInterface
{
    public function findByIdForProject(int $id, int $projectId): ?Task;

    /**
     * @return PaginatedResultDTO<Task>
     */
    public function listForProject(
        int $projectId,
        PaginationParamsDTO $pagination,
        ?string $search = null,
        ?TaskStatus $status = null,
        ?TaskPriority $priority = null
    ): PaginatedResultDTO;

    public function dashboardStatsForUser(int $userId): TaskDashboardStatsDTO;

    public function save(array $data, ?Task $task = null): Task;

    public function delete(Task $task): void;
}
