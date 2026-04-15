<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('isAdmin returns true for admin email', function () {
    $user = User::factory()->make(['email' => 'aclearmonth@gmail.com']);

    expect($user->isAdmin())->toBeTrue();
});

test('isAdmin returns false for non-admin email', function () {
    $user = User::factory()->make(['email' => 'notadmin@example.com']);

    expect($user->isAdmin())->toBeFalse();
});

test('initials returns correct initials for full name', function () {
    $user = User::factory()->make(['name' => 'John Doe']);

    expect($user->initials())->toBe('JD');
});

test('initials returns single initial for single name', function () {
    $user = User::factory()->make(['name' => 'Madonna']);

    expect($user->initials())->toBe('M');
});

test('initials handles triple names', function () {
    $user = User::factory()->make(['name' => 'John Paul Jones']);

    expect($user->initials())->toBe('JP');
});
