<?php

namespace App\Services\Api\V1;

use App\DTOs\Api\V1\CreateProjectDTO;
use App\DTOs\Api\V1\ProjectResultDTO;
use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;

final class CreateProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(CreateProjectDTO $dto): ProjectResultDTO
    {
        $project = $this->projectRepository->save([
            'user_id' => $dto->userId,
            'name' => $dto->name,
            'description' => $dto->description,
            'status' => $dto->status,
        ]);

        return $this->toResultDTO($project);
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
