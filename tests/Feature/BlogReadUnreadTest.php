<?php

use App\Livewire\Blog;
use App\Livewire\BlogPost as BlogPostComponent;
use App\Models\BlogPost;
use App\Models\User;
use App\Models\UserBlogRead;
use App\Models\UserBlogView;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('viewing a blog post as authenticated user creates a view record', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->published()->create();

    $this->actingAs($user);

    Livewire::test(BlogPostComponent::class, ['post' => $post]);

    expect(UserBlogView::where('user_id', $user->id)->where('blog_post_id', $post->id)->count())->toBe(1);
});

test('viewing a blog post as guest does not create a view record', function () {
    $post = BlogPost::factory()->published()->create();

    Livewire::test(BlogPostComponent::class, ['post' => $post]);

    expect(UserBlogView::count())->toBe(0);
});

test('authenticated user can mark a blog post as read', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->published()->create();

    $this->actingAs($user);

    Livewire::test(BlogPostComponent::class, ['post' => $post])
        ->assertSet('isRead', false)
        ->call('markAsRead')
        ->assertSet('isRead', true);

    expect(UserBlogRead::where('user_id', $user->id)->where('blog_post_id', $post->id)->exists())->toBeTrue();
});

test('authenticated user can mark a blog post as unread', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->published()->create();

    UserBlogRead::create([
        'user_id' => $user->id,
        'blog_post_id' => $post->id,
    ]);

    $this->actingAs($user);

    Livewire::test(BlogPostComponent::class, ['post' => $post])
        ->assertSet('isRead', true)
        ->call('markAsUnread')
        ->assertSet('isRead', false);

    expect(UserBlogRead::where('user_id', $user->id)->where('blog_post_id', $post->id)->exists())->toBeFalse();
});

test('unauthenticated user cannot mark a blog post as read', function () {
    $post = BlogPost::factory()->published()->create();

    Livewire::test(BlogPostComponent::class, ['post' => $post])
        ->call('markAsRead');

    expect(UserBlogRead::count())->toBe(0);
});

test('blog post component loads with correct read state for previously read post', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->published()->create();

    UserBlogRead::create([
        'user_id' => $user->id,
        'blog_post_id' => $post->id,
    ]);

    $this->actingAs($user);

    Livewire::test(BlogPostComponent::class, ['post' => $post])
        ->assertSet('isRead', true);
});

test('blog list shows reads relationship for authenticated user', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->published()->create();

    UserBlogRead::create([
        'user_id' => $user->id,
        'blog_post_id' => $post->id,
    ]);

    $this->actingAs($user);

    Livewire::test(Blog::class)
        ->assertSee('✓ Read');
});

test('blog list does not show read badge for unauthenticated user', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->published()->create();

    UserBlogRead::create([
        'user_id' => $user->id,
        'blog_post_id' => $post->id,
    ]);

    Livewire::test(Blog::class)
        ->assertDontSee('✓ Read');
});

test('mark as read is idempotent for the same user and post', function () {
    $user = User::factory()->create();
    $post = BlogPost::factory()->published()->create();

    $this->actingAs($user);

    $component = Livewire::test(BlogPostComponent::class, ['post' => $post]);
    $component->call('markAsRead');
    $component->call('markAsRead');

    expect(UserBlogRead::where('user_id', $user->id)->where('blog_post_id', $post->id)->count())->toBe(1);
});
