<?php

use App\Livewire\Admin\BlogPostList;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function blogListUser(): User
{
    return User::factory()->create();
}

test('guests are redirected from admin blog list', function () {
    $this->get('/admin/blog')->assertRedirect('/login');
});

test('authenticated user can view admin blog list', function () {
    $this->actingAs(blogListUser());

    $this->get('/admin/blog')->assertSuccessful();
});

test('admin blog list shows posts', function () {
    $this->actingAs(blogListUser());

    BlogPost::factory()->published()->create(['title' => 'Visible Test Post']);

    Livewire::test(BlogPostList::class)->assertSee('Visible Test Post');
});

test('admin blog list search by title works', function () {
    $this->actingAs(blogListUser());

    BlogPost::factory()->published()->create(['title' => 'Searchable Title Post']);
    BlogPost::factory()->published()->create(['title' => 'Different Name Article']);

    Livewire::test(BlogPostList::class)
        ->set('search', 'Searchable Title')
        ->assertSee('Searchable Title Post')
        ->assertDontSee('Different Name Article');
});

test('admin blog list search by excerpt works', function () {
    $this->actingAs(blogListUser());

    BlogPost::factory()->published()->create([
        'title' => 'Post With Excerpt',
        'excerpt' => 'This excerpt has a distinctive phrase',
    ]);
    BlogPost::factory()->published()->create([
        'title' => 'Other Post',
        'excerpt' => 'Nothing special in this excerpt',
    ]);

    Livewire::test(BlogPostList::class)
        ->set('search', 'distinctive phrase')
        ->assertSee('Post With Excerpt')
        ->assertDontSee('Other Post');
});

test('admin blog list filter by status works', function () {
    $this->actingAs(blogListUser());

    BlogPost::factory()->published()->create(['title' => 'Published One']);
    BlogPost::factory()->draft()->create(['title' => 'Draft One']);

    Livewire::test(BlogPostList::class)
        ->set('status', 'published')
        ->assertSee('Published One')
        ->assertDontSee('Draft One');
});

test('admin blog list sort by oldest works', function () {
    $this->actingAs(blogListUser());

    BlogPost::factory()->create(['title' => 'Newer Post', 'created_at' => now()->subDay()]);
    BlogPost::factory()->create(['title' => 'Oldest Post', 'created_at' => now()->subDays(30)]);

    Livewire::test(BlogPostList::class)
        ->set('sortBy', 'oldest')
        ->assertSee('Oldest Post')
        ->assertSee('Newer Post');
});

test('admin blog list sort by title works', function () {
    $this->actingAs(blogListUser());

    BlogPost::factory()->create(['title' => 'Zebra Article']);
    BlogPost::factory()->create(['title' => 'Apple Article']);

    Livewire::test(BlogPostList::class)
        ->set('sortBy', 'title')
        ->assertSee('Apple Article')
        ->assertSee('Zebra Article');
});

test('admin blog list sort by views works', function () {
    $this->actingAs(blogListUser());

    BlogPost::factory()->create(['title' => 'Low Views', 'views' => 1]);
    BlogPost::factory()->create(['title' => 'High Views', 'views' => 999]);

    Livewire::test(BlogPostList::class)
        ->set('sortBy', 'views')
        ->assertSee('High Views')
        ->assertSee('Low Views');
});

test('admin blog post can be deleted', function () {
    $this->actingAs(blogListUser());

    $post = BlogPost::factory()->create(['title' => 'Post To Delete']);

    Livewire::test(BlogPostList::class)
        ->call('deletePost', $post->id);

    expect(BlogPost::find($post->id))->toBeNull();
});

test('admin blog post toggle status draft to published sets published_at', function () {
    $this->actingAs(blogListUser());

    $post = BlogPost::factory()->draft()->create();

    Livewire::test(BlogPostList::class)->call('toggleStatus', $post->id);

    $updated = $post->fresh();
    expect($updated->status)->toBe('published');
    expect($updated->published_at)->not->toBeNull();
});

test('admin blog post toggle status published to draft clears published_at', function () {
    $this->actingAs(blogListUser());

    $post = BlogPost::factory()->published()->create();

    Livewire::test(BlogPostList::class)->call('toggleStatus', $post->id);

    $updated = $post->fresh();
    expect($updated->status)->toBe('draft');
    expect($updated->published_at)->toBeNull();
});

test('admin blog post delete shows success flash', function () {
    $this->actingAs(blogListUser());

    $post = BlogPost::factory()->create();

    Livewire::test(BlogPostList::class)
        ->call('deletePost', $post->id)
        ->assertSee('Post deleted successfully');
});
