<?php

namespace App\Services\Api\V1;

use App\DTOs\Api\V1\ListTasksDTO;
use App\DTOs\Api\V1\TaskResultDTO;
use App\DTOs\Pagination\PaginatedResultDTO;
use App\Models\Task;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ListTasksService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * @return PaginatedResultDTO<TaskResultDTO>
     */
    public function execute(ListTasksDTO $dto): PaginatedResultDTO
    {
        $project = $this->projectRepository->findByIdForUser($dto->projectId, $dto->userId);

        if (! $project) {
            throw new NotFoundHttpException('Project not found.', code: 404);
        }

        return $this->taskRepository->listForProject(
            $dto->projectId,
            $dto->pagination,
            $dto->search,
            $dto->status,
            $dto->priority
        )->map(fn(Task $task) => $this->toResultDTO($task));
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
