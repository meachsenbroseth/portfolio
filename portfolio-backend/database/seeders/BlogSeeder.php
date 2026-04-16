<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        if (Blog::count() > 0) {
            return;
        }

        Blog::create([
            'title' => 'Building a Portfolio API with Laravel',
            'slug' => 'building-portfolio-api-with-laravel',
            'excerpt' => 'How I structured routes, controllers, and database seeding for a clean portfolio backend.',
            'content' => "In this post, I walk through how I built a small Laravel API for my portfolio.\n\nWe start with route definitions, then move into controllers and models, and finally add seeders so the frontend can render meaningful data right away.",
            'cover_image' => null,
            'category' => 'Laravel',
            'meta_title' => 'Building a Portfolio API with Laravel',
            'meta_description' => 'A quick guide to structuring Laravel routes and seeders for portfolio content.',
            'tags' => ['Laravel', 'API', 'SQLite'],
            'status' => 'published',
            'published_at' => now()->subDays(45),
        ]);

        Blog::create([
            'title' => 'Pagination Notes and Edge Cases',
            'slug' => 'pagination-notes-and-edge-cases',
            'excerpt' => 'What I learned while testing pagination responses and frontend expectations.',
            'content' => "Pagination is simple until it isn't.\n\nThis draft covers the edge cases I hit: empty pages, consistent ordering, and how response shape affects the UI.",
            'cover_image' => null,
            'category' => 'Engineering',
            'meta_title' => null,
            'meta_description' => null,
            'tags' => ['React', 'Laravel', 'Pagination'],
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}

