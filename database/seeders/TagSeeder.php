<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Laravel', 'type' => 'framework', 'description' => 'Laravel PHP Framework'],
            ['name' => 'PHP', 'type' => 'language', 'description' => 'PHP Programming Language'],
            ['name' => 'JavaScript', 'type' => 'language', 'description' => 'JavaScript Programming Language'],
            ['name' => 'TypeScript', 'type' => 'language', 'description' => 'TypeScript Programming Language'],
            ['name' => 'React', 'type' => 'framework', 'description' => 'React JavaScript Library'],
            ['name' => 'Vue.js', 'type' => 'framework', 'description' => 'Vue.js JavaScript Framework'],
            ['name' => 'Node.js', 'type' => 'runtime', 'description' => 'Node.js JavaScript Runtime'],
            ['name' => 'Python', 'type' => 'language', 'description' => 'Python Programming Language'],
            ['name' => 'Django', 'type' => 'framework', 'description' => 'Django Python Framework'],
            ['name' => 'Tailwind CSS', 'type' => 'framework', 'description' => 'Tailwind CSS Utility Framework'],
            ['name' => 'Alpine.js', 'type' => 'framework', 'description' => 'Alpine.js JavaScript Framework'],
            ['name' => 'Docker', 'type' => 'tool', 'description' => 'Docker Container Platform'],
            ['name' => 'MySQL', 'type' => 'database', 'description' => 'MySQL Database'],
            ['name' => 'PostgreSQL', 'type' => 'database', 'description' => 'PostgreSQL Database'],
            ['name' => 'Redis', 'type' => 'tool', 'description' => 'Redis Cache & Queue'],
            ['name' => 'API', 'type' => 'concept', 'description' => 'RESTful API Development'],
            ['name' => 'Testing', 'type' => 'practice', 'description' => 'Software Testing'],
            ['name' => 'DevOps', 'type' => 'practice', 'description' => 'DevOps Practices'],
            ['name' => 'Git', 'type' => 'tool', 'description' => 'Git Version Control'],
            ['name' => 'HTML', 'type' => 'language', 'description' => 'HTML Markup Language'],
            ['name' => 'CSS', 'type' => 'language', 'description' => 'CSS Styling'],
            ['name' => 'Database Design', 'type' => 'concept', 'description' => 'Database Design & Modeling'],
            ['name' => 'Security', 'type' => 'practice', 'description' => 'Web Security'],
            ['name' => 'Performance', 'type' => 'practice', 'description' => 'Performance Optimization'],
            ['name' => 'Livewire', 'type' => 'framework', 'description' => 'Laravel Livewire'],
            ['name' => 'Filament', 'type' => 'framework', 'description' => 'Filament Admin Panel'],
            ['name' => 'Go', 'type' => 'language', 'description' => 'Go Programming Language'],
            ['name' => 'Rust', 'type' => 'language', 'description' => 'Rust Programming Language'],
            ['name' => 'Kubernetes', 'type' => 'tool', 'description' => 'Kubernetes Orchestration'],
            ['name' => 'GraphQL', 'type' => 'concept', 'description' => 'GraphQL API'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag['name']],
                [
                    'slug' => \Illuminate\Support\Str::slug($tag['name']),
                    'type' => $tag['type'],
                    'description' => $tag['description'],
                    'usage_count' => 0,
                ]
            );
        }
    }
}
