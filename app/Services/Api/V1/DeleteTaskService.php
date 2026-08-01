<?php

namespace App\Services\Api\V1;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DeleteTaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(int $taskId, int $projectId, int $userId): void
    {
        $project = $this->projectRepository->findByIdForUser($projectId, $userId);

        if (! $project) {
            throw new NotFoundHttpException('Project not found.', code: 404);
        }

        $task = $this->taskRepository->findByIdForProject($taskId, $projectId);

        if (! $task) {
            throw new NotFoundHttpException('Task not found.', code: 404);
        }

        $this->taskRepository->delete($task);
    }
}
