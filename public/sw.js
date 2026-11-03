// Bumping the version purges older caches on activate (v1 cached HTML fetched by wire:navigate).
const CACHE_NAME = 'dregozone-v2';
const OFFLINE_URL = '/offline.html';
const PRECACHE_URLS = [OFFLINE_URL];

// Only immutable static assets are served cache-first. Pages, Livewire requests and
// anything else always go to the network so content is never stale.
const CACHEABLE_DESTINATIONS = ['style', 'script', 'image', 'font'];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_URLS))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(
            keys
                .filter((key) => key !== CACHE_NAME)
                .map((key) => caches.delete(key))
        ))
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') {
        return;
    }

    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => caches.match(OFFLINE_URL))
        );

        return;
    }

    const isSameOrigin = event.request.url.startsWith(self.location.origin);

    if (!isSameOrigin || !CACHEABLE_DESTINATIONS.includes(event.request.destination)) {
        return;
    }

    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(event.request).then((networkResponse) => {
                if (networkResponse.ok) {
                    const responseClone = networkResponse.clone();

                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseClone));
                }

                return networkResponse;
            });
        })
    );
});
