<?php

namespace Tests\Feature\Api\V1\Tasks;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class UpdateTaskTest extends TaskTestCase
{
    #[Test]
    public function it_updates_a_task_successfully(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $task = Task::factory()->for($project)->create([
            'title' => 'Old Title',
            'description' => 'Old description',
            'priority' => TaskPriority::Low,
            'status' => TaskStatus::Todo,
            'due_date' => '2026-08-10',
        ]);

        $payload = [
            'title' => 'New Title',
            'description' => 'New description',
            'priority' => TaskPriority::High->value,
            'status' => TaskStatus::Done->value,
            'due_date' => '2026-09-01',
        ];

        // Act
        $response = $this
            ->actingAs($user)
            ->putJson($this->taskUrl($project->id, $task->id), $payload);

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('message', 'Task updated successfully')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'project_id',
                    'title',
                    'description',
                    'priority',
                    'status',
                    'due_date',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJsonPath('data.id', $task->id)
            ->assertJsonPath('data.title', $payload['title'])
            ->assertJsonPath('data.description', $payload['description'])
            ->assertJsonPath('data.priority', $payload['priority'])
            ->assertJsonPath('data.status', $payload['status'])
            ->assertJsonPath('data.due_date', $payload['due_date']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'project_id' => $project->id,
            'title' => $payload['title'],
            'description' => $payload['description'],
            'priority' => $payload['priority'],
            'status' => $payload['status'],
            'due_date' => $payload['due_date'],
        ]);
    }

    #[Test]
    public function it_updates_only_the_provided_title(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $task = Task::factory()->for($project)->create([
            'title' => 'Old Title',
            'priority' => TaskPriority::Medium,
            'status' => TaskStatus::Todo,
        ]);

        $payload = [
            'title' => 'Updated Title Only',
        ];

        // Act
        $response = $this
            ->actingAs($user)
            ->putJson($this->taskUrl($project->id, $task->id), $payload);

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('data.title', $payload['title'])
            ->assertJsonPath('data.priority', TaskPriority::Medium->value)
            ->assertJsonPath('data.status', TaskStatus::Todo->value);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $payload['title'],
            'priority' => TaskPriority::Medium->value,
            'status' => TaskStatus::Todo->value,
        ]);
    }

    #[Test]
    public function it_fails_to_update_a_task_with_invalid_priority_or_status(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $task = Task::factory()->for($project)->create();

        $payload = [
            'priority' => 'urgent',
            'status' => 'blocked',
        ];

        // Act
        $response = $this
            ->actingAs($user)
            ->putJson($this->taskUrl($project->id, $task->id), $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['priority', 'status']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'priority' => TaskPriority::Medium->value,
            'status' => TaskStatus::Todo->value,
        ]);
    }

    #[Test]
    public function it_fails_to_update_a_task_that_does_not_exist(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $payload = $this->validTaskPayload([
            'title' => 'Does Not Matter',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->putJson($this->taskUrl($project->id, 99999), $payload);

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonPath('error.message', 'Task not found.');
    }

    #[Test]
    public function it_fails_to_update_a_task_for_another_users_project(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $otherUser = User::factory()->create();
        $project = Project::factory()->for($otherUser)->create();
        $task = Task::factory()->for($project)->create([
            'title' => 'Owned by other',
        ]);

        $payload = [
            'title' => 'Hijacked Title',
        ];

        // Act
        $response = $this
            ->actingAs($user)
            ->putJson($this->taskUrl($project->id, $task->id), $payload);

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonPath('error.message', 'Project not found.');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Owned by other',
            'project_id' => $project->id,
        ]);
    }

    #[Test]
    public function it_fails_to_update_a_task_when_unauthenticated(): void
    {
        // Arrange
        $project = Project::factory()->create();
        $task = Task::factory()->for($project)->create([
            'title' => 'Original Title',
        ]);

        // Act
        $response = $this->putJson($this->taskUrl($project->id, $task->id), [
            'title' => 'Changed Title',
        ]);

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Original Title',
        ]);
    }
}
