<?php

namespace Tests\Feature\Api\V1\Projects;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class UpdateProjectTest extends ProjectTestCase
{
    #[Test]
    public function it_updates_a_project_successfully(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = Project::factory()->for($user)->create([
            'name' => 'Old Name',
            'description' => 'Old description',
            'status' => ProjectStatus::Active,
        ]);

        $payload = [
            'name' => 'New Name',
            'description' => 'New description',
            'status' => ProjectStatus::Completed->value,
        ];

        // Act
        $response = $this
            ->actingAs($user)
            ->putJson($this->projectUrl($project->id), $payload);

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('message', 'Project updated successfully')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJsonPath('data.id', $project->id)
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.description', $payload['description'])
            ->assertJsonPath('data.status', $payload['status']);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $user->id,
            'name' => $payload['name'],
            'description' => $payload['description'],
            'status' => $payload['status'],
        ]);
    }

    #[Test]
    public function it_updates_only_the_provided_name(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = Project::factory()->for($user)->create([
            'name' => 'Old Name',
            'description' => 'Keep or clear description',
            'status' => ProjectStatus::Active,
        ]);

        $payload = [
            'name' => 'Updated Name Only',
        ];

        // Act
        $response = $this
            ->actingAs($user)
            ->putJson($this->projectUrl($project->id), $payload);

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.status', ProjectStatus::Active->value);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => $payload['name'],
            'status' => ProjectStatus::Active->value,
        ]);
    }

    #[Test]
    public function it_fails_to_update_a_project_with_invalid_status(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = Project::factory()->for($user)->create();

        $payload = [
            'status' => 'not-valid',
        ];

        // Act
        $response = $this
            ->actingAs($user)
            ->putJson($this->projectUrl($project->id), $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status' => ProjectStatus::Active->value,
        ]);
    }

    #[Test]
    public function it_fails_to_update_a_project_that_does_not_exist(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $payload = $this->validProjectPayload([
            'name' => 'Does Not Matter',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->putJson($this->projectUrl(99999), $payload);

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonPath('error.message', 'Project not found.');
    }

    #[Test]
    public function it_fails_to_update_another_users_project(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $otherUser = User::factory()->create();
        $project = Project::factory()->for($otherUser)->create([
            'name' => 'Owned by other',
        ]);

        $payload = [
            'name' => 'Hijacked Name',
        ];

        // Act
        $response = $this
            ->actingAs($user)
            ->putJson($this->projectUrl($project->id), $payload);

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonPath('error.message', 'Project not found.');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Owned by other',
            'user_id' => $otherUser->id,
        ]);
    }

    #[Test]
    public function it_fails_to_update_a_project_when_unauthenticated(): void
    {
        // Arrange
        $project = Project::factory()->create([
            'name' => 'Original Name',
        ]);

        // Act
        $response = $this->putJson($this->projectUrl($project->id), [
            'name' => 'Changed Name',
        ]);

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Original Name',
        ]);
    }
}
