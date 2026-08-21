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
        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        $technologies = [
            'Alpine.js',
            'Bootstrap',
            'FFmpeg',
            'JavaScript',
            'jQuery',
            'Laravel',
            'Livewire',
            'MySQL',
            'PHP',
            'PostgreSQL',
            'Redis',
            'Swagger',
            'Tailwind CSS',
            'TypeScript',
            'Vue.js',
            'WebSockets',
        ];

        $now = now();

        DB::table('technologies')->insertOrIgnore(
            array_map(fn (string $name) => [
                'name' => $name,
                'slug' => Str::slug($name),
                'created_at' => $now,
                'updated_at' => $now,
            ], $technologies)
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('technologies');
    }
};
