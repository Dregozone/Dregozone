<?php

/**
 * The service worker once served wire:navigate page fetches from a cache-first store,
 * so admin pages showed stale content until a hard refresh. These guard the fix.
 */
function serviceWorkerSource(): string
{
    return file_get_contents(public_path('sw.js'));
}

test('service worker only serves static asset destinations from cache', function () {
    expect(serviceWorkerSource())
        ->toContain("const CACHEABLE_DESTINATIONS = ['style', 'script', 'image', 'font'];")
        ->toContain('!CACHEABLE_DESTINATIONS.includes(event.request.destination)');
});

test('service worker bumps its cache version so stale page caches are purged', function () {
    expect(serviceWorkerSource())
        ->toContain("const CACHE_NAME = 'dregozone-v2';")
        ->not->toContain("const PRECACHE_URLS = ['/', OFFLINE_URL];");
});
