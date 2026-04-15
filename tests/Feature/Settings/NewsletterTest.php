<?php

use App\Livewire\Settings\Newsletter;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('newsletter settings page is displayed', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/settings/newsletter')->assertOk();
});

test('newsletter page shows unsubscribed status when no subscriber record exists', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    $this->actingAs($user);

    Livewire::test(Newsletter::class)
        ->assertSet('subscriptionStatus', 'unsubscribed');
});

test('newsletter page shows subscribed status when subscriber record is active', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    NewsletterSubscriber::create([
        'email' => 'test@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(Newsletter::class)
        ->assertSet('subscriptionStatus', 'subscribed');
});

test('changing subscription status to unsubscribed unsubscribes the user', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    NewsletterSubscriber::create([
        'email' => 'test@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(Newsletter::class)
        ->set('subscriptionStatus', 'unsubscribed')
        ->assertSet('saved', true);

    expect(NewsletterSubscriber::where('email', 'test@example.com')->first()->is_subscribed)->toBeFalse();
});

test('changing subscription status to subscribed subscribes the user', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    $this->actingAs($user);

    Livewire::test(Newsletter::class)
        ->set('subscriptionStatus', 'subscribed')
        ->assertSet('saved', true);

    expect(NewsletterSubscriber::where('email', 'test@example.com')->first()->is_subscribed)->toBeTrue();
});
