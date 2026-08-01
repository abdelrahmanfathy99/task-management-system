<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Seed the tasks table.
     */
    public function run(): void
    {
        // Create tasks for each project
        Project::all()->each(function (Project $project) {
            Task::factory(5)->create([
                'project_id' => $project->id,
            ]);
        });
    }
}
