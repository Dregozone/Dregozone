<?php

use App\Livewire\Admin\ImageList;
use App\Models\UploadedImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Testing\TestResponse;
use Livewire\Livewire;
use Livewire\Mechanisms\HandleRequests\EndpointResolver;

uses(RefreshDatabase::class);

/**
 * Render the image library as the given user and return its signed Livewire snapshot.
 */
function imageListSnapshotFor(User $user): string
{
    $html = test()->actingAs($user)->get('/admin/images')->assertSuccessful()->getContent();

    preg_match('/wire:snapshot="([^"]+)"[^>]*wire:name="admin\.image-list"|wire:name="admin\.image-list"[^>]*wire:snapshot="([^"]+)"/', $html, $matches);

    return html_entity_decode($matches[1] ?: $matches[2]);
}

/**
 * Send a Livewire update request calling the given method on the snapshot's component.
 */
function callLivewireMethod(string $snapshot, string $method): TestResponse
{
    return test()->withHeaders(['X-Livewire' => 'true'])
        ->postJson(EndpointResolver::updatePath(), [
            'components' => [[
                'snapshot' => $snapshot,
                'updates' => [],
                'calls' => [['method' => $method, 'params' => []]],
            ]],
        ]);
}

/**
 * A relative signed URL for Livewire's temporary upload endpoint.
 */
function livewireUploadUrl(): string
{
    return URL::temporarySignedRoute('livewire.upload-file', now()->addMinutes(5), absolute: false);
}

test('admin can call livewire actions on admin components', function () {
    $admin = User::factory()->create(['email' => 'aclearmonth@gmail.com']);

    callLivewireMethod(imageListSnapshotFor($admin), 'toggleUploadForm')->assertSuccessful();
});

test('a replayed admin snapshot is rejected once the user is no longer an admin', function () {
    $snapshot = imageListSnapshotFor(User::factory()->create(['email' => 'aclearmonth@gmail.com']));

    $this->actingAs(User::factory()->create(['email' => 'user@example.com']));

    callLivewireMethod($snapshot, 'toggleUploadForm')->assertForbidden();
});

test('non-admins cannot save images even when reaching the component directly', function () {
    Storage::fake('tmp-for-tests');

    Livewire::actingAs(User::factory()->create(['email' => 'user@example.com']))
        ->test(ImageList::class)
        ->set('name', 'Sneaky')
        ->set('photo', UploadedFile::fake()->image('photo.png'))
        ->call('save')
        ->assertForbidden();

    expect(UploadedImage::count())->toBe(0);
});

test('temporary uploads reject non-image files before storing them', function () {
    Storage::fake('tmp-for-tests');

    $this->actingAs(User::factory()->create(['email' => 'aclearmonth@gmail.com']))
        ->postJson(livewireUploadUrl(), ['files' => [UploadedFile::fake()->create('shell.php', 10, 'application/x-php')]])
        ->assertUnprocessable();

    expect(Storage::disk('tmp-for-tests')->allFiles())->toBeEmpty();
});

test('temporary uploads reject images over 2MB', function () {
    Storage::fake('tmp-for-tests');

    $this->actingAs(User::factory()->create(['email' => 'aclearmonth@gmail.com']))
        ->postJson(livewireUploadUrl(), ['files' => [UploadedFile::fake()->image('huge.png')->size(3000)]])
        ->assertUnprocessable();

    expect(Storage::disk('tmp-for-tests')->allFiles())->toBeEmpty();
});

test('temporary uploads are limited to 10 requests per minute', function () {
    Storage::fake('tmp-for-tests');

    $this->actingAs(User::factory()->create(['email' => 'aclearmonth@gmail.com']));

    foreach (range(1, 10) as $attempt) {
        $this->postJson(livewireUploadUrl(), ['files' => [UploadedFile::fake()->image("photo{$attempt}.png")]])
            ->assertSuccessful();
    }

    $this->postJson(livewireUploadUrl(), ['files' => [UploadedFile::fake()->image('photo11.png')]])
        ->assertTooManyRequests();
});
