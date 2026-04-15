<?php

use App\Livewire\Settings\Newsletter;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * Subscriber User Tests
 *
 * A "subscriber" is an authenticated user who has opted in to the newsletter.
 * Subscribers can manage their subscription via settings but have no access
 * to any admin routes — only the single admin (aclearmonth@gmail.com) may do so.
 */

// ── Newsletter subscription management ────────────────────────────────────────

test('subscriber can see their subscribed status on the settings page', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);

    NewsletterSubscriber::create([
        'email' => 'subscriber@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(Newsletter::class)
        ->assertSet('subscriptionStatus', 'subscribed');
});

test('subscriber can unsubscribe via settings page', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);

    NewsletterSubscriber::create([
        'email' => 'subscriber@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(Newsletter::class)
        ->set('subscriptionStatus', 'unsubscribed')
        ->assertSet('saved', true);

    expect(NewsletterSubscriber::where('email', 'subscriber@example.com')->first()->is_subscribed)->toBeFalse();
});

test('subscriber can re-subscribe via settings page', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);

    NewsletterSubscriber::create([
        'email' => 'subscriber@example.com',
        'is_subscribed' => false,
        'subscribed_at' => now()->subMonth(),
        'unsubscribed_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(Newsletter::class)
        ->set('subscriptionStatus', 'subscribed')
        ->assertSet('saved', true);

    expect(NewsletterSubscriber::where('email', 'subscriber@example.com')->first()->is_subscribed)->toBeTrue();
});

test('non-subscribed authenticated user sees unsubscribed status by default', function () {
    $user = User::factory()->create(['email' => 'notsub@example.com']);

    $this->actingAs($user);

    Livewire::test(Newsletter::class)
        ->assertSet('subscriptionStatus', 'unsubscribed');
});

// ── Admin route access — subscriber must be blocked ───────────────────────────

test('subscriber is forbidden from admin blog list', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/admin/blog')->assertForbidden();
});

test('subscriber is forbidden from admin blog create', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/admin/blog/create')->assertForbidden();
});

test('subscriber is forbidden from admin projects list', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/admin/projects')->assertForbidden();
});

test('subscriber is forbidden from admin contact messages', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/admin/contact-messages')->assertForbidden();
});

test('subscriber is forbidden from admin newsletter subscribers', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/admin/newsletter-subscribers')->assertForbidden();
});

test('subscriber is forbidden from newsletter subscriber export', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/admin/newsletter-subscribers/export')->assertForbidden();
});

test('subscriber is forbidden from image library', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/admin/images')->assertForbidden();
});

test('subscriber is forbidden from image converter', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/admin/image-converter')->assertForbidden();
});

test('subscriber is forbidden from uploading images', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->postJson(route('admin.images.upload'), [
        'base64_data' => 'data:image/png;base64,abc123',
    ])->assertForbidden();
});

// ── Settings routes remain accessible to subscribers ──────────────────────────

test('subscriber can access their profile settings', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/settings/profile')->assertSuccessful();
});

test('subscriber can access password settings', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/settings/password')->assertSuccessful();
});

test('subscriber can access newsletter settings', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/settings/newsletter')->assertSuccessful();
});

test('subscriber is redirected to home from the dashboard', function () {
    $user = User::factory()->create(['email' => 'subscriber@example.com']);
    $this->actingAs($user);

    $this->get('/dashboard')->assertRedirect('/');
});
