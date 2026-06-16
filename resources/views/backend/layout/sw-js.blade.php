const filesToCache = [
  'offline.html',
];

const staticCacheName = 'acp-v1';

// addAll() fails if any single request fails (e.g. 404 for offline.html).
// Add each URL individually so one failure doesn't break install.
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(staticCacheName).then(cache => {
      return Promise.allSettled(
        filesToCache.map(url =>
          cache.add(url).catch(err => {
            console.warn('sw: cache add failed for', url, err);
          })
        )
      );
    }).then(() => self.skipWaiting())
  );
});

self.addEventListener('fetch', event => {
  //console.log('Fetch event for ', event.request.url);
  event.respondWith(
    caches.match(event.request)
    .then(response => {
      if (response) {
        //console.log('Found ', event.request.url, ' in cache');
        return response;
      }
      //console.log('Network request for ', event.request.url);
      return fetch(event.request)
    }).catch(error => {
       return caches.match('offline.html');
    })
  );
});