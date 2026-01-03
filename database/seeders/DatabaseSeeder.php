<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       $this->call([
            RolesAndPermissionsSeeder::class,
            MajorSeeder::class
        ]);


        $user=User::factory()->create([
            'name' => 'admin admin',
            'email' => 'admin@admin.com',
            'password'=>Hash::make('12345678')
        ]);
        $user->assignRole('admin');
    }
}
