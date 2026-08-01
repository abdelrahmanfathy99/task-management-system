<?php

namespace Tests\Feature\Api\V1\Tasks;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class TaskTestCase extends TestCase
{
    use RefreshDatabase;

    protected function authenticatedUser(): User
    {
        return User::factory()->create();
    }

    protected function projectFor(User $user): Project
    {
        return Project::factory()->for($user)->create();
    }

    protected function tasksUrl(int $projectId): string
    {
        return '/api/v1/projects/'.$projectId.'/tasks';
    }

    protected function taskUrl(int $projectId, int $taskId): string
    {
        return $this->tasksUrl($projectId).'/'.$taskId;
    }

    /**
     * @return array{title: string, description: string, priority: string, status: string, due_date: string}
     */
    protected function validTaskPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Implement login page',
            'description' => 'Build the login UI and wire it to the API',
            'priority' => TaskPriority::Medium->value,
            'status' => TaskStatus::Todo->value,
            'due_date' => '2026-08-15',
        ], $overrides);
    }
}
