<?php

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('createFromName creates new tag with correct slug', function () {
    $initialCount = Tag::count();

    $tag = Tag::createFromName('My Unique Test Tag XYZ');

    expect($tag->name)->toBe('My Unique Test Tag XYZ');
    expect($tag->slug)->toBe('my-unique-test-tag-xyz');
    expect(Tag::count())->toBe($initialCount + 1);
});

test('createFromName returns existing tag if slug already exists', function () {
    $first = Tag::createFromName('My Unique Tag ABC');
    $second = Tag::createFromName('My Unique Tag ABC');

    expect($first->id)->toBe($second->id);
    expect(Tag::where('slug', 'my-unique-tag-abc')->count())->toBe(1);
});

test('allNames returns all tag names ordered alphabetically', function () {
    Tag::createFromName('Zebra Unique Name');
    Tag::createFromName('Apple Unique Name');
    Tag::createFromName('Mango Unique Name');

    $names = Tag::allNames()->values()->all();

    $appleIndex = array_search('Apple Unique Name', $names);
    $mangoIndex = array_search('Mango Unique Name', $names);
    $zebraIndex = array_search('Zebra Unique Name', $names);

    expect($appleIndex)->toBeLessThan($mangoIndex);
    expect($mangoIndex)->toBeLessThan($zebraIndex);
});

test('topByUsage returns tags sorted by published post usage count', function () {
    Tag::createFromName('PHP');
    Tag::createFromName('Laravel');
    Tag::createFromName('CSS');

    BlogPost::factory()->published()->create(['tags' => ['PHP', 'Laravel']]);
    BlogPost::factory()->published()->create(['tags' => ['PHP']]);
    BlogPost::factory()->draft()->create(['tags' => ['CSS']]);

    $top = Tag::topByUsage(3);

    expect($top->first()->name)->toBe('PHP');
    expect($top->first()->count)->toBe(2);

    $names = $top->pluck('name')->all();
    expect($names)->toContain('PHP');
    expect($names)->toContain('Laravel');
});
