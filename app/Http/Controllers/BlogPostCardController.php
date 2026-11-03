<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

/**
 * Serves a published post's header image as a 1200×630 JPEG link preview card.
 *
 * Images are stored as data URIs, which LinkedIn, Slack and X cannot fetch, so the card is
 * rendered once per image version and kept on the local disk.
 */
class BlogPostCardController extends Controller
{
    public const WIDTH = 1200;

    public const HEIGHT = 630;

    public function __invoke(BlogPost $post): Response
    {
        $post->loadMissing('image');

        abort_unless($post->status === 'published' && $post->published_at?->isPast(), 404);
        abort_unless($post->image?->isSuitableForSocialCard(), 404);

        $path = sprintf('social-cards/post-%d-image-%d-%d.jpg', $post->id, $post->image->id, $post->image->updated_at?->timestamp ?? 0);
        $disk = Storage::disk('local');

        if (! $disk->exists($path)) {
            $disk->put($path, $this->renderCard($post->image->binary()));
        }

        return response($disk->get($path), 200, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }

    /**
     * Cover-crops the source to the card size, centred, the way object-fit: cover would.
     */
    protected function renderCard(string $source): string
    {
        $image = imagecreatefromstring($source);
        $sourceWidth = imagesx($image);
        $sourceHeight = imagesy($image);

        $scale = max(self::WIDTH / $sourceWidth, self::HEIGHT / $sourceHeight);
        $cropWidth = (int) round(self::WIDTH / $scale);
        $cropHeight = (int) round(self::HEIGHT / $scale);

        $card = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        imagecopyresampled(
            $card, $image,
            0, 0, (int) (($sourceWidth - $cropWidth) / 2), (int) (($sourceHeight - $cropHeight) / 2),
            self::WIDTH, self::HEIGHT, $cropWidth, $cropHeight,
        );

        ob_start();
        imagejpeg($card, null, 85);

        return (string) ob_get_clean();
    }
}
