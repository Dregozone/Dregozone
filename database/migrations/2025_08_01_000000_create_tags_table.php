<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

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

        $now = now();

        DB::table('tags')->insertOrIgnore(
            array_map(fn (string $name) => [
                'name'       => $name,
                'slug'       => Str::slug($name),
                'created_at' => $now,
                'updated_at' => $now,
            ], $tags)
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
