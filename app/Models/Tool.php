<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'image_id',
        'order',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('title');
    }

    public function uploadedImage(): BelongsTo
    {
        return $this->belongsTo(UploadedImage::class, 'image_id');
    }
}
