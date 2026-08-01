<?php

namespace Tests\Feature\Api\V1\Dashboard;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    private const DASHBOARD_URL = '/api/v1/dashboard';

    #[Test]
    public function it_returns_dashboard_stats_for_the_authenticated_user(): void
    {
        // Arrange
        Carbon::setTestNow('2026-08-15 10:00:00');

        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $activeProject = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
        ]);
        Project::factory()->for($user)->create([
            'status' => ProjectStatus::Completed,
        ]);
        Project::factory()->for($user)->create([
            'status' => ProjectStatus::Archived,
        ]);
        Project::factory()->for($otherUser)->create([
            'status' => ProjectStatus::Active,
        ]);

        Task::factory()->for($activeProject)->create([
            'status' => TaskStatus::Todo,
            'due_date' => '2026-08-20',
        ]);
        Task::factory()->for($activeProject)->inProgress()->create([
            'due_date' => '2026-08-18',
        ]);
        Task::factory()->for($activeProject)->done()->create([
            'due_date' => '2026-08-01',
        ]);
        Task::factory()->for($activeProject)->create([
            'status' => TaskStatus::Todo,
            'due_date' => '2026-08-10',
        ]);

        $otherProject = Project::factory()->for($otherUser)->create();
        Task::factory()->for($otherProject)->create([
            'status' => TaskStatus::Todo,
            'due_date' => '2026-08-01',
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson(self::DASHBOARD_URL);

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('message', 'Dashboard stats retrieved successfully')
            ->assertExactJson([
                'message' => 'Dashboard stats retrieved successfully',
                'data' => [
                    'total_projects' => 3,
                    'active_projects' => 1,
                    'total_tasks' => 4,
                    'completed_tasks' => 1,
                    'pending_tasks' => 3,
                    'overdue_tasks' => 1,
                ],
            ]);
    }

    #[Test]
    public function it_returns_zero_stats_when_the_user_has_no_data(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson(self::DASHBOARD_URL);

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('data.total_projects', 0)
            ->assertJsonPath('data.active_projects', 0)
            ->assertJsonPath('data.total_tasks', 0)
            ->assertJsonPath('data.completed_tasks', 0)
            ->assertJsonPath('data.pending_tasks', 0)
            ->assertJsonPath('data.overdue_tasks', 0);
    }

    #[Test]
    public function it_excludes_soft_deleted_projects_and_tasks(): void
    {
        // Arrange
        Carbon::setTestNow('2026-08-15 10:00:00');

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
        ]);
        $deletedProject = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
        ]);

        Task::factory()->for($project)->create([
            'status' => TaskStatus::Todo,
            'due_date' => '2026-08-01',
        ]);
        $deletedTask = Task::factory()->for($project)->create([
            'status' => TaskStatus::Todo,
            'due_date' => '2026-08-01',
        ]);

        $deletedTask->delete();
        $deletedProject->delete();

        // Act
        $response = $this
            ->actingAs($user)
            ->getJson(self::DASHBOARD_URL);

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('data.total_projects', 1)
            ->assertJsonPath('data.active_projects', 1)
            ->assertJsonPath('data.total_tasks', 1)
            ->assertJsonPath('data.pending_tasks', 1)
            ->assertJsonPath('data.overdue_tasks', 1);
    }

    #[Test]
    public function it_fails_to_get_dashboard_stats_when_unauthenticated(): void
    {
        // Arrange
        // no auth

        // Act
        $response = $this->getJson(self::DASHBOARD_URL);

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }
}
