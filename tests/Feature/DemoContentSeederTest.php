<?php

use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Project;
use App\Models\Tool;
use Database\Seeders\DemoContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('demo content seeder fills every public and admin screen', function () {
    $this->seed(DemoContentSeeder::class);

    expect(BlogPost::published()->count())->toBe(10)
        ->and(BlogPost::where('status', 'draft')->count())->toBe(2)
        ->and(Project::featured()->count())->toBe(3)
        ->and(Tool::count())->toBe(1)
        ->and(ContactMessage::count())->toBe(4)
        ->and(NewsletterSubscriber::count())->toBe(6);
});

test('demo content seeder is idempotent', function () {
    $this->seed(DemoContentSeeder::class);
    $this->seed(DemoContentSeeder::class);

    expect(BlogPost::count())->toBe(12)
        ->and(Project::count())->toBe(6);
});

test('demo content seeder never runs in production', function () {
    app()->detectEnvironment(fn () => 'production');

    app(DemoContentSeeder::class)->run();

    expect(BlogPost::count())->toBe(0)
        ->and(Project::count())->toBe(0);
});
