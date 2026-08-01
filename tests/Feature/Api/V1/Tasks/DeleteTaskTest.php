<?php

namespace Tests\Feature\Api\V1\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class DeleteTaskTest extends TaskTestCase
{
    #[Test]
    public function it_deletes_a_task_successfully(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $task = Task::factory()->for($project)->create([
            'title' => 'Task To Delete',
        ]);

        $this->assertDatabaseCount('tasks', 1);

        // Act
        $response = $this
            ->actingAs($user)
            ->deleteJson($this->taskUrl($project->id, $task->id));

        // Assert
        $response
            ->assertOk()
            ->assertExactJson([
                'message' => 'Task deleted successfully',
            ]);

        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
            'project_id' => $project->id,
        ]);
    }

    #[Test]
    public function it_fails_to_delete_a_task_that_does_not_exist(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);

        // Act
        $response = $this
            ->actingAs($user)
            ->deleteJson($this->taskUrl($project->id, 99999));

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonStructure([
                'error' => [
                    'message',
                    'code',
                ],
            ])
            ->assertJsonPath('error.message', 'Task not found.');
    }

    #[Test]
    public function it_fails_to_delete_a_task_for_another_users_project(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $otherUser = User::factory()->create();
        $project = Project::factory()->for($otherUser)->create();
        $task = Task::factory()->for($project)->create();

        // Act
        $response = $this
            ->actingAs($user)
            ->deleteJson($this->taskUrl($project->id, $task->id));

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonPath('error.message', 'Project not found.');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'project_id' => $project->id,
            'deleted_at' => null,
        ]);
    }

    #[Test]
    public function it_fails_to_delete_a_task_when_unauthenticated(): void
    {
        // Arrange
        $project = Project::factory()->create();
        $task = Task::factory()->for($project)->create();

        // Act
        $response = $this->deleteJson($this->taskUrl($project->id, $task->id));

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'deleted_at' => null,
        ]);
    }
}
