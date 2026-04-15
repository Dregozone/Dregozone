<?php

use App\Livewire\Blog;
use App\Livewire\BlogPost as BlogPostComponent;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('home page renders successfully', function () {
    $this->get('/')->assertSuccessful();
});

test('blog page renders with published posts', function () {
    $post = BlogPost::factory()->published()->create(['title' => 'My Published Post']);

    Livewire::test(Blog::class)->assertSee('My Published Post');
});

test('blog page search filters results by title', function () {
    BlogPost::factory()->published()->create(['title' => 'Laravel Tips and Tricks']);
    BlogPost::factory()->published()->create(['title' => 'Unrelated Article']);

    Livewire::test(Blog::class)
        ->set('search', 'Laravel Tips')
        ->assertSee('Laravel Tips and Tricks')
        ->assertDontSee('Unrelated Article');
});

test('blog page search filters results by excerpt', function () {
    BlogPost::factory()->published()->create([
        'title' => 'First Post',
        'excerpt' => 'This discusses a unique excerpt topic',
    ]);
    BlogPost::factory()->published()->create([
        'title' => 'Second Post',
        'excerpt' => 'Completely different subject matter here',
    ]);

    Livewire::test(Blog::class)
        ->set('search', 'unique excerpt topic')
        ->assertSee('First Post')
        ->assertDontSee('Second Post');
});

test('blog page tag filter works', function () {
    BlogPost::factory()->published()->create(['title' => 'Laravel Post', 'tags' => ['Laravel', 'PHP']]);
    BlogPost::factory()->published()->create(['title' => 'CSS Post', 'tags' => ['CSS']]);

    Livewire::test(Blog::class)
        ->set('tag', 'Laravel')
        ->assertSee('Laravel Post')
        ->assertDontSee('CSS Post');
});

test('blog page sort by latest works', function () {
    BlogPost::factory()->published()->create([
        'title' => 'Older Post',
        'published_at' => now()->subDays(10),
    ]);
    BlogPost::factory()->published()->create([
        'title' => 'Newer Post',
        'published_at' => now()->subDay(),
    ]);

    $component = Livewire::test(Blog::class)->set('sortBy', 'latest');
    $component->assertSee('Newer Post')->assertSee('Older Post');
});

test('blog page sort by oldest works', function () {
    BlogPost::factory()->published()->create([
        'title' => 'Old Article',
        'published_at' => now()->subDays(20),
    ]);
    BlogPost::factory()->published()->create([
        'title' => 'Recent Article',
        'published_at' => now()->subDay(),
    ]);

    Livewire::test(Blog::class)
        ->set('sortBy', 'oldest')
        ->assertSee('Old Article')
        ->assertSee('Recent Article');
});

test('blog page sort by popular works', function () {
    BlogPost::factory()->published()->create(['title' => 'Low Views Post', 'views' => 5]);
    BlogPost::factory()->published()->create(['title' => 'High Views Post', 'views' => 500]);

    Livewire::test(Blog::class)
        ->set('sortBy', 'popular')
        ->assertSee('High Views Post')
        ->assertSee('Low Views Post');
});

test('blog page shows empty state when no posts', function () {
    Livewire::test(Blog::class)
        ->assertDontSee('<article');
});

test('blog post detail page renders for published post', function () {
    $post = BlogPost::factory()->published()->create(['title' => 'A Detailed Post']);

    Livewire::test(BlogPostComponent::class, ['post' => $post])
        ->assertSee('A Detailed Post');
});

test('blog post detail page returns 404 for nonexistent slug', function () {
    $this->get('/blog/this-slug-does-not-exist')->assertNotFound();
});

test('blog post detail page increments view count', function () {
    $post = BlogPost::factory()->published()->create(['views' => 0]);

    Livewire::test(BlogPostComponent::class, ['post' => $post]);

    expect($post->fresh()->views)->toBe(1);
});

test('projects page renders', function () {
    $this->get('/projects')->assertSuccessful();
});

test('contact page renders', function () {
    $this->get('/contact')->assertSuccessful();
});

test('privacy policy page renders', function () {
    $this->get('/privacy-policy')->assertSuccessful();
});
