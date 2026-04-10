<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public static function createFromName(string $name): self
    {
        return self::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name]
        );
    }

    /**
     * Return all tag names ordered alphabetically.
     */
    public static function allNames(): Collection
    {
        return self::orderBy('name')->pluck('name');
    }

    /**
     * Return the top $limit tags by number of published blog posts that use them,
     * as a Collection of objects with 'name' and 'count' keys.
     */
    public static function topByUsage(int $limit = 10): Collection
    {
        $allTagNames = self::orderBy('name')->pluck('name');

        $usageCounts = BlogPost::published()
            ->whereNotNull('tags')
            ->get()
            ->flatMap(fn ($post) => $post->tags ?? [])
            ->countBy()
            ->sortDesc();

        // Build the top tags list: start with tags that have posts, then fill up with
        // remaining DB tags (sorted by name) so we always show $limit entries if available.
        $top = $usageCounts->take($limit);

        if ($top->count() < $limit) {
            $remaining = $allTagNames->diff($top->keys())->values();
            foreach ($remaining as $name) {
                if ($top->count() >= $limit) {
                    break;
                }
                $top->put($name, 0);
            }
        }

        return $top->map(fn ($count, $name) => (object) [
            'name'  => $name,
            'count' => $count,
        ])->values();
    }
}
