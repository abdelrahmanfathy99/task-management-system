<?php

namespace Tests\Feature\Api\V1\Projects;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class ShowProjectTest extends ProjectTestCase
{
    #[Test]
    public function it_shows_a_project_successfully(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = Project::factory()->for($user)->create([
            'name' => 'Website Redesign',
            'description' => 'Rebuild the marketing site',
            'status' => ProjectStatus::Active,
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson($this->projectUrl($project->id));

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('message', 'Project retrieved successfully')
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
            ->assertJsonPath('data.name', $project->name)
            ->assertJsonPath('data.description', $project->description)
            ->assertJsonPath('data.status', ProjectStatus::Active->value);
    }

    #[Test]
    public function it_fails_to_show_a_project_that_does_not_exist(): void
    {
        // Arrange
        $user = $this->authenticatedUser();

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson($this->projectUrl(99999));

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
    public function it_fails_to_show_another_users_project(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $otherUser = User::factory()->create();
        $project = Project::factory()->for($otherUser)->create();

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson($this->projectUrl($project->id));

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonPath('error.message', 'Project not found.');
    }

    #[Test]
    public function it_fails_to_show_a_project_when_unauthenticated(): void
    {
        // Arrange
        $project = Project::factory()->create();

        // Act
        $response = $this->getJson($this->projectUrl($project->id));

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }
}
