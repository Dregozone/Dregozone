<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Exceptions;
use Livewire\Features\SupportFileUploads\MissingFileUploadsTraitException;
use Livewire\Livewire;
use Livewire\Mechanisms\HandleRequests\EndpointResolver;

uses(RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->call('register');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('registration attempts are rate limited per ip address', function () {
    $component = Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'not-an-email')
        ->set('password', 'password');

    foreach (range(1, 5) as $attempt) {
        $component->call('register')->assertHasErrors(['email' => 'email']);
    }

    $component
        ->set('email', 'test@example.com')
        ->call('register')
        ->assertHasErrors('email')
        ->assertSee('Too many');

    expect(User::count())->toBe(0);
    $this->assertGuest();
});

test('forged file upload calls on the register component return a bad request without being reported', function () {
    Exceptions::fake();

    preg_match('/wire:snapshot="([^"]+)"/', $this->get('/register')->getContent(), $matches);

    $response = $this->withHeaders(['X-Livewire' => 'true'])
        ->postJson(EndpointResolver::updatePath(), [
            'components' => [[
                'snapshot' => html_entity_decode($matches[1]),
                'updates' => [],
                'calls' => [[
                    'method' => '_startUpload',
                    'params' => ['avatar', [['name' => 'shell.php', 'size' => 100, 'type' => 'image/png']], false],
                ]],
            ]],
        ]);

    $response->assertBadRequest();
    Exceptions::assertNotReported(MissingFileUploadsTraitException::class);
});
