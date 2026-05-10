<?php

use App\Livewire\Admin\BlogEngagement;
use App\Models\BlogPost;
use App\Models\User;
use App\Models\UserBlogRead;
use App\Models\UserBlogView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function blogEngagementAdminUser(): User
{
    return User::factory()->create(['email' => 'aclearmonth@gmail.com']);
}

function createBlogView(?User $user = null, ?BlogPost $post = null, array $attributes = []): UserBlogView
{
    $user ??= User::factory()->create();
    $post ??= BlogPost::factory()->published()->create();

    return UserBlogView::query()->forceCreate(array_merge([
        'user_id' => $user->id,
        'blog_post_id' => $post->id,
        'created_at' => now(),
        'updated_at' => now(),
    ], $attributes));
}

function createGuestBlogView(?BlogPost $post = null, array $attributes = []): UserBlogView
{
    $post ??= BlogPost::factory()->published()->create();

    return UserBlogView::query()->forceCreate(array_merge([
        'user_id' => null,
        'blog_post_id' => $post->id,
        'created_at' => now(),
        'updated_at' => now(),
    ], $attributes));
}

function createBlogRead(?User $user = null, ?BlogPost $post = null, array $attributes = []): UserBlogRead
{
    $user ??= User::factory()->create();
    $post ??= BlogPost::factory()->published()->create();

    return UserBlogRead::query()->forceCreate(array_merge([
        'user_id' => $user->id,
        'blog_post_id' => $post->id,
        'created_at' => now(),
        'updated_at' => now(),
    ], $attributes));
}

test('guests are redirected from admin blog engagement', function () {
    $this->get('/admin/blog-engagement')->assertRedirect('/login');
});

test('non-admin authenticated user is forbidden from admin blog engagement', function () {
    /** @var User $user */
    $user = User::factory()->create(['email' => 'user@example.com']);

    $this->actingAs($user);

    $this->get('/admin/blog-engagement')->assertForbidden();
});

test('admin can view admin blog engagement page', function () {
    $this->actingAs(blogEngagementAdminUser());

    $this->get('/admin/blog-engagement')->assertSuccessful();
});

test('blog engagement summaries respect the selected post filter', function () {
    $this->actingAs(blogEngagementAdminUser());

    $includedPost = BlogPost::factory()->published()->create(['title' => 'Included Post']);
    $excludedPost = BlogPost::factory()->published()->create(['title' => 'Excluded Post']);

    createBlogView(User::factory()->create(), $includedPost);
    createBlogView(User::factory()->create(), $includedPost);
    createBlogRead(User::factory()->create(), $includedPost);

    createBlogView(User::factory()->create(), $excludedPost);
    createBlogRead(User::factory()->create(), $excludedPost);

    Livewire::test(BlogEngagement::class)
        ->set('postId', (string) $includedPost->id)
        ->assertViewHas('viewStats', fn (array $stats): bool => $stats['total'] === 2 && $stats['users'] === 2 && $stats['posts'] === 1)
        ->assertViewHas('readStats', fn (array $stats): bool => $stats['total'] === 1 && $stats['users'] === 1 && $stats['posts'] === 1);
});

test('blog engagement search filters both views and reads by reader details', function () {
    $this->actingAs(blogEngagementAdminUser());

    $matchingUser = User::factory()->create(['name' => 'Mila Reader', 'email' => 'mila@example.com']);
    $otherUser = User::factory()->create(['name' => 'Noah Hidden', 'email' => 'noah@example.com']);

    createBlogView($matchingUser);
    createBlogRead($matchingUser);
    createBlogView($otherUser);
    createBlogRead($otherUser);

    Livewire::test(BlogEngagement::class)
        ->set('search', 'Mila')
        ->assertSee('Mila Reader')
        ->assertDontSee('Noah Hidden');
});

test('view sorting happens before the top 25 limit is applied', function () {
    $this->actingAs(blogEngagementAdminUser());

    $post = BlogPost::factory()->published()->create();

    createBlogView(
        User::factory()->create(['name' => 'Aardvark Reader', 'email' => 'aardvark@example.com']),
        $post,
        ['created_at' => now()->subDays(60), 'updated_at' => now()->subDays(60)],
    );

    foreach (range(1, 25) as $index) {
        createBlogView(
            User::factory()->create([
                'name' => 'Zulu Reader '.$index,
                'email' => 'zulu'.$index.'@example.com',
            ]),
            $post,
            ['created_at' => now()->subMinutes($index), 'updated_at' => now()->subMinutes($index)],
        );
    }

    Livewire::test(BlogEngagement::class)
        ->call('sortViewsBy', 'reader')
        ->assertSee('Aardvark Reader');
});

test('read sorting happens before the top 25 limit is applied', function () {
    $this->actingAs(blogEngagementAdminUser());

    $post = BlogPost::factory()->published()->create();

    createBlogRead(
        User::factory()->create(['name' => 'Amber Reader', 'email' => 'amber@example.com']),
        $post,
        ['created_at' => now()->subDays(45), 'updated_at' => now()->subDays(45)],
    );

    foreach (range(1, 25) as $index) {
        createBlogRead(
            User::factory()->create([
                'name' => 'Zeta Reader '.$index,
                'email' => 'zeta'.$index.'@example.com',
            ]),
            $post,
            ['created_at' => now()->subMinutes($index), 'updated_at' => now()->subMinutes($index)],
        );
    }

    Livewire::test(BlogEngagement::class)
        ->call('sortReadsBy', 'reader')
        ->assertSee('Amber Reader');
});

test('guest views are recorded and appear in the view activity table', function () {
    $this->actingAs(blogEngagementAdminUser());

    $post = BlogPost::factory()->published()->create(['title' => 'Popular Post']);
    createGuestBlogView($post);

    Livewire::test(BlogEngagement::class)
        ->assertSee('Guest')
        ->assertViewHas('viewStats', fn (array $stats): bool => $stats['total'] === 1);
});

test('guest views are counted in total views but not in unique viewers', function () {
    $this->actingAs(blogEngagementAdminUser());

    $post = BlogPost::factory()->published()->create();
    $user = User::factory()->create();

    createBlogView($user, $post);
    createGuestBlogView($post);
    createGuestBlogView($post);

    Livewire::test(BlogEngagement::class)
        ->assertViewHas('viewStats', fn (array $stats): bool => $stats['total'] === 3 && $stats['users'] === 1);
});

test('guest views appear when filtering by post', function () {
    $this->actingAs(blogEngagementAdminUser());

    $post = BlogPost::factory()->published()->create();
    createGuestBlogView($post);

    Livewire::test(BlogEngagement::class)
        ->set('postId', (string) $post->id)
        ->assertViewHas('viewStats', fn (array $stats): bool => $stats['total'] === 1);
});

test('guest views appear when searching by post title', function () {
    $this->actingAs(blogEngagementAdminUser());

    $post = BlogPost::factory()->published()->create(['title' => 'Searchable Title']);
    createGuestBlogView($post);

    Livewire::test(BlogEngagement::class)
        ->set('search', 'Searchable Title')
        ->assertSee('Guest');
});
