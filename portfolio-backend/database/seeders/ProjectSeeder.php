<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        if (Project::count() > 0) {
            return;
        }

        Project::create([
            'title' => 'Portfolio API',
            'slug' => 'portfolio-api',
            'short_description' => 'Laravel backend serving my portfolio data.',
            'description' => 'A small Laravel API that exposes education, experience, projects, and blog endpoints. Built with clean routes/controllers and SQLite for quick local development.',
            'category' => 'Backend',
            'date' => '2026-01',
            'image' => null,
            'github_link' => 'https://github.com/example/portfolio-api',
            'technologies' => ['Laravel', 'PHP', 'SQLite'],
            'status' => 'published',
        ]);

        Project::create([
            'title' => 'Realtime Dashboard',
            'slug' => 'realtime-dashboard',
            'short_description' => 'A dashboard-style project showcasing queued updates.',
            'description' => 'A demo app that uses the backend to simulate realtime updates. Useful for testing queue workflows, pagination, and data freshness across endpoints.',
            'category' => 'Full-stack',
            'date' => '2025-11',
            'image' => null,
            'github_link' => 'https://github.com/example/realtime-dashboard',
            'technologies' => ['Laravel', 'Queue', 'Redis'],
            'status' => 'published',
        ]);

        Project::create([
            'title' => 'Queue Worker Notes',
            'slug' => 'queue-worker-notes',
            'short_description' => 'Experiments documenting background jobs and retries.',
            'description' => 'A draft project entry that documents queue behavior, retry policies, and how to keep background processing reliable in development.',
            'category' => 'Backend',
            'date' => '2025-07',
            'image' => null,
            'github_link' => 'https://github.com/example/queue-worker-notes',
            'technologies' => ['Laravel', 'Queues'],
            'status' => 'draft',
        ]);
    }
}

