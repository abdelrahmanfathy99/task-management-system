<?php

namespace Tests\Feature\Api\V1\Projects;

use App\Models\Project;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class DeleteProjectTest extends ProjectTestCase
{
    #[Test]
    public function it_deletes_a_project_successfully(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = Project::factory()->for($user)->create([
            'name' => 'Project To Delete',
        ]);

        $this->assertDatabaseCount('projects', 1);

        // Act
        $response = $this
            ->actingAs($user)
            ->deleteJson($this->projectUrl($project->id));

        // Assert
        $response
            ->assertOk()
            ->assertExactJson([
                'message' => 'Project deleted successfully',
            ]);

        $this->assertSoftDeleted('projects', [
            'id' => $project->id,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_fails_to_delete_a_project_that_does_not_exist(): void
    {
        // Arrange
        $user = $this->authenticatedUser();

        // Act
        $response = $this
            ->actingAs($user)
            ->deleteJson($this->projectUrl(99999));

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonStructure([
                'error' => [
                    'message',
                    'code',
                ],
            ])
            ->assertJsonPath('error.message', 'Project not found.');
    }

    #[Test]
    public function it_fails_to_delete_another_users_project(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $otherUser = User::factory()->create();
        $project = Project::factory()->for($otherUser)->create();

        // Act
        $response = $this
            ->actingAs($user)
            ->deleteJson($this->projectUrl($project->id));

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonPath('error.message', 'Project not found.');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $otherUser->id,
            'deleted_at' => null,
        ]);
    }

    #[Test]
    public function it_fails_to_delete_a_project_when_unauthenticated(): void
    {
        // Arrange
        $project = Project::factory()->create();

        // Act
        $response = $this->deleteJson($this->projectUrl($project->id));

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'deleted_at' => null,
        ]);
    }
}
