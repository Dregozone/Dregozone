<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UploadedImage extends Model
{
    /**
     * Smallest source that still looks sharp when cropped to a 1200×630 link preview.
     */
    public const SOCIAL_CARD_MIN_WIDTH = 600;

    public const SOCIAL_CARD_MIN_HEIGHT = 315;

    protected $fillable = [
        'name',
        'imageable_id',
        'imageable_type',
        'base64_data',
    ];

    public function scopeLibrary(Builder $query): Builder
    {
        return $query->where('imageable_type', 'library');
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where('name', 'like', '%'.$term.'%');
    }

    /**
     * The raw image bytes behind the stored data URI.
     */
    public function binary(): string
    {
        $data = (string) $this->base64_data;
        $comma = strpos($data, ',');

        return (string) base64_decode($comma === false ? $data : substr($data, $comma + 1), true);
    }

    /**
     * Whether the image is big enough, and wide enough, to crop into a link preview card
     * without upscaling or losing most of the picture.
     */
    public function isSuitableForSocialCard(): bool
    {
        $size = @getimagesizefromstring($this->binary());

        if ($size === false || $size[1] === 0) {
            return false;
        }

        [$width, $height] = $size;
        $aspectRatio = $width / $height;

        return $width >= self::SOCIAL_CARD_MIN_WIDTH
            && $height >= self::SOCIAL_CARD_MIN_HEIGHT
            && $aspectRatio >= 1.0
            && $aspectRatio <= 3.0;
    }
}
