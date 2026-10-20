<?php

use App\Models\Project;
use App\Models\UploadedImage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function updateProjectImagesMigration(): object
{
    return require database_path('migrations/2026_10_20_000001_update_project_images.php');
}

test('every project screenshot exists as a jpeg', function () {
    $images = (new ReflectionClassConstant(updateProjectImagesMigration(), 'IMAGES'))->getValue();

    foreach ($images as $file) {
        $path = database_path('data/project-images/'.$file);

        expect($path)->toBeFile()
            ->and(getimagesize($path)['mime'])->toBe('image/jpeg');
    }
});

test('migration attaches a new library image to each matching project', function () {
    $oldImage = UploadedImage::create(['name' => 'Old', 'imageable_type' => 'library', 'imageable_id' => 0, 'base64_data' => 'data:image/png;base64,old']);
    $evoluna = Project::factory()->create(['title' => 'Evoluna', 'image_id' => $oldImage->id]);
    $dictation = Project::factory()->create(['title' => 'Dictation']);
    $unrelated = Project::factory()->create(['title' => 'Something Else']);

    updateProjectImagesMigration()->up();

    expect($evoluna->fresh()->uploadedImage)
        ->name->toBe('Evoluna')
        ->imageable_type->toBe('library')
        ->base64_data->toStartWith('data:image/jpeg;base64,')
        ->and($dictation->fresh()->uploadedImage->name)->toBe('Dictation')
        ->and($unrelated->fresh()->image_id)->toBeNull()
        ->and($oldImage->fresh())->not->toBeNull()
        ->and(UploadedImage::library()->count())->toBe(3);
});

test('migration adds nothing when no project titles match', function () {
    Project::factory()->create(['title' => 'Something Else']);

    updateProjectImagesMigration()->up();

    expect(UploadedImage::count())->toBe(0);
});

test('rolling back removes only the images the migration added', function () {
    $oldImage = UploadedImage::create(['name' => 'Evoluna', 'imageable_type' => 'library', 'imageable_id' => 0, 'base64_data' => 'data:image/png;base64,old']);
    $evoluna = Project::factory()->create(['title' => 'Evoluna']);
    $migration = updateProjectImagesMigration();

    $migration->up();
    $migration->down();

    expect(UploadedImage::pluck('id')->all())->toBe([$oldImage->id])
        ->and($evoluna->fresh()->image_id)->toBeNull();
});
