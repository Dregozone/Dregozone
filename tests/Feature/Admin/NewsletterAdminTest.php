<?php

use App\Livewire\Admin\NewsletterSubscriberList;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function newsletterAdminUser(): User
{
    return User::factory()->create(['email' => 'aclearmonth@gmail.com']);
}

test('guests are redirected from admin newsletter subscribers', function () {
    $this->get('/admin/newsletter-subscribers')->assertRedirect('/login');
});

test('non-admin authenticated user is forbidden from admin newsletter subscribers', function () {
    $this->actingAs(User::factory()->create(['email' => 'user@example.com']));

    $this->get('/admin/newsletter-subscribers')->assertForbidden();
});

test('admin can view admin newsletter subscribers', function () {
    $this->actingAs(newsletterAdminUser());

    $this->get('/admin/newsletter-subscribers')->assertSuccessful();
});

test('admin newsletter list shows subscribers', function () {
    $this->actingAs(newsletterAdminUser());

    NewsletterSubscriber::create([
        'email' => 'listed@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
    ]);

    Livewire::test(NewsletterSubscriberList::class)->assertSee('listed@example.com');
});

test('admin newsletter list can search by email', function () {
    $this->actingAs(newsletterAdminUser());

    NewsletterSubscriber::create(['email' => 'find.me@example.com', 'is_subscribed' => true, 'subscribed_at' => now()]);
    NewsletterSubscriber::create(['email' => 'hidden@example.com', 'is_subscribed' => true, 'subscribed_at' => now()]);

    Livewire::test(NewsletterSubscriberList::class)
        ->set('search', 'find.me')
        ->assertSee('find.me@example.com')
        ->assertDontSee('hidden@example.com');
});

test('admin newsletter list shows active subscription stats', function () {
    $this->actingAs(newsletterAdminUser());

    NewsletterSubscriber::create(['email' => 'active@example.com', 'is_subscribed' => true, 'subscribed_at' => now()]);

    Livewire::test(NewsletterSubscriberList::class)->assertViewHas('totalActive');
});

test('admin newsletter list filters by subscribed status', function () {
    $this->actingAs(newsletterAdminUser());

    NewsletterSubscriber::create(['email' => 'active-member@example.com', 'is_subscribed' => true, 'subscribed_at' => now()]);
    NewsletterSubscriber::create([
        'email' => 'opted-out@example.com',
        'is_subscribed' => false,
        'subscribed_at' => now()->subMonth(),
        'unsubscribed_at' => now(),
    ]);

    Livewire::test(NewsletterSubscriberList::class)
        ->set('status', 'subscribed')
        ->assertSee('active-member@example.com')
        ->assertDontSee('opted-out@example.com');
});

test('admin newsletter list filters by unsubscribed status', function () {
    $this->actingAs(newsletterAdminUser());

    NewsletterSubscriber::create(['email' => 'active-member@example.com', 'is_subscribed' => true, 'subscribed_at' => now()]);
    NewsletterSubscriber::create([
        'email' => 'opted-out@example.com',
        'is_subscribed' => false,
        'subscribed_at' => now()->subMonth(),
        'unsubscribed_at' => now(),
    ]);

    Livewire::test(NewsletterSubscriberList::class)
        ->set('status', 'unsubscribed')
        ->assertSee('opted-out@example.com')
        ->assertDontSee('active-member@example.com');
});

test('export active subscribers returns json response', function () {
    $this->actingAs(newsletterAdminUser());

    $this->get('/admin/newsletter-subscribers/export')->assertSuccessful()->assertJson([]);
});

test('export json contains only subscribed emails', function () {
    $this->actingAs(newsletterAdminUser());

    NewsletterSubscriber::create(['email' => 'active@example.com', 'is_subscribed' => true, 'subscribed_at' => now()]);
    NewsletterSubscriber::create([
        'email' => 'inactive@example.com',
        'is_subscribed' => false,
        'subscribed_at' => now()->subMonth(),
        'unsubscribed_at' => now(),
    ]);

    $response = $this->get('/admin/newsletter-subscribers/export');

    $data = $response->json('active_subscribers');
    expect($data)->toContain('active@example.com');
    expect($data)->not->toContain('inactive@example.com');
});

test('export guest is redirected to login', function () {
    $this->get('/admin/newsletter-subscribers/export')->assertRedirect('/login');
});

test('non-admin authenticated user is forbidden from newsletter export', function () {
    $this->actingAs(User::factory()->create(['email' => 'user@example.com']));

    $this->get('/admin/newsletter-subscribers/export')->assertForbidden();
});
