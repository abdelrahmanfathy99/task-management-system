<?php

namespace App\Services\Api\V1;

use App\DTOs\Api\V1\ProjectResultDTO;
use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Support\Collection;

final class ListProjectsService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * @return Collection<int, ProjectResultDTO>
     */
    public function execute(int $userId): Collection
    {
        return $this->projectRepository
            ->listForUser($userId)
            ->map(fn (Project $project) => $this->toResultDTO($project));
    }

    private function toResultDTO(Project $project): ProjectResultDTO
    {
        return new ProjectResultDTO(
            id: (int) $project->id,
            name: $project->name,
            description: $project->description,
            status: $project->status->value,
            createdAt: $project->created_at->toDateTimeString(),
            updatedAt: $project->updated_at->toDateTimeString()
        );
    }
}
