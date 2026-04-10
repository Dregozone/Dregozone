<?php

use App\Livewire\Admin\BlogPostPreview;
use App\Models\BlogPost;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to login from the preview route', function () {
    $post = BlogPost::factory()->draft()->create();

    $this->get("/admin/blog/{$post->id}/preview")->assertRedirect('/login');
});

test('authenticated user can access the preview page', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->draft()->create();

    $this->actingAs($user)
        ->get("/admin/blog/{$post->id}/preview")
        ->assertStatus(200);
});

test('preview page shows the preview banner', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->draft()->create();

    Livewire::actingAs($user)
        ->test(BlogPostPreview::class, ['postId' => $post->id])
        ->assertSee('Preview Mode')
        ->assertSee('This blog post is not yet published');
});

test('preview page shows the post title and excerpt', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->draft()->create([
        'title' => 'My Draft Post Title',
        'excerpt' => 'A short summary of the draft.',
    ]);

    Livewire::actingAs($user)
        ->test(BlogPostPreview::class, ['postId' => $post->id])
        ->assertSee('My Draft Post Title')
        ->assertSee('A short summary of the draft.');
});

test('preview page does not increment views', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->draft()->create(['views' => 5]);

    Livewire::actingAs($user)
        ->test(BlogPostPreview::class, ['postId' => $post->id]);

    expect($post->fresh()->views)->toBe(5);
});

test('preview works for published posts too', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->published()->create();

    Livewire::actingAs($user)
        ->test(BlogPostPreview::class, ['postId' => $post->id])
        ->assertSee('Preview Mode');
});
