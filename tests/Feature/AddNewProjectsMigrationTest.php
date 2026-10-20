<?php

use App\Models\Project;
use App\Models\UploadedImage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function addNewProjectsMigration(): object
{
    return require database_path('migrations/2026_10_20_000002_add_wifone_scentry_flip_kitty_and_approximation_projects.php');
}

test('every new project screenshot exists as a jpeg', function () {
    $projects = (new ReflectionClassConstant(addNewProjectsMigration(), 'PROJECTS'))->getValue();

    foreach ($projects as $project) {
        $path = database_path('data/project-images/'.$project['image']);

        expect($path)->toBeFile()
            ->and(getimagesize($path)['mime'])->toBe('image/jpeg');
    }
});

test('migration adds the new projects after the existing ones with their images', function () {
    Project::factory()->create(['title' => 'Evoluna', 'order' => 7]);

    addNewProjectsMigration()->up();

    $wifone = Project::where('title', 'Wifone')->first();

    expect(Project::orderBy('order')->pluck('title')->all())->toBe(['Evoluna', 'Wifone', 'Scentry Candles', 'Flip Kitty', 'Approximation'])
        ->and(Project::where('order', '>', 7)->pluck('status')->unique()->all())->toBe(['in_progress'])
        ->and($wifone->order)->toBe(8)
        ->and($wifone->featured)->toBeFalse()
        ->and($wifone->github_url)->toBe('https://github.com/Dregozone/Wifone')
        ->and($wifone->technologies)->toContain('WebRTC')
        ->and($wifone->uploadedImage)
        ->name->toBe('Wifone')
        ->imageable_type->toBe('library')
        ->base64_data->toStartWith('data:image/jpeg;base64,')
        ->and(Project::where('title', 'Flip Kitty')->value('github_url'))->toBeNull();
});

test('migration skips a project whose title already exists', function () {
    $existing = Project::factory()->create(['title' => 'Wifone', 'description' => 'Already here']);

    addNewProjectsMigration()->up();

    expect(Project::where('title', 'Wifone')->count())->toBe(1)
        ->and($existing->fresh()->description)->toBe('Already here')
        ->and(UploadedImage::where('name', 'Wifone')->exists())->toBeFalse()
        ->and(Project::count())->toBe(4);
});

test('migration leaves a database without projects empty', function () {
    addNewProjectsMigration()->up();

    expect(Project::count())->toBe(0)
        ->and(UploadedImage::count())->toBe(0);
});

test('rolling back removes only the added projects and their images', function () {
    $existing = Project::factory()->create(['title' => 'Evoluna']);
    $migration = addNewProjectsMigration();

    $migration->up();
    $migration->down();

    expect(Project::pluck('id')->all())->toBe([$existing->id])
        ->and(UploadedImage::count())->toBe(0);
});
