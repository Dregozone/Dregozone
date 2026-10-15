<?php

use App\Models\ContactMessage;
use App\Models\Login;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Auth\Events\Login as LoginEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['services.stats.token' => 'secret-token']);
    $this->travelTo(now()->setDate(2026, 10, 13)->setTime(12, 0));
});

function statsGet(string $date = '2026-10-12', string $token = 'secret-token')
{
    return test()->getJson('/api/stats/daily?date='.$date, ['Authorization' => 'Bearer '.$token]);
}

test('returns 401 with a wrong token', function () {
    statsGet(token: 'nope')->assertUnauthorized();
    test()->getJson('/api/stats/daily')->assertUnauthorized();
});

test('is off when no token is configured', function () {
    config(['services.stats.token' => null]);

    statsGet(token: '')->assertNotFound();
});

test('defaults to yesterday in Europe/London', function () {
    test()->getJson('/api/stats/daily', ['Authorization' => 'Bearer '.'secret-token'])
        ->assertOk()
        ->assertJsonPath('date', '2026-10-12')
        ->assertJsonPath('app', 'Dregozone');
});

test('counts signups, newsletter and contact messages for the day', function () {
    // 2026-10-12 in London (BST) is 2026-10-11 23:00 UTC to 2026-10-12 22:59:59 UTC
    User::factory()->create(['created_at' => '2026-10-12 10:00:00']);
    User::factory()->create(['created_at' => '2026-10-11 22:59:59']);
    User::factory()->create(['created_at' => '2026-10-12 23:00:00']);

    NewsletterSubscriber::create(['email' => 'a@example.com', 'is_subscribed' => true, 'subscribed_at' => now()])
        ->forceFill(['created_at' => '2026-10-12 09:00:00'])->save();
    NewsletterSubscriber::create(['email' => 'b@example.com', 'is_subscribed' => false, 'subscribed_at' => now(), 'unsubscribed_at' => now()])
        ->forceFill(['created_at' => '2026-10-12 09:00:00'])->save();

    ContactMessage::query()->insert([
        'name' => 'N', 'email' => 'n@example.com', 'subject' => 's', 'message' => 'm',
        'created_at' => '2026-10-12 09:00:00', 'updated_at' => '2026-10-12 09:00:00',
    ]);

    statsGet()->assertOk()
        ->assertJsonPath('signups', 1)
        ->assertJsonPath('users.total', 3)
        ->assertJsonPath('newsletter_signups', 1)
        ->assertJsonPath('contact_messages', 1);
});

test('counts total and unique logins', function () {
    $users = User::factory()->count(2)->create();

    foreach ([$users[0], $users[0], $users[1]] as $user) {
        event(new LoginEvent('web', $user, false));
    }
    Login::query()->update(['created_at' => '2026-10-12 10:00:00']);

    statsGet()->assertOk()
        ->assertJsonPath('logins.total', 3)
        ->assertJsonPath('logins.unique', 2);
});

test('computes active windows from last_active_at', function () {
    User::factory()->create(['last_active_at' => now()->subDays(2)]);
    User::factory()->create(['last_active_at' => now()->subDays(10)]);
    User::factory()->create(['last_active_at' => now()->subDays(40)]);
    User::factory()->create();

    statsGet()->assertOk()
        ->assertJsonPath('users.active_7d', 1)
        ->assertJsonPath('users.active_30d', 2);
});

test('last_active_at is updated at most once an hour', function () {
    $user = User::factory()->create();

    test()->actingAs($user)->get('/privacy-policy');
    $first = $user->fresh()->last_active_at;
    expect($first)->not->toBeNull();

    $this->travel(30)->minutes();
    test()->actingAs($user)->get('/privacy-policy');
    expect($user->fresh()->last_active_at->equalTo($first))->toBeTrue();

    $this->travel(31)->minutes();
    test()->actingAs($user)->get('/privacy-policy');
    expect($user->fresh()->last_active_at->greaterThan($first))->toBeTrue();
});
