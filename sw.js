const CACHE_NAME = 'mysic-v1';
const urlsToCache = [
  '/',
  '/index.php/welcome',
  '/assets/css/style.css',
  '/assets/js/app.js'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(res => res || fetch(event.request))
  );
});
