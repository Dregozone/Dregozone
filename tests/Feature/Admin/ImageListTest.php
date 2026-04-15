<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected from image library', function () {
    $this->get('/admin/images')->assertRedirect('/login');
});

test('authenticated user can view image library', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get('/admin/images')->assertSuccessful();
});

test('authenticated user can view image converter', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get('/admin/image-converter')->assertSuccessful();
});

test('guests are redirected from image converter', function () {
    $this->get('/admin/image-converter')->assertRedirect('/login');
});
