<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Api\V1\ProjectDashboardStatsDTO;
use App\DTOs\Pagination\PaginatedResultDTO;
use App\DTOs\Pagination\PaginationParamsDTO;
use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Pagination\Contracts\PaginatorInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        private readonly PaginatorInterface $paginator
    ) {}

    public function findByIdForUser(int $id, int $userId): ?Project
    {
        return Project::query()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function listForUser(
        int $userId,
        PaginationParamsDTO $pagination,
        ?string $search = null,
        ?ProjectStatus $status = null
    ): PaginatedResultDTO {
        $query = Project::query()
            ->where('user_id', $userId)
            ->when($search, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByDesc('id');

        return $this->paginator->paginate($query, $pagination);
    }

    public function dashboardStatsForUser(int $userId): ProjectDashboardStatsDTO
    {
        $stats = Project::query()
            ->where('user_id', $userId)
            ->toBase()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) as active',
                [ProjectStatus::Active->value]
            )
            ->first();

        return new ProjectDashboardStatsDTO(
            total: (int) ($stats->total ?? 0),
            active: (int) ($stats->active ?? 0)
        );
    }

    public function save(array $data, ?Project $project = null): Project
    {
        if ($project) {
            $project->update($data);

            return $project;
        }

        return Project::query()->create($data);
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }
}
