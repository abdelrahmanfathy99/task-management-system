<?php

namespace Tests\Feature\Api\V1\Tasks;

use App\Enums\TaskStatus;
use App\Jobs\SendTaskOverdueNotificationJob;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskOverdueNotification;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;

class TaskOverdueNotificationTest extends TaskTestCase
{
    #[Test]
    public function it_dispatches_a_delayed_overdue_job_when_a_task_is_created_with_a_due_date(): void
    {
        // Arrange
        Queue::fake();
        Carbon::setTestNow('2026-08-01 10:00:00');

        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $payload = $this->validTaskPayload([
            'due_date' => '2026-08-10',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson($this->tasksUrl($project->id), $payload);

        // Assert
        $response->assertCreated();

        Queue::assertPushed(SendTaskOverdueNotificationJob::class, function (SendTaskOverdueNotificationJob $job) use ($response) {
            return $job->taskId === $response->json('data.id')
                && $job->expectedDueDate === '2026-08-10'
                && $job->delay?->equalTo(Carbon::parse('2026-08-11 00:00:00'));
        });
    }

    #[Test]
    public function it_does_not_dispatch_an_overdue_job_when_a_task_is_created_without_a_due_date(): void
    {
        // Arrange
        Queue::fake();

        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $payload = $this->validTaskPayload([
            'due_date' => null,
        ]);

        // Act
        $this
            ->actingAs($user)
            ->postJson($this->tasksUrl($project->id), $payload)
            ->assertCreated();

        // Assert
        Queue::assertNotPushed(SendTaskOverdueNotificationJob::class);
    }

    #[Test]
    public function it_reschedules_the_overdue_job_when_due_date_is_updated(): void
    {
        // Arrange
        Queue::fake();
        Carbon::setTestNow('2026-08-01 10:00:00');

        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $task = Task::factory()->for($project)->create([
            'due_date' => '2026-08-10',
            'overdue_notified_at' => now(),
            'status' => TaskStatus::Todo,
        ]);

        // Act
        $this
            ->actingAs($user)
            ->putJson($this->taskUrl($project->id, $task->id), [
                'due_date' => '2026-08-20',
            ])
            ->assertOk();

        // Assert
        $task->refresh();
        $this->assertNull($task->overdue_notified_at);
        $this->assertSame('2026-08-20', $task->due_date->toDateString());

        Queue::assertPushed(SendTaskOverdueNotificationJob::class, function (SendTaskOverdueNotificationJob $job) use ($task) {
            return $job->taskId === $task->id
                && $job->expectedDueDate === '2026-08-20'
                && $job->delay?->equalTo(Carbon::parse('2026-08-21 00:00:00'));
        });
    }

    #[Test]
    public function it_does_not_reschedule_when_due_date_is_not_provided_on_update(): void
    {
        // Arrange
        Queue::fake();

        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $task = Task::factory()->for($project)->create([
            'title' => 'Keep Due Date',
            'due_date' => '2026-08-15',
            'description' => 'Original description',
        ]);

        // Act
        $this
            ->actingAs($user)
            ->putJson($this->taskUrl($project->id, $task->id), [
                'title' => 'Updated Title Only',
            ])
            ->assertOk()
            ->assertJsonPath('data.due_date', '2026-08-15')
            ->assertJsonPath('data.description', 'Original description');

        // Assert
        Queue::assertNotPushed(SendTaskOverdueNotificationJob::class);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title Only',
            'due_date' => '2026-08-15',
            'description' => 'Original description',
        ]);
    }

    #[Test]
    public function it_clears_due_date_and_does_not_dispatch_when_due_date_is_set_to_null(): void
    {
        // Arrange
        Queue::fake();

        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $task = Task::factory()->for($project)->create([
            'due_date' => '2026-08-15',
            'overdue_notified_at' => now(),
        ]);

        // Act
        $this
            ->actingAs($user)
            ->putJson($this->taskUrl($project->id, $task->id), [
                'due_date' => null,
            ])
            ->assertOk()
            ->assertJsonPath('data.due_date', null);

        // Assert
        $task->refresh();
        $this->assertNull($task->due_date);
        $this->assertNull($task->overdue_notified_at);
        Queue::assertNotPushed(SendTaskOverdueNotificationJob::class);
    }

