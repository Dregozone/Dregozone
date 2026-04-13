<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UploadedImage extends Model
{
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
}
