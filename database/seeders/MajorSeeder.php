<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $majors=['هندسة برمجيات','شبكات'];

        foreach($majors as $major){
            Major::updateOrCreate(
                ['name'=>$major],
                ['name'=>$major]
            );
        }

    }
}
