<?php

namespace App\Services\Api\V1;

use App\DTOs\Api\V1\CreateTaskDTO;
use App\DTOs\Api\V1\TaskResultDTO;
use App\Models\Task;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\ScheduleTaskOverdueNotificationService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CreateTaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly ScheduleTaskOverdueNotificationService $scheduleOverdueNotification
    ) {}

    public function execute(CreateTaskDTO $dto): TaskResultDTO
    {
        $project = $this->projectRepository->findByIdForUser($dto->projectId, $dto->userId);

        if (! $project) {
            throw new NotFoundHttpException('Project not found.', code: 404);
        }

        $task = $this->taskRepository->save([
            'project_id' => $dto->projectId,
            'title' => $dto->title,
            'description' => $dto->description,
            'priority' => $dto->priority,
            'status' => $dto->status,
            'due_date' => $dto->dueDate,
        ]);

        $this->scheduleOverdueNotification->schedule($task);

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
