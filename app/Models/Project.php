<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'url',
        'github_url',
        'technologies',
        'status',
        'order',
        'featured',
    ];

    protected $casts = [
        'technologies' => 'array',
        'featured' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'archived');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true)
                    ->where('status', '!=', 'archived')
                    ->orderBy('order');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
                    ->orderBy('order');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress')
                    ->orderBy('order');
    }
}
