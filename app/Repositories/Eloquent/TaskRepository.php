<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Api\V1\TaskDashboardStatsDTO;
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

    public function dashboardStatsForUser(int $userId): TaskDashboardStatsDTO
    {
        $today = now()->toDateString();
        $done = TaskStatus::Done->value;
        $todo = TaskStatus::Todo->value;
        $inProgress = TaskStatus::InProgress->value;

        $stats = Task::query()
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->where('projects.user_id', $userId)
            ->whereNull('projects.deleted_at')
            ->toBase()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN tasks.status = ? THEN 1 ELSE 0 END), 0) as completed',
                [$done]
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN tasks.status IN (?, ?) THEN 1 ELSE 0 END), 0) as pending',
                [$todo, $inProgress]
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN tasks.due_date IS NOT NULL AND tasks.due_date < ? AND tasks.status != ? THEN 1 ELSE 0 END), 0) as overdue',
                [$today, $done]
            )
            ->first();

        return new TaskDashboardStatsDTO(
            total: (int) ($stats->total ?? 0),
            completed: (int) ($stats->completed ?? 0),
            pending: (int) ($stats->pending ?? 0),
            overdue: (int) ($stats->overdue ?? 0)
        );
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
