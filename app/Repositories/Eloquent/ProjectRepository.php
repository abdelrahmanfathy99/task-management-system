<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Support\Collection;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function findByIdForUser(int $id, int $userId): ?Project
    {
        return Project::query()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function listForUser(int $userId): Collection
    {
        return Project::query()
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function save(array $data, ?Project $project = null): Project
    {
        if ($project) {
            $project->update($data);

            return $project;
        }

        return Project::query()->create($data);
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }
}
