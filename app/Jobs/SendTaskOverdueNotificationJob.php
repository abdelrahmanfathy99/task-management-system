<?php

namespace App\Jobs;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Notifications\TaskOverdueNotification;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTaskOverdueNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $taskId,
        public readonly string $expectedDueDate
    ) {}

    public function handle(TaskRepositoryInterface $taskRepository): void
    {
        $task = Task::query()
            ->with('project.user')
            ->find($this->taskId);

        if (! $this->shouldNotify($task)) {
            return;
        }

        $user = $task->project?->user;

        if (! $user) {
            return;
        }

        $user->notify(new TaskOverdueNotification($task));

        $taskRepository->save([
            'overdue_notified_at' => now(),
        ], $task);
    }

    private function shouldNotify(?Task $task): bool
    {
        if (! $task) {
            return false;
        }

        if ($task->status === TaskStatus::Done) {
            return false;
        }

        if ($task->due_date === null) {
            return false;
        }

        if ($task->due_date->toDateString() !== $this->expectedDueDate) {
            return false;
        }

        if ($task->overdue_notified_at !== null) {
            return false;
        }

        return $task->isOverdue();
    }
}
