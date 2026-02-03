<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'supervisor']);

        $supervisors = [
            [
                'name' => 'Ahmed Ali',
                'email' => 'ahmed.supervisor@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sara Mansour',
                'email' => 'sara.supervisor@gmail.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($supervisors as $supervisor) {
            $user = User::updateOrCreate(
                ['email' => $supervisor['email']],
                $supervisor
            );
            
            $user->assignRole($role);
        }
    }
}
