<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'tags',
        'status',
        'published_at',
        'views',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(UploadedImage::class, 'image_id');
    }

    public function uploadedImage(): BelongsTo
    {
        return $this->belongsTo(UploadedImage::class, 'image_id');
    }

    /**
     * The link preview image for this post: its header image cropped to 1200×630, or null
     * when the post has no image suitable for a card and the site default should be used.
     */
    public function socialCardUrl(): ?string
    {
        if (! $this->image?->isSuitableForSocialCard()) {
            return null;
        }

        return route('blog.post.card', ['post' => $this, 'v' => $this->image->updated_at?->timestamp]);
    }

    public function viewRecords(): HasMany
    {
        return $this->hasMany(UserBlogView::class);
    }

    public function reads(): HasMany
    {
        return $this->hasMany(UserBlogRead::class);
    }
}
