const CACHE_NAME = 'cherry-menu-cache-v1';
const urlsToCache = [
    '/',
    'https://www.cherrymenu.com/Kibuncafe',
    '/styles.css',
    '/app.js',
    '/offline.html',
    '/logo.png',  // Add all the necessary files you want to cache
    // Include URLs to the menus and any other static assets
];

// Install the service worker and cache the app shell
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// Cache and return requests
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) {
                    return response; // Return the cached response if found
                }
                return fetch(event.request); // Fetch from network if not in cache
            })
    );
});

// Update the service worker
self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});


