<?php

namespace App\Repositories\Contracts;

use App\DTOs\Api\V1\ProjectDashboardStatsDTO;
use App\DTOs\Pagination\PaginatedResultDTO;
use App\DTOs\Pagination\PaginationParamsDTO;
use App\Enums\ProjectStatus;
use App\Models\Project;

interface ProjectRepositoryInterface
{
    public function findByIdForUser(int $id, int $userId): ?Project;

    /**
     * @return PaginatedResultDTO<Project>
     */
    public function listForUser(
        int $userId,
        PaginationParamsDTO $pagination,
        ?string $search = null,
        ?ProjectStatus $status = null
    ): PaginatedResultDTO;

    public function dashboardStatsForUser(int $userId): ProjectDashboardStatsDTO;

    public function save(array $data, ?Project $project = null): Project;

    public function delete(Project $project): void;
}
