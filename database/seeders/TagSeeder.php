<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'AI',
            'Cooking',
            'Database',
            'Design',
            'General',
            'Gym',
            'Laravel',
            'Livewire',
            'Optimization',
            'Performance',
            'Personal Development',
            'PHP',
            'Productivity',
            'Running',
            'Trends',
            'Web Development',
        ];

        foreach ($tags as $name) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
