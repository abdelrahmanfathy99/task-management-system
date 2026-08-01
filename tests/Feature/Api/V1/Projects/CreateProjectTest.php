<?php

namespace Tests\Feature\Api\V1\Projects;

use App\Enums\ProjectStatus;
use App\Models\Project;
use PHPUnit\Framework\Attributes\Test;

class CreateProjectTest extends ProjectTestCase
{
    #[Test]
    public function it_creates_a_project_successfully(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $payload = $this->validProjectPayload();

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson(self::PROJECTS_URL, $payload);

        // Assert
        $response
            ->assertCreated()
            ->assertJsonPath('message', 'Project created successfully')
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
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.description', $payload['description'])
            ->assertJsonPath('data.status', $payload['status']);

        $this->assertDatabaseHas('projects', [
            'id' => $response->json('data.id'),
            'user_id' => $user->id,
            'name' => $payload['name'],
            'description' => $payload['description'],
            'status' => $payload['status'],
        ]);
        $this->assertDatabaseCount('projects', 1);
    }

    #[Test]
    public function it_creates_a_project_with_default_status_when_status_is_omitted(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $payload = $this->validProjectPayload();
        unset($payload['status']);

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson(self::PROJECTS_URL, $payload);

        // Assert
        $response
            ->assertCreated()
            ->assertJsonPath('data.status', ProjectStatus::Active->value);

        $this->assertDatabaseHas('projects', [
            'id' => $response->json('data.id'),
            'user_id' => $user->id,
            'status' => ProjectStatus::Active->value,
        ]);
    }

    #[Test]
    public function it_creates_a_project_without_description(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $payload = $this->validProjectPayload([
            'description' => null,
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson(self::PROJECTS_URL, $payload);

        // Assert
        $response
            ->assertCreated()
            ->assertJsonPath('data.description', null);

        $this->assertDatabaseHas('projects', [
            'id' => $response->json('data.id'),
            'user_id' => $user->id,
            'description' => null,
        ]);
    }

    #[Test]
    public function it_fails_to_create_a_project_with_missing_name(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $payload = [
            'description' => 'Missing name',
        ];

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson(self::PROJECTS_URL, $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);

        $this->assertDatabaseCount('projects', 0);
    }

    #[Test]
    public function it_fails_to_create_a_project_with_invalid_status(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $payload = $this->validProjectPayload([
            'status' => 'invalid-status',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->postJson(self::PROJECTS_URL, $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);

        $this->assertDatabaseCount('projects', 0);
    }

    #[Test]
    public function it_fails_to_create_a_project_when_unauthenticated(): void
    {
        // Arrange
        $payload = $this->validProjectPayload();

        // Act
        $response = $this->postJson(self::PROJECTS_URL, $payload);

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $this->assertDatabaseCount('projects', 0);
        $this->assertSame(0, Project::query()->count());
    }
}
