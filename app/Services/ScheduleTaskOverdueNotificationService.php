<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Jobs\SendTaskOverdueNotificationJob;
use App\Models\Task;
use Illuminate\Support\Carbon;

final class ScheduleTaskOverdueNotificationService
{
    public function schedule(Task $task): void
    {
        if ($task->due_date === null) {
            return;
        }

        if ($task->status === TaskStatus::Done) {
            return;
        }

        $availableAt = $this->availableAt($task->due_date);

        SendTaskOverdueNotificationJob::dispatch(
            $task->id,
            $task->due_date->toDateString()
        )->delay($availableAt);
    }

    private function availableAt(Carbon $dueDate): Carbon
    {
        $availableAt = $dueDate->copy()->addDay()->startOfDay();

        return $availableAt->greaterThan(now()) ? $availableAt : now();
    }
}
