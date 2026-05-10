<?php

test('user blog views nullable migration alters existing user_id column', function () {
    $migrationContents = file_get_contents(database_path('migrations/2026_05_10_000001_make_user_id_nullable_in_user_blog_views_table.php'));

    expect($migrationContents)
        ->toContain("unsignedBigInteger('user_id')->nullable()->change();")
        ->toContain("unsignedBigInteger('user_id')->nullable(false)->change();")
        ->not->toContain("foreignId('user_id')");
});
