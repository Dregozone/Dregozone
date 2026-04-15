<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated non-admin users are redirected to home', function () {
    $this->actingAs(User::factory()->create(['email' => 'user@example.com']));

    $this->get('/dashboard')->assertRedirect('/');
});

test('authenticated admin users are redirected to admin blog index', function () {
    $this->actingAs(User::factory()->create(['email' => 'aclearmonth@gmail.com']));

    $this->get('/dashboard')->assertRedirect(route('admin.blog.index'));
});
