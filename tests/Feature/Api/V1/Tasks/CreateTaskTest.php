<?php

namespace Tests\Feature\Api\V1\Tasks;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class CreateTaskTest extends TaskTestCase
{
    #[Test]
    public function it_creates_a_task_successfully(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $payload = $this->validTaskPayload();

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson($this->tasksUrl($project->id), $payload);

        // Assert
        $response
            ->assertCreated()
            ->assertJsonPath('message', 'Task created successfully')
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
            ->assertJsonPath('data.project_id', $project->id)
            ->assertJsonPath('data.title', $payload['title'])
            ->assertJsonPath('data.description', $payload['description'])
            ->assertJsonPath('data.priority', $payload['priority'])
            ->assertJsonPath('data.status', $payload['status'])
            ->assertJsonPath('data.due_date', $payload['due_date']);

        $this->assertDatabaseHas('tasks', [
            'id' => $response->json('data.id'),
            'project_id' => $project->id,
            'title' => $payload['title'],
            'description' => $payload['description'],
            'priority' => $payload['priority'],
            'status' => $payload['status'],
            'due_date' => $payload['due_date'],
        ]);
        $this->assertDatabaseCount('tasks', 1);
    }

    #[Test]
    public function it_creates_a_task_with_default_priority_and_status_when_omitted(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $payload = $this->validTaskPayload();
        unset($payload['priority'], $payload['status']);

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson($this->tasksUrl($project->id), $payload);

        // Assert
        $response
            ->assertCreated()
            ->assertJsonPath('data.priority', TaskPriority::Medium->value)
            ->assertJsonPath('data.status', TaskStatus::Todo->value);

        $this->assertDatabaseHas('tasks', [
            'id' => $response->json('data.id'),
            'project_id' => $project->id,
            'priority' => TaskPriority::Medium->value,
            'status' => TaskStatus::Todo->value,
        ]);
    }

    #[Test]
    public function it_creates_a_task_without_description_and_due_date(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $payload = $this->validTaskPayload([
            'description' => null,
            'due_date' => null,
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson($this->tasksUrl($project->id), $payload);

        // Assert
        $response
            ->assertCreated()
            ->assertJsonPath('data.description', null)
            ->assertJsonPath('data.due_date', null);

        $this->assertDatabaseHas('tasks', [
            'id' => $response->json('data.id'),
            'project_id' => $project->id,
            'description' => null,
            'due_date' => null,
        ]);
    }

    #[Test]
    public function it_fails_to_create_a_task_with_missing_title(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $payload = [
            'description' => 'Missing title',
        ];

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson($this->tasksUrl($project->id), $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);

        $this->assertDatabaseCount('tasks', 0);
    }

    #[Test]
    public function it_fails_to_create_a_task_with_invalid_priority_or_status(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $payload = $this->validTaskPayload([
            'priority' => 'urgent',
            'status' => 'blocked',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson($this->tasksUrl($project->id), $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['priority', 'status']);

        $this->assertDatabaseCount('tasks', 0);
    }

    #[Test]
    public function it_fails_to_create_a_task_for_another_users_project(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $otherUser = User::factory()->create();
        $project = Project::factory()->for($otherUser)->create();
        $payload = $this->validTaskPayload();

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson($this->tasksUrl($project->id), $payload);

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonPath('error.message', 'Project not found.');

        $this->assertDatabaseCount('tasks', 0);
    }

    #[Test]
    public function it_fails_to_create_a_task_when_unauthenticated(): void
    {
        // Arrange
        $project = Project::factory()->create();
        $payload = $this->validTaskPayload();

        // Act
        $response = $this->postJson($this->tasksUrl($project->id), $payload);

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $this->assertSame(0, Task::query()->count());
    }
}
