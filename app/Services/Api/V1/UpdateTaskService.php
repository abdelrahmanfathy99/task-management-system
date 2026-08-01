<?php

namespace App\Services\Api\V1;

use App\DTOs\Api\V1\TaskResultDTO;
use App\DTOs\Api\V1\UpdateTaskDTO;
use App\Models\Task;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UpdateTaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(UpdateTaskDTO $dto): TaskResultDTO
    {
        $project = $this->projectRepository->findByIdForUser($dto->projectId, $dto->userId);

        if (! $project) {
            throw new NotFoundHttpException('Project not found.', code: 404);
        }

        $task = $this->taskRepository->findByIdForProject($dto->taskId, $dto->projectId);

        if (! $task) {
            throw new NotFoundHttpException('Task not found.', code: 404);
        }

        $task = $this->taskRepository->save([
            'title' => $dto->title ?? $task->title,
            'description' => $dto->description,
            'priority' => $dto->priority ?? $task->priority,
            'status' => $dto->status ?? $task->status,
            'due_date' => $dto->dueDate,
        ], $task);

        return $this->toResultDTO($task);
    }

    private function toResultDTO(Task $task): TaskResultDTO
    {
        return new TaskResultDTO(
            id: (int) $task->id,
            projectId: (int) $task->project_id,
            title: $task->title,
            description: $task->description,
            priority: $task->priority->value,
            status: $task->status->value,
            dueDate: $task->due_date?->toDateString(),
            createdAt: $task->created_at->toDateTimeString(),
            updatedAt: $task->updated_at->toDateTimeString()
        );
    }
}
