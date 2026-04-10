<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'E-commerce Platform',
                'description' => 'A full-featured e-commerce platform built with Laravel and Livewire. Features include product management, shopping cart, payment processing, and admin dashboard.',
                'technologies' => ['Laravel', 'Livewire', 'MySQL', 'Tailwind CSS', 'Alpine.js'],
                'status' => 'completed',
                'featured' => true,
                'order' => 1,
                'url' => 'https://example.com',
                'github_url' => 'https://github.com/example/ecommerce',
            ],
            [
                'title' => 'Task Management System',
                'description' => 'A collaborative task management application with real-time updates, team collaboration, and project tracking capabilities.',
                'technologies' => ['Laravel', 'Livewire', 'PostgreSQL', 'Redis', 'Vue.js'],
                'status' => 'completed',
                'featured' => true,
                'order' => 2,
                'url' => 'https://example.com',
                'github_url' => 'https://github.com/example/task-manager',
            ],
            [
                'title' => 'API Development Platform',
                'description' => 'A comprehensive API development and testing platform with documentation generation, rate limiting, and analytics.',
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'Redis', 'Swagger'],
                'status' => 'completed',
                'featured' => false,
                'order' => 3,
                'url' => 'https://example.com',
                'github_url' => 'https://github.com/example/api-platform',
            ],
            [
                'title' => 'Real-time Chat Application',
                'description' => 'A modern chat application with real-time messaging, file sharing, and user presence indicators.',
                'technologies' => ['Laravel', 'Livewire', 'WebSockets', 'MySQL', 'Tailwind CSS'],
                'status' => 'in_progress',
                'featured' => true,
                'order' => 4,
                'url' => null,
                'github_url' => 'https://github.com/example/chat-app',
            ],
            [
                'title' => 'Learning Management System',
                'description' => 'An educational platform for online courses with video streaming, progress tracking, and interactive assessments.',
                'technologies' => ['Laravel', 'Livewire', 'MySQL', 'FFmpeg', 'JavaScript'],
                'status' => 'in_progress',
                'featured' => false,
                'order' => 5,
                'url' => null,
                'github_url' => 'https://github.com/example/lms',
            ],
            [
                'title' => 'Inventory Management System',
                'description' => 'A comprehensive inventory management solution with barcode scanning, reporting, and supplier management.',
                'technologies' => ['Laravel', 'Livewire', 'MySQL', 'Bootstrap', 'jQuery'],
                'status' => 'completed',
                'featured' => false,
                'order' => 6,
                'url' => 'https://example.com',
                'github_url' => 'https://github.com/example/inventory',
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
