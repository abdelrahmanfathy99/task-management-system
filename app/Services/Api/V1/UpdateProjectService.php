<?php

namespace App\Services\Api\V1;

use App\DTOs\Api\V1\ProjectResultDTO;
use App\DTOs\Api\V1\UpdateProjectDTO;
use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UpdateProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(UpdateProjectDTO $dto): ProjectResultDTO
    {
        $project = $this->projectRepository->findByIdForUser($dto->projectId, $dto->userId);

        if (! $project) {
            throw new NotFoundHttpException('Project not found.', code: 404);
        }

        $project = $this->projectRepository->save([
            'name' => $dto->name ?? $project->name,
            'description' => $dto->description,
            'status' => $dto->status ?? $project->status,
        ], $project);

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
