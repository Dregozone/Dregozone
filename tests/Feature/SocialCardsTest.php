<?php

use App\Models\BlogPost;
use App\Models\UploadedImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
});

function uploadedImageOfSize(int $width, int $height): UploadedImage
{
    $image = imagecreatetruecolor($width, $height);
    ob_start();
    imagejpeg($image);

    return UploadedImage::create([
        'name' => "header-{$width}x{$height}.jpg",
        'imageable_id' => 0,
        'imageable_type' => 'library',
        'base64_data' => 'data:image/jpeg;base64,'.base64_encode((string) ob_get_clean()),
    ]);
}

function publishedPostWithImage(?UploadedImage $image, array $attributes = []): BlogPost
{
    $post = BlogPost::factory()->published()->create($attributes);
    $post->image()->associate($image)->save();

    return $post;
}

test('pages share the site card as their link preview', function () {
    $this->get('/')
        ->assertSuccessful()
        ->assertSee('<title>Anders Learmonth — Developer &amp; AI Tinkerer</title>', false)
        ->assertSee('<meta property="og:title" content="Anders Learmonth — Developer &amp; AI Tinkerer">', false)
        ->assertSee('<meta property="og:image" content="'.asset('images/social/card.jpg').'">', false)
        ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
        ->assertSee('<meta property="og:type" content="website">', false);

    expect(getimagesize(public_path('images/social/card.jpg')))
        ->{0}->toBe(1200)
        ->{1}->toBe(630)
        ->mime->toBe('image/jpeg');
});

test('a blog post previews with its own title, excerpt and header image', function () {
    $post = publishedPostWithImage(uploadedImageOfSize(1920, 1080), [
        'title' => 'Running ComfyUI on 8 GB',
        'excerpt' => 'Getting SDXL Turbo and Wan 2.2 to fit.',
    ]);

    $this->get(route('blog.post', $post))
        ->assertSuccessful()
        ->assertSee('<title>Anders Learmonth — Running ComfyUI on 8 GB</title>', false)
        ->assertSee('<meta property="og:title" content="Running ComfyUI on 8 GB">', false)
        ->assertSee('<meta property="og:description" content="Getting SDXL Turbo and Wan 2.2 to fit.">', false)
        ->assertSee('<meta property="og:type" content="article">', false)
        ->assertSee('article:published_time', false)
        ->assertSee(e($post->socialCardUrl()), false)
        ->assertDontSee('images/social/card.jpg', false);
});

test('the blog post card is the header image cropped to 1200 by 630', function () {
    $post = publishedPostWithImage(uploadedImageOfSize(1600, 1600 * 9 / 16));

    $response = $this->get(route('blog.post.card', $post))
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'image/jpeg');

    $size = getimagesizefromstring($response->getContent());

    expect([$size[0], $size[1]])->toBe([1200, 630])
        ->and(Storage::disk('local')->files('social-cards'))->toHaveCount(1);
});

test('a post falls back to the site card when its image is unsuitable or missing', function (?array $size) {
    $post = publishedPostWithImage($size ? uploadedImageOfSize(...$size) : null);

    expect($post->socialCardUrl())->toBeNull();

    $this->get(route('blog.post', $post))
        ->assertSee('<meta property="og:image" content="'.asset('images/social/card.jpg').'">', false);

    $this->get(route('blog.post.card', $post))->assertNotFound();
})->with([
    'too small' => [[400, 225]],
    'portrait' => [[1080, 1920]],
    'no image' => [null],
]);

test('drafts never expose a card', function () {
    $post = publishedPostWithImage(uploadedImageOfSize(1920, 1080), ['status' => 'draft']);

    $this->get(route('blog.post.card', $post))->assertNotFound();
});
