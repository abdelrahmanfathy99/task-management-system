<?php

namespace App\Services\Api\V1;

use App\DTOs\Api\V1\ListProjectsDTO;
use App\DTOs\Api\V1\ProjectResultDTO;
use App\DTOs\Pagination\PaginatedResultDTO;
use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;

final class ListProjectsService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * @return PaginatedResultDTO<ProjectResultDTO>
     */
    public function execute(ListProjectsDTO $dto): PaginatedResultDTO
    {
        return $this->projectRepository
            ->listForUser($dto->userId, $dto->pagination, $dto->search, $dto->status)
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
