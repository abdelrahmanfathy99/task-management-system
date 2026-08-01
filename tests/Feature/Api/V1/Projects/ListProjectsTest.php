<?php

namespace Tests\Feature\Api\V1\Projects;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class ListProjectsTest extends ProjectTestCase
{
    #[Test]
    public function it_lists_projects_for_the_authenticated_user(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $otherUser = User::factory()->create();

        $ownedProjects = Project::factory()
            ->count(2)
            ->for($user)
            ->sequence(
                ['name' => 'Alpha Project'],
                ['name' => 'Beta Project'],
            )
            ->create();

        Project::factory()->for($otherUser)->create([
            'name' => 'Other User Project',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson(self::PROJECTS_URL);

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('message', 'Projects retrieved successfully')
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'meta' => [
                    'per_page',
                    'next_cursor',
                    'prev_cursor',
                    'has_more',
                ],
            ])
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 15)
            ->assertJsonPath('meta.has_more', false);

        $returnedIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertEqualsCanonicalizing(
            $ownedProjects->pluck('id')->all(),
            $returnedIds
        );
        $this->assertNotContains(
            Project::query()->where('user_id', $otherUser->id)->value('id'),
            $returnedIds
        );
    }

    #[Test]
    public function it_filters_projects_by_status(): void
    {
        // Arrange
        $user = $this->authenticatedUser();

        $active = Project::factory()->for($user)->create([
            'name' => 'Active Project',
            'status' => ProjectStatus::Active,
        ]);
        Project::factory()->for($user)->completed()->create([
            'name' => 'Completed Project',
        ]);
        Project::factory()->for($user)->archived()->create([
            'name' => 'Archived Project',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson(self::PROJECTS_URL.'?status='.ProjectStatus::Active->value);

        // Assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $active->id)
            ->assertJsonPath('data.0.status', ProjectStatus::Active->value);
    }

    #[Test]
    public function it_searches_projects_by_name_or_description(): void
    {
        // Arrange
        $user = $this->authenticatedUser();

        $byName = Project::factory()->for($user)->create([
            'name' => 'Marketing Launch',
            'description' => 'General notes',
        ]);
        $byDescription = Project::factory()->for($user)->create([
            'name' => 'Ops Work',
            'description' => 'Includes marketing assets',
        ]);
        Project::factory()->for($user)->create([
            'name' => 'Unrelated',
            'description' => 'Something else',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson(self::PROJECTS_URL.'?search=marketing');

        // Assert
        $response
            ->assertOk()
            ->assertJsonCount(2, 'data');

        $returnedIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertEqualsCanonicalizing(
            [$byName->id, $byDescription->id],
            $returnedIds
        );
    }

    #[Test]
    public function it_paginates_projects_with_per_page(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        Project::factory()->count(3)->for($user)->create();

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson(self::PROJECTS_URL.'?per_page=2');

        // Assert
        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.has_more', true);

        $this->assertNotNull($response->json('meta.next_cursor'));
    }

    #[Test]
    public function it_fails_to_list_projects_with_invalid_status_filter(): void
    {
        // Arrange
        $user = $this->authenticatedUser();

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson(self::PROJECTS_URL.'?status=not-a-status');

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    #[Test]
    public function it_fails_to_list_projects_when_unauthenticated(): void
    {
        // Arrange
        // no auth

        // Act
        $response = $this->getJson(self::PROJECTS_URL);

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }
}
