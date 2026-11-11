<?php

use App\Livewire\Auth\VerifyEmail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('email verification screen can be rendered', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/verify-email');

    $response->assertStatus(200);
});

test('email can be verified', function () {
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
});

test('email is not verified with invalid hash', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('verification emails can be resent', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    Livewire::actingAs($user)
        ->test(VerifyEmail::class)
        ->call('sendVerification')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, VerifyEmailNotification::class);
});

test('verification email resends are limited to three per ten minutes', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();
    $component = Livewire::actingAs($user)->test(VerifyEmail::class);

    foreach (range(1, 3) as $attempt) {
        $component->call('sendVerification')->assertHasNoErrors();
    }

    $component->call('sendVerification')
        ->assertHasErrors('verification')
        ->assertSee('Too many');

    Notification::assertSentToTimes($user, VerifyEmailNotification::class, 3);
});
