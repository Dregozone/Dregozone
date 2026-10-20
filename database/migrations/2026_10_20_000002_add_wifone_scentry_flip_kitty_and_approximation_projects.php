<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * New projects, each with the screenshot stored in its image library entry.
     *
     * @var list<array{title: string, description: string, image: string, url: ?string, github_url: ?string, technologies: list<string>}>
     */
    private const array PROJECTS = [
        [
            'title' => 'Wifone',
            'description' => "Audio-only voice calling in the browser. See who's online and call them over Wi-Fi or mobile data with WebRTC, plus call history, scheduled calls and an installable PWA.",
            'image' => 'wifone.jpg',
            'url' => null,
            'github_url' => 'https://github.com/Dregozone/Wifone',
            'technologies' => ['Laravel', 'Livewire', 'Reverb', 'WebRTC', 'Tailwind CSS'],
        ],
        [
            'title' => 'Scentry Candles',
            'description' => 'A mobile-first, offline-ready PWA landing page for a candle brand where every candle is a tiny wax guardian of a cherished memory.',
            'image' => 'scentry-candles.jpg',
            'url' => null,
            'github_url' => null,
            'technologies' => ['Laravel', 'Livewire', 'Tailwind CSS', 'PWA'],
        ],
        [
            'title' => 'Flip Kitty',
            'description' => 'A physics-driven mobile game: flick a cat through the air and land it on the target the right way up, across levels of rising difficulty.',
            'image' => 'flip-kitty.jpg',
            'url' => null,
            'github_url' => null,
            'technologies' => ['Laravel', 'NativePHP', 'TypeScript', 'Planck.js', 'Tailwind CSS'],
        ],
        [
            'title' => 'Approximation',
            'description' => 'A fast-paced estimation game for mobile: place values on a number line, crack ciphers and pick the closer estimate against a 5-second timer before your three lives run out.',
            'image' => 'approximation.jpg',
            'url' => null,
            'github_url' => null,
            'technologies' => ['Laravel', 'Livewire', 'Alpine.js', 'Tailwind CSS', 'NativePHP'],
        ],
    ];

    /**
     * Add each project as in progress after the existing ones, skipping any whose title already exists.
     * A fresh database with no projects is left empty, as these extend an existing portfolio.
     */
    public function up(): void
    {
        if (DB::table('projects')->doesntExist()) {
            return;
        }

        $order = (int) DB::table('projects')->max('order');

        foreach (self::PROJECTS as $project) {
            if (DB::table('projects')->where('title', $project['title'])->exists()) {
                continue;
            }

            $imageId = DB::table('uploaded_images')->insertGetId([
                'name' => $project['title'],
                'imageable_type' => 'library',
                'imageable_id' => 0,
                'base64_data' => 'data:image/jpeg;base64,'.base64_encode(file_get_contents(database_path('data/project-images/'.$project['image']))),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('projects')->insert([
                'title' => $project['title'],
                'description' => $project['description'],
                'image_id' => $imageId,
                'url' => $project['url'],
                'github_url' => $project['github_url'],
                'technologies' => json_encode($project['technologies']),
                'status' => 'in_progress',
                'order' => ++$order,
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Remove the projects added by this migration along with their images.
     */
    public function down(): void
    {
        foreach (self::PROJECTS as $project) {
            DB::table('projects')
                ->where('title', $project['title'])
                ->where('description', $project['description'])
                ->get(['id', 'image_id'])
                ->each(function (object $row): void {
                    DB::table('projects')->where('id', $row->id)->delete();
                    DB::table('uploaded_images')->where('id', $row->image_id)->delete();
                });
        }
    }
};
