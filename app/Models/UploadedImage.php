<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UploadedImage extends Model
{
    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'base64_data',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