    #[Test]
    public function it_sends_a_notification_when_the_overdue_job_runs(): void
    {
        // Arrange
        Notification::fake();
        Carbon::setTestNow('2026-08-11 09:00:00');

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->create([
            'title' => 'Overdue Task',
            'due_date' => '2026-08-10',
            'status' => TaskStatus::Todo,
            'overdue_notified_at' => null,
        ]);

        // Act
        (new SendTaskOverdueNotificationJob($task->id, '2026-08-10'))->handle(
            app(TaskRepositoryInterface::class)
        );

        // Assert
        Notification::assertSentTo($user, TaskOverdueNotification::class, function (TaskOverdueNotification $notification) use ($task) {
            return $notification->task->is($task);
        });

        $this->assertNotNull($task->fresh()->overdue_notified_at);
    }

    #[Test]
    public function it_skips_notification_when_due_date_was_changed_before_the_job_runs(): void
    {
        // Arrange
        Notification::fake();
        Carbon::setTestNow('2026-08-11 09:00:00');

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->create([
            'due_date' => '2026-08-20',
            'status' => TaskStatus::Todo,
        ]);

        // Act — job was scheduled for the old due date
        (new SendTaskOverdueNotificationJob($task->id, '2026-08-10'))->handle(
            app(TaskRepositoryInterface::class)
        );

        // Assert
        Notification::assertNothingSent();
        $this->assertNull($task->fresh()->overdue_notified_at);
    }

    #[Test]
    public function it_skips_notification_when_task_is_done(): void
    {
        // Arrange
        Notification::fake();
        Carbon::setTestNow('2026-08-11 09:00:00');

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->done()->create([
            'due_date' => '2026-08-10',
        ]);

        // Act
        (new SendTaskOverdueNotificationJob($task->id, '2026-08-10'))->handle(
            app(TaskRepositoryInterface::class)
        );

        // Assert
        Notification::assertNothingSent();
        $this->assertNull($task->fresh()->overdue_notified_at);
    }

    #[Test]
    public function it_skips_notification_when_already_notified_for_the_current_due_date(): void
    {
        // Arrange
        Notification::fake();
        Carbon::setTestNow('2026-08-11 09:00:00');

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->create([
            'due_date' => '2026-08-10',
            'status' => TaskStatus::Todo,
            'overdue_notified_at' => now()->subHour(),
        ]);

        // Act
        (new SendTaskOverdueNotificationJob($task->id, '2026-08-10'))->handle(
            app(TaskRepositoryInterface::class)
        );

        // Assert
        Notification::assertNothingSent();
    }

    #[Test]
    public function it_skips_notification_when_task_was_soft_deleted(): void
    {
        // Arrange
        Notification::fake();
        Carbon::setTestNow('2026-08-11 09:00:00');

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->create([
            'due_date' => '2026-08-10',
            'status' => TaskStatus::Todo,
        ]);
        $task->delete();

        // Act
        (new SendTaskOverdueNotificationJob($task->id, '2026-08-10'))->handle(
            app(TaskRepositoryInterface::class)
        );

        // Assert
        Notification::assertNothingSent();
    }

    #[Test]
    public function it_dispatches_immediately_when_created_with_an_already_past_due_date(): void
    {
        // Arrange
        Queue::fake();
        Carbon::setTestNow('2026-08-15 10:00:00');

        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson($this->tasksUrl($project->id), $this->validTaskPayload([
                'due_date' => '2026-08-10',
            ]));

        // Assert
        $response->assertCreated();

        Queue::assertPushed(SendTaskOverdueNotificationJob::class, function (SendTaskOverdueNotificationJob $job) {
            return $job->expectedDueDate === '2026-08-10'
                && $job->delay?->equalTo(Carbon::parse('2026-08-15 10:00:00'));
        });
    }
}
