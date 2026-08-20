<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Technology extends Model
{
    protected $fillable = ['name', 'slug'];

    public static function createFromName(string $name): self
    {
        return self::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name]
        );
    }

    /**
     * Return all technology names ordered alphabetically.
     */
    public static function allNames(): Collection
    {
        return self::orderBy('name')->pluck('name');
    }
}
