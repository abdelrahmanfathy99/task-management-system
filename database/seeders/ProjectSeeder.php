<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Seed the projects table.
     */
    public function run(): void
    {
        // Create projects for each user
        User::all()->each(function (User $user) {
            Project::factory(3)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
