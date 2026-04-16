<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        if (Experience::count() > 0) {
            return;
        }

        Experience::create([
            'title' => 'Software Engineer',
            'date' => '2025 - Present',
            'order' => 0,
        ]);

        Experience::create([
            'title' => 'Frontend Developer',
            'date' => '2024 - 2025',
            'order' => 1,
        ]);

        Experience::create([
            'title' => 'Web Developer',
            'date' => '2022 - 2024',
            'order' => 2,
        ]);
    }
}

