<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\ExperienceSeeder;
use Database\Seeders\EducationSeeder;
use Database\Seeders\BlogSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ProjectSeeder::class,
            ExperienceSeeder::class,
            EducationSeeder::class,
            BlogSeeder::class,
        ]);

        // Deterministic test user for local development.
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'role' => 'user',
                'password' => Hash::make('password'),
            ],
        );
    }
}
