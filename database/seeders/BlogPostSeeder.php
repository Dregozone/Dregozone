<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Building Modern Web Applications with Laravel and Livewire',
                'excerpt' => 'Explore how Laravel and Livewire work together to create dynamic, reactive web applications without the complexity of traditional JavaScript frameworks.',
                'content' => '<h2>Introduction</h2>
<p>Laravel and Livewire have revolutionized the way we build modern web applications. By combining the power of Laravel\'s backend capabilities with Livewire\'s reactive frontend components, developers can create sophisticated applications with minimal JavaScript.</p>

<h2>Why Laravel + Livewire?</h2>
<p>The combination offers several advantages:</p>
<ul>
<li><strong>Rapid Development:</strong> Build features quickly without switching between multiple languages</li>
<li><strong>Real-time Updates:</strong> Create reactive interfaces without complex JavaScript</li>
<li><strong>Maintainability:</strong> Keep your logic in one place with clear separation of concerns</li>
<li><strong>Performance:</strong> Optimized rendering and minimal client-side overhead</li>
</ul>

<h2>Getting Started</h2>
<p>To begin building with Laravel and Livewire:</p>
<ol>
<li>Install Laravel and set up your project</li>
<li>Add Livewire to your Laravel application</li>
<li>Create your first Livewire component</li>
<li>Build reactive interfaces with ease</li>
</ol>

<h2>Best Practices</h2>
<p>When working with this stack, consider these best practices:</p>
<ul>
<li>Keep components focused and single-purpose</li>
<li>Use Laravel\'s validation in your Livewire components</li>
<li>Optimize database queries and avoid N+1 problems</li>
<li>Implement proper error handling and user feedback</li>
</ul>

<h2>Conclusion</h2>
<p>Laravel and Livewire provide a powerful foundation for modern web development. By leveraging the strengths of both technologies, you can build applications that are both powerful and maintainable.</p>',
                'tags' => ['Laravel', 'Livewire', 'PHP', 'Web Development'],
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'views' => 1247,
            ],
            [
                'title' => 'The Future of Web Development: Trends to Watch in 2024',
                'excerpt' => 'Discover the key trends shaping web development in 2024, from AI integration to performance optimization and beyond.',
                'content' => '<h2>AI-Powered Development</h2>
<p>Artificial Intelligence is transforming how we write and maintain code. From automated testing to intelligent code completion, AI tools are becoming essential for modern developers.</p>

<h2>Performance First</h2>
<p>Web performance continues to be a critical factor in user experience and SEO. Modern frameworks and tools are prioritizing speed and efficiency.</p>

<h2>Component-Driven Architecture</h2>
<p>The shift towards component-based development is accelerating, with tools like Livewire, React, and Vue leading the charge.</p>

<h2>Accessibility by Default</h2>
<p>Web accessibility is no longer optional. Modern frameworks and tools are building accessibility features into their core.</p>

<h2>Conclusion</h2>
<p>Staying ahead in web development means embracing these trends while maintaining focus on delivering value to users.</p>',
                'tags' => ['Web Development', 'Trends', 'AI', 'Performance'],
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'views' => 892,
            ],
            [
                'title' => 'Optimizing Database Performance in Laravel Applications',
                'excerpt' => 'Learn essential techniques for optimizing database performance in Laravel applications, from query optimization to caching strategies.',
                'content' => '<h2>Understanding Query Performance</h2>
<p>Database performance is crucial for any web application. In Laravel, understanding how queries are executed and optimized can significantly improve your application\'s speed.</p>

<h2>Common Performance Issues</h2>
<p>Some of the most common performance problems include:</p>
<ul>
<li>N+1 query problems</li>
<li>Missing database indexes</li>
<li>Inefficient eager loading</li>
<li>Lack of proper caching</li>
</ul>

<h2>Optimization Techniques</h2>
<p>Here are some proven techniques for improving database performance:</p>
<ol>
<li><strong>Use Eager Loading:</strong> Avoid N+1 queries with proper relationship loading</li>
<li><strong>Implement Caching:</strong> Cache frequently accessed data</li>
<li><strong>Optimize Queries:</strong> Use Laravel\'s query builder efficiently</li>
<li><strong>Add Indexes:</strong> Create proper database indexes for your queries</li>
</ol>

<h2>Monitoring and Profiling</h2>
<p>Regular monitoring and profiling help identify performance bottlenecks before they become problems.</p>

<h2>Conclusion</h2>
<p>Database optimization is an ongoing process that requires attention to detail and regular monitoring.</p>',
                'tags' => ['Laravel', 'Database', 'Performance', 'Optimization'],
                'status' => 'published',
                'published_at' => now()->subDays(8),
                'views' => 1563,
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::create([
                'title' => $post['title'],
                'slug' => Str::slug($post['title']),
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'tags' => $post['tags'],
                'status' => $post['status'],
                'published_at' => $post['published_at'],
                'views' => $post['views'],
            ]);
        }
    }
}
