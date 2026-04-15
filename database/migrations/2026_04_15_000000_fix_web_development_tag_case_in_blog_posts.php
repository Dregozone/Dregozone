<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->replaceTag('Web Development', 'Web development');
    }

    public function down(): void
    {
        $this->replaceTag('Web development', 'Web Development');
    }

    private function replaceTag(string $from, string $to): void
    {
        DB::table('blog_posts')
            ->whereJsonContains('tags', $from)
            ->chunkById(100, function ($posts) use ($from, $to): void {
                foreach ($posts as $post) {
                    $tags = json_decode($post->tags, true);

                    if (! is_array($tags)) {
                        continue;
                    }

                    $updated = array_map(
                        fn ($tag) => $tag === $from ? $to : $tag,
                        $tags
                    );

                    DB::table('blog_posts')
                        ->where('id', $post->id)
                        ->update(['tags' => json_encode($updated)]);
                }
            });
    }
};
