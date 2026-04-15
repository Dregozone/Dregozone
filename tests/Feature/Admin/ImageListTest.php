<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected from image library', function () {
    $this->get('/admin/images')->assertRedirect('/login');
});

test('non-admin authenticated user is forbidden from image library', function () {
    $this->actingAs(User::factory()->create(['email' => 'user@example.com']));

    $this->get('/admin/images')->assertForbidden();
});

test('admin can view image library', function () {
    $user = User::factory()->create(['email' => 'aclearmonth@gmail.com']);
    $this->actingAs($user);

    $this->get('/admin/images')->assertSuccessful();
});

test('non-admin authenticated user is forbidden from image converter', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    $this->actingAs($user);

    $this->get('/admin/image-converter')->assertForbidden();
});

test('admin can view image converter', function () {
    $user = User::factory()->create(['email' => 'aclearmonth@gmail.com']);
    $this->actingAs($user);

    $this->get('/admin/image-converter')->assertSuccessful();
});

test('guests are redirected from image converter', function () {
    $this->get('/admin/image-converter')->assertRedirect('/login');
});
