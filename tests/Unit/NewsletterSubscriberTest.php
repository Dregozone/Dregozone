<?php

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('secret key is auto generated on create', function () {
    $subscriber = NewsletterSubscriber::create([
        'email' => 'auto@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
    ]);

    expect($subscriber->secret_key)->not->toBeEmpty();
});

test('secret key is not overridden if already set', function () {
    $subscriber = NewsletterSubscriber::create([
        'email' => 'preset@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
        'secret_key' => 'my-preset-secret-key',
    ]);

    expect($subscriber->secret_key)->toBe('my-preset-secret-key');
});

test('unsubscribe sets is subscribed to false and sets unsubscribed at', function () {
    $subscriber = NewsletterSubscriber::create([
        'email' => 'unsub@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
    ]);

    $subscriber->unsubscribe();

    expect($subscriber->fresh()->is_subscribed)->toBeFalse();
    expect($subscriber->fresh()->unsubscribed_at)->not->toBeNull();
});

test('subscribed scope returns only subscribed subscribers', function () {
    NewsletterSubscriber::create(['email' => 'active@example.com', 'is_subscribed' => true, 'subscribed_at' => now()]);
    NewsletterSubscriber::create([
        'email' => 'opted-out@example.com',
        'is_subscribed' => false,
        'subscribed_at' => now()->subMonth(),
        'unsubscribed_at' => now(),
    ]);

    $subscribed = NewsletterSubscriber::subscribed()->pluck('email');

    expect($subscribed)->toContain('active@example.com');
    expect($subscribed)->not->toContain('opted-out@example.com');
});
