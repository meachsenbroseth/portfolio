<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('education')->count() > 0) {
            return;
        }

        $now = now();

        DB::table('education')->insert([
            [
                'title' => 'B.Sc. Computer Science',
                'date' => '2019 - 2023',
                'order' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'High School Diploma',
                'date' => '2016 - 2019',
                'order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

