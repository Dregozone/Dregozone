const CACHE_NAME = 'dregozone-v1';
const OFFLINE_URL = '/offline.html';
const PRECACHE_URLS = ['/', OFFLINE_URL];

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

    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(event.request).then((networkResponse) => {
                if (networkResponse.ok && event.request.url.startsWith(self.location.origin)) {
                    const responseClone = networkResponse.clone();

                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseClone));
                }

                return networkResponse;
            });
        })
    );
});
