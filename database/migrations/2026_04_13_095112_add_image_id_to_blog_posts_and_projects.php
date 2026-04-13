<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('blog_posts', 'image_id')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->foreignId('image_id')->nullable()->after('featured_image')->constrained('uploaded_images')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('projects', 'image_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->foreignId('image_id')->nullable()->after('image')->constrained('uploaded_images')->nullOnDelete();
            });
        }

        // Backfill blog_posts.image_id from existing morph data
        DB::table('uploaded_images')
            ->where('imageable_type', 'App\\Models\\BlogPost')
            ->orderBy('id')
            ->each(function ($image) {
                DB::table('blog_posts')
                    ->where('id', $image->imageable_id)
                    ->update(['image_id' => $image->id]);
            });

        // Backfill projects.image_id from existing morph data
        DB::table('uploaded_images')
            ->where('imageable_type', 'App\\Models\\Project')
            ->orderBy('id')
            ->each(function ($image) {
                DB::table('projects')
                    ->where('id', $image->imageable_id)
                    ->update(['image_id' => $image->id]);
            });

        // All uploaded images are now library images — reset morph columns
        DB::table('uploaded_images')->update(['imageable_type' => 'library', 'imageable_id' => 0]);
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
        });
    }
};
