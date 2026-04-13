<?php

use App\Livewire\Admin\BlogPostForm;
use App\Models\BlogPost;
use App\Models\UploadedImage;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function blogAdminUser(): User
{
    return User::factory()->create(['email' => 'aclearmonth@gmail.com']);
}

test('blog post form stores base64 image as uploaded image record', function () {
    $this->actingAs(blogAdminUser());

    $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

    $pendingImage = UploadedImage::create([
        'imageable_id' => 0,
        'imageable_type' => 'pending',
        'base64_data' => $base64,
    ]);

    Livewire::test(BlogPostForm::class)
        ->set('title', 'My Base64 Blog Post')
        ->set('excerpt', 'A short excerpt for the blog post that is long enough.')
        ->set('content', str_repeat('Blog content here. ', 5))
        ->set('status', 'draft')
        ->set('pendingImageId', $pendingImage->id)
        ->call('save')
        ->assertHasNoErrors();

    $post = BlogPost::where('title', 'My Base64 Blog Post')->first();
    expect($post)->not->toBeNull();
    expect($post->uploadedImage)->not->toBeNull();
    expect($post->uploadedImage->base64_data)->toBe($base64);
});

test('blog post form updates uploaded image when editing', function () {
    $this->actingAs(blogAdminUser());

    $post = BlogPost::factory()->draft()->create();
    $original = 'data:image/png;base64,ORIGINAL';
    $post->uploadedImage()->create(['base64_data' => $original]);

    $updated = 'data:image/png;base64,UPDATED';

    $pendingImage = UploadedImage::create([
        'imageable_id' => 0,
        'imageable_type' => 'pending',
        'base64_data' => $updated,
    ]);

    Livewire::test(BlogPostForm::class, ['postId' => $post->id])
        ->set('pendingImageId', $pendingImage->id)
        ->call('save')
        ->assertHasNoErrors();

    expect($post->fresh()->uploadedImage->base64_data)->toBe($updated);
});

test('blog post form deletes uploaded image when base64 is cleared while editing', function () {
    $this->actingAs(blogAdminUser());

    $post = BlogPost::factory()->draft()->create();
    $post->uploadedImage()->create(['base64_data' => 'data:image/png;base64,SOMEDATA']);

    Livewire::test(BlogPostForm::class, ['postId' => $post->id])
        ->set('pendingImageId', null)
        ->call('save')
        ->assertHasNoErrors();

    expect($post->fresh()->uploadedImage)->toBeNull();
});
