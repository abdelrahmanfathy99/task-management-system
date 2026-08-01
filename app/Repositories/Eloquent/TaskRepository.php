<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Pagination\PaginatedResultDTO;
use App\DTOs\Pagination\PaginationParamsDTO;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Pagination\Contracts\PaginatorInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        private readonly PaginatorInterface $paginator
    ) {}

    public function findByIdForProject(int $id, int $projectId): ?Task
    {
        return Task::query()
            ->where('id', $id)
            ->where('project_id', $projectId)
            ->first();
    }

    public function listForProject(
        int $projectId,
        PaginationParamsDTO $pagination,
        ?string $search = null,
        ?TaskStatus $status = null,
        ?TaskPriority $priority = null
    ): PaginatedResultDTO {
        $query = Task::query()
            ->where('project_id', $projectId)
            ->when($search, function ($query, string $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($priority, fn ($query) => $query->where('priority', $priority))
            ->orderByDesc('id');

        return $this->paginator->paginate($query, $pagination);
    }

    public function save(array $data, ?Task $task = null): Task
    {
        if ($task) {
            $task->update($data);

            return $task;
        }

        return Task::query()->create($data);
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
