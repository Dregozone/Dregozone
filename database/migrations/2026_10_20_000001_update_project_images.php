<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Screenshot for each project, keyed by the project's exact title.
     *
     * @var array<string, string>
     */
    private const array IMAGES = [
        'Evoluna' => 'evoluna.jpg',
        'Little Ones Wishlist' => 'little-ones-wishlist.jpg',
        'Whenish' => 'whenish.jpg',
        'Personal Assistant' => 'personal-assistant.jpg',
        'Atlas Fit' => 'atlas-fit.jpg',
        'Exclusion Zone' => 'exclusion-zone.jpg',
        'Synthera Coder' => 'synthera-coder.jpg',
        'Roulette Helper' => 'roulette-helper.jpg',
        'Dregozone' => 'dregozone.jpg',
        'Glacial Studio' => 'glacial-studio.jpg',
        'Dictation' => 'dictation.jpg',
    ];

    /**
     * Add each screenshot to the image library and attach it to the project with the matching title.
     * Projects that don't exist are skipped, and the images they replace stay in the library.
     */
    public function up(): void
    {
        foreach (self::IMAGES as $title => $file) {
            $projectIds = DB::table('projects')->where('title', $title)->pluck('id');

            if ($projectIds->isEmpty()) {
                continue;
            }

            $imageId = DB::table('uploaded_images')->insertGetId([
                'name' => $title,
                'imageable_type' => 'library',
                'imageable_id' => 0,
                'base64_data' => 'data:image/jpeg;base64,'.base64_encode(file_get_contents(database_path('data/project-images/'.$file))),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('projects')->whereIn('id', $projectIds)->update(['image_id' => $imageId]);
        }
    }

    /**
     * Remove the screenshots added by this migration. Their projects are left without an image.
     */
    public function down(): void
    {
        foreach (self::IMAGES as $title => $file) {
            DB::table('uploaded_images')
                ->where('imageable_type', 'library')
                ->where('name', $title)
                ->where('base64_data', 'data:image/jpeg;base64,'.base64_encode(file_get_contents(database_path('data/project-images/'.$file))))
                ->delete();
        }
    }
};
