<?php

namespace App\Services\Api\V1;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DeleteProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(int $projectId, int $userId): void
    {
        $project = $this->projectRepository->findByIdForUser($projectId, $userId);

        if (! $project) {
            throw new NotFoundHttpException('Project not found.', code: 404);
        }

        $this->projectRepository->delete($project);
    }
}
