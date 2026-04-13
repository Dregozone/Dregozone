<?php

use App\Models\UploadedImage;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests cannot upload images', function () {
    $this->postJson(route('admin.images.upload'), [
        'base64_data' => 'data:image/png;base64,abc123',
    ])->assertUnauthorized();
});

test('authenticated admin can upload a pending image', function () {
    $user = User::factory()->create(['email' => 'aclearmonth@gmail.com']);
    $this->actingAs($user);

    $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

    $response = $this->postJson(route('admin.images.upload'), [
        'base64_data' => $base64,
    ]);

    $response->assertOk()->assertJsonStructure(['id']);

    $id = $response->json('id');
    $image = UploadedImage::find($id);

    expect($image)->not->toBeNull();
    expect($image->imageable_type)->toBe('pending');
    expect($image->imageable_id)->toBe(0);
    expect($image->base64_data)->toBe($base64);
});

test('image upload rejects non-image base64 data', function () {
    $user = User::factory()->create(['email' => 'aclearmonth@gmail.com']);
    $this->actingAs($user);

    $this->postJson(route('admin.images.upload'), [
        'base64_data' => 'not-a-valid-image-data-uri',
    ])->assertStatus(422);
});

test('image upload requires base64_data field', function () {
    $user = User::factory()->create(['email' => 'aclearmonth@gmail.com']);
    $this->actingAs($user);

    $this->postJson(route('admin.images.upload'), [])->assertUnprocessable();
});
