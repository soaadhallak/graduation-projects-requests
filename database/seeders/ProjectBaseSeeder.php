<?php

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\ProjectRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProjectBaseSeeder extends Seeder
{
    public function run(): void
    {
        $supervisor = User::create([
            'name' => 'Dr. Ahmad Supervisor',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
        ]);

        $supervisor->assignRole('supervisor');

        $teamA = Team::create(['name' => 'Team Alpha']);
        $teamB = Team::create(['name' => 'Team Beta']);

        $activeProject = Project::create([
            'supervisor_id' => $supervisor->id,
            'title' => 'project test',
            'description' => 'description test',
            'tools' => 'laravel',
            'status' => ProjectStatus::PENDING,
        ]);

        ProjectRequest::create([
            'project_id' => $activeProject->id,
            'team_id' => $teamA->id,
            'is_looking_for_members' => false,
        ]);

        $activeProjectTwo = Project::create([
            'supervisor_id' => $supervisor->id,
            'status' => ProjectStatus::PENDING,
            'title' => 'project test two',
            'description' => 'description test two',
            'tools' => 'laravel',
        ]);

        ProjectRequest::create([
            'project_id' => $activeProjectTwo->id,
            'team_id' => $teamB->id,
            'is_looking_for_members' => false,
        ]);
    }
}
