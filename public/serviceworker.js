var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    "/assets/pwa/fonts/fonts.css",
    "/assets/pwa/fonts/font-icons.css",
    "/assets/pwa/css/bootstrap.min.css",
    "/assets/pwa/css/nouislider.min.css",
    "/assets/pwa/css/swiper-bundle.min.css",
    "/assets/pwa/css/styles.css",
    "/assets/fonts/tabler-icons.min.css ",
    "/assets/pwa/images/logo/168.png",
    "/assets/pwa/js/bootstrap.min.js",
    "/assets/pwa/js/jquery.min.js",
    "/assets/pwa/js/main.js",
    "/images/icons/icon-72x72.png",
    "/images/icons/icon-96x96.png",
    "/images/icons/icon-128x128.png",
    "/images/icons/icon-144x144.png",
    "/images/icons/icon-152x152.png",
    "/images/icons/icon-192x192.png",
    "/images/icons/icon-384x384.png",
    "/images/icons/icon-512x512.png",
];

// Cache on install
self.addEventListener("install", (event) => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName).then((cache) => {
            return cache.addAll(filesToCache);
        })
    );
});

// Clear cache on activate
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((cacheName) => cacheName.startsWith("pwa-"))
                    .filter((cacheName) => cacheName !== staticCacheName)
                    .map((cacheName) => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches
            .match(event.request)
            .then((response) => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match("offline");
            })
    );
});
