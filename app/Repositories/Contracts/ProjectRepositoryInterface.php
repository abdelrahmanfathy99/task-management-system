<?php

namespace App\Repositories\Contracts;

use App\Models\Project;
use Illuminate\Support\Collection;

interface ProjectRepositoryInterface
{
    public function findByIdForUser(int $id, int $userId): ?Project;

    /**
     * @return Collection<int, Project>
     */
    public function listForUser(int $userId): Collection;

    public function save(array $data, ?Project $project = null): Project;

    public function delete(Project $project): void;
}
