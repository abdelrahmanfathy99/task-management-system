<?php

namespace Tests\Feature\Api\V1\Projects;

use App\Enums\ProjectStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class ProjectTestCase extends TestCase
{
    use RefreshDatabase;

    protected const PROJECTS_URL = '/api/v1/projects';

    protected function authenticatedUser(): User
    {
        return User::factory()->create();
    }

    protected function projectUrl(int $projectId): string
    {
        return self::PROJECTS_URL.'/'.$projectId;
    }

    /**
     * @return array{name: string, description: string, status: string}
     */
    protected function validProjectPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Website Redesign',
            'description' => 'Rebuild the marketing site',
            'status' => ProjectStatus::Active->value,
        ], $overrides);
    }
}
