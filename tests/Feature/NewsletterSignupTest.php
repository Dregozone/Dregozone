<?php

use App\Livewire\NewsletterSignup;
use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('newsletter signup form submits with valid email', function () {
    Livewire::test(NewsletterSignup::class)
        ->set('email', 'subscriber@example.com')
        ->call('subscribe')
        ->assertHasNoErrors();

    expect(NewsletterSubscriber::where('email', 'subscriber@example.com')->exists())->toBeTrue();
});

test('newsletter signup sets subscribed to true after success', function () {
    Livewire::test(NewsletterSignup::class)
        ->set('email', 'subscriber@example.com')
        ->call('subscribe')
        ->assertSet('subscribed', true);
});

test('newsletter signup rejects invalid email', function () {
    Livewire::test(NewsletterSignup::class)
        ->set('email', 'not-a-valid-email')
        ->call('subscribe')
        ->assertHasErrors(['email']);
});

test('newsletter signup rejects duplicate email', function () {
    NewsletterSubscriber::create([
        'email' => 'existing@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
    ]);

    Livewire::test(NewsletterSignup::class)
        ->set('email', 'existing@example.com')
        ->call('subscribe')
        ->assertHasErrors(['email']);
});

test('newsletter signup creates subscriber with subscribed_at', function () {
    Livewire::test(NewsletterSignup::class)
        ->set('email', 'timestamped@example.com')
        ->call('subscribe');

    $subscriber = NewsletterSubscriber::where('email', 'timestamped@example.com')->first();
    expect($subscriber->subscribed_at)->not->toBeNull();
});

test('newsletter signup auto-generates secret_key', function () {
    Livewire::test(NewsletterSignup::class)
        ->set('email', 'secretkey@example.com')
        ->call('subscribe');

    $subscriber = NewsletterSubscriber::where('email', 'secretkey@example.com')->first();
    expect($subscriber->secret_key)->not->toBeEmpty();
});

test('newsletter signup with name stores name', function () {
    Livewire::test(NewsletterSignup::class)
        ->set('email', 'named@example.com')
        ->set('name', 'Jane Doe')
        ->call('subscribe');

    expect(NewsletterSubscriber::where('email', 'named@example.com')->first()->name)->toBe('Jane Doe');
});

test('newsletter signup with blank name still works', function () {
    Livewire::test(NewsletterSignup::class)
        ->set('email', 'noname@example.com')
        ->set('name', '')
        ->call('subscribe')
        ->assertHasNoErrors();

    expect(NewsletterSubscriber::where('email', 'noname@example.com')->exists())->toBeTrue();
});

test('unsubscribe with valid email and key succeeds', function () {
    $subscriber = NewsletterSubscriber::create([
        'email' => 'unsub@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
        'secret_key' => 'valid-secret-key-string-that-is-long-enough-for-testing-purposes-here',
    ]);

    $this->get('/emails/unsubscribe?email=unsub@example.com&key=valid-secret-key-string-that-is-long-enough-for-testing-purposes-here')
        ->assertSuccessful();

    expect($subscriber->fresh()->is_subscribed)->toBeFalse();
});

test('unsubscribe with invalid key shows error', function () {
    NewsletterSubscriber::create([
        'email' => 'validuser@example.com',
        'is_subscribed' => true,
        'subscribed_at' => now(),
        'secret_key' => 'the-real-secret-key-value',
    ]);

    $response = $this->get('/emails/unsubscribe?email=validuser@example.com&key=wrong-key');

    $response->assertSuccessful();
    $response->assertSee('incorrect');
});

test('unsubscribe with missing email or key shows error', function () {
    $response = $this->get('/emails/unsubscribe');

    $response->assertSuccessful();
    $response->assertSee('Invalid unsubscribe link');
});

test('unsubscribe when already unsubscribed shows message', function () {
    $subscriber = NewsletterSubscriber::create([
        'email' => 'already@example.com',
        'is_subscribed' => false,
        'subscribed_at' => now()->subMonth(),
        'unsubscribed_at' => now()->subDay(),
        'secret_key' => 'already-unsubscribed-key-that-is-long-enough-for-testing',
    ]);

    $response = $this->get('/emails/unsubscribe?email=already@example.com&key=already-unsubscribed-key-that-is-long-enough-for-testing');

    $response->assertSuccessful();
    $response->assertSee('already unsubscribed');
});

test('unsubscribe with non-existent email shows error', function () {
    $response = $this->get('/emails/unsubscribe?email=ghost@example.com&key=any-key');

    $response->assertSuccessful();
    $response->assertSee('incorrect');
});
