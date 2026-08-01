<?php

namespace Tests\Feature\Api\V1\Tasks;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class ListTasksTest extends TaskTestCase
{
    #[Test]
    public function it_lists_tasks_for_the_authenticated_users_project(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        $otherProject = $this->projectFor($user);

        $ownedTasks = Task::factory()
            ->count(2)
            ->for($project)
            ->sequence(
                ['title' => 'Alpha Task'],
                ['title' => 'Beta Task'],
            )
            ->create();

        Task::factory()->for($otherProject)->create([
            'title' => 'Other Project Task',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson($this->tasksUrl($project->id));

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('message', 'Tasks retrieved successfully')
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
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
            $ownedTasks->pluck('id')->all(),
            $returnedIds
        );
    }

    #[Test]
    public function it_filters_tasks_by_status(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);

        $todo = Task::factory()->for($project)->create([
            'title' => 'Todo Task',
            'status' => TaskStatus::Todo,
        ]);
        Task::factory()->for($project)->inProgress()->create([
            'title' => 'In Progress Task',
        ]);
        Task::factory()->for($project)->done()->create([
            'title' => 'Done Task',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson($this->tasksUrl($project->id).'?status='.TaskStatus::Todo->value);

        // Assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $todo->id)
            ->assertJsonPath('data.0.status', TaskStatus::Todo->value);
    }

    #[Test]
    public function it_filters_tasks_by_priority(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);

        $high = Task::factory()->for($project)->high()->create([
            'title' => 'High Priority Task',
        ]);
        Task::factory()->for($project)->low()->create([
            'title' => 'Low Priority Task',
        ]);
        Task::factory()->for($project)->create([
            'title' => 'Medium Priority Task',
            'priority' => TaskPriority::Medium,
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson($this->tasksUrl($project->id).'?priority='.TaskPriority::High->value);

        // Assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $high->id)
            ->assertJsonPath('data.0.priority', TaskPriority::High->value);
    }

    #[Test]
    public function it_searches_tasks_by_title(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);

        $match = Task::factory()->for($project)->create([
            'title' => 'Implement authentication',
            'description' => 'General notes',
        ]);
        Task::factory()->for($project)->create([
            'title' => 'Write docs',
            'description' => 'Includes authentication notes',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson($this->tasksUrl($project->id).'?search=authentication');

        // Assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $match->id);
    }

    #[Test]
    public function it_paginates_tasks_with_per_page(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);
        Task::factory()->count(3)->for($project)->create();

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson($this->tasksUrl($project->id).'?per_page=2');

        // Assert
        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.has_more', true);

        $this->assertNotNull($response->json('meta.next_cursor'));
    }

    #[Test]
    public function it_fails_to_list_tasks_with_invalid_filters(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $project = $this->projectFor($user);

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson($this->tasksUrl($project->id).'?status=blocked&priority=urgent');

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status', 'priority']);
    }

    #[Test]
    public function it_fails_to_list_tasks_for_another_users_project(): void
    {
        // Arrange
        $user = $this->authenticatedUser();
        $otherUser = User::factory()->create();
        $project = Project::factory()->for($otherUser)->create();

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson($this->tasksUrl($project->id));

        // Assert
        $response
            ->assertNotFound()
            ->assertJsonPath('error.message', 'Project not found.');
    }

    #[Test]
    public function it_fails_to_list_tasks_when_unauthenticated(): void
    {
        // Arrange
        $project = Project::factory()->create();

        // Act
        $response = $this->getJson($this->tasksUrl($project->id));

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }
}
