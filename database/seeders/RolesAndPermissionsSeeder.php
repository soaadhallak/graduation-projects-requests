<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
   {
        $student= Role::firstOrCreate(['name' => 'student']);
        $admin= Role::firstOrCreate(['name' => 'admin']);
        $supervisor= Role::firstOrCreate(['name' => 'supervisor']);

        //permissions
        $permissionsForStudent=[
            'update self details',
            'create team',
            'view team',
            'leave team',
            'view team requests',
            'view open requests',
            'request to join team',
        ];
        $permissionsForAdmin=[
            'manage majors',
            'approve projects',
            'reject projects',
            'manage all users',
            'view all requests projects',
            'manage roles',
            'create archived projects'
        ];
        $permissionsForSupervisor=[
            'view own project',
            'grade project',
            'make project inprogress',
            'make project complete'
        ];
        $permissionForLeaderTeam=[
            'submit project request',
            'edit project request',
            'delete project request',
            'remove team member',
            'delete team'
        ];
        $allPermissions=array_merge($permissionsForStudent,$permissionsForAdmin,$permissionsForSupervisor,$permissionForLeaderTeam);

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $student->syncPermissions($permissionsForStudent);
        $admin->syncPermissions($permissionsForAdmin);
        $supervisor->syncPermissions($permissionsForSupervisor);
    }
}
