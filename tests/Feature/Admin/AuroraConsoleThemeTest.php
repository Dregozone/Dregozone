<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function auroraAdmin(): User
{
    return User::factory()->create(['email' => 'aclearmonth@gmail.com']);
}

test('admin screens render in the aurora console theme', function (string $routeName) {
    $this->actingAs(auroraAdmin())
        ->get(route($routeName))
        ->assertSuccessful()
        ->assertSee('theme-aurora dark', false)
        ->assertSee('console');
})->with([
    'admin.blog.index',
    'admin.blog.create',
    'admin.projects.index',
    'admin.tools.index',
    'admin.contact-messages.index',
    'admin.blog-engagement.index',
    'admin.newsletter-subscribers.index',
    'admin.images.index',
]);

test('settings screens use the aurora page header and highlight the current section', function (string $routeName, string $label) {
    $this->actingAs(User::factory()->create())
        ->get(route($routeName))
        ->assertSuccessful()
        ->assertSee('theme-aurora', false)
        ->assertSee('~/settings')
        ->assertSeeInOrder(['aria-current="page"', $label], false);
})->with([
    ['settings.profile', 'Profile'],
    ['settings.password', 'Password'],
    ['settings.newsletter', 'Newsletter'],
]);
