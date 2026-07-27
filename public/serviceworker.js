var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    "/offline",
    "/assets/css/style.css",
    "/assets/css/style-preset.css",
    "/assets/css/custom.css",
    "/assets/css/plugins/animate.min.css",
    "/assets/fonts/phosphor/duotone/style.css",
    "/assets/fonts/tabler-icons.min.css",
    "/assets/fonts/feather.css",
    "/assets/fonts/fontawesome.css ",
    "/assets/fonts/material.css",
    "/assets/js/script.js",
    "/assets/js/theme.js",
    "/assets/js/plugins/bootstrap.min.js",
    "/assets/js/plugins/simplebar.min.js",
    "/assets/js/plugins/popper.min.js",
];

self.addEventListener("notificationclick", function (event) {
    event.notification.close();
    const domain = "https://smartstock.smartwork.id";
    const url = event.notification.data?.url;
    if (!url) {
        return;
    }
    event.waitUntil(clients.openWindow(domain + url));
});

self.addEventListener("push", function (event) {
    const payload = event.data.json();
    self.registration.showNotification(payload.data.title, {
        body: payload.data.body,
        icon: "/assets/images/icons/1000x1000.png",
        data: {
            url: payload.data.url,
        },
    });
});

// ** BLOCK BARU UNTUK OPT-OUT CHROME 143 BUG (ServiceWorkerAutoPreloadEnabled) **
// Blok ini harus ada sebelum event install utama Anda.
// ******************************************************************************

// Cache on install (Install listener lama Anda)
self.addEventListener("install", (event) => {
    self.skipWaiting();
    if (event.addRoutes) {
        event.addRoutes({
            condition: {
                // Terapkan opt-out untuk semua URL
                urlPattern: new URLPattern({}),
            },
            // Paksa Service Worker untuk menggunakan event fetch biasa (opt-out preload)
            source: "fetch-event",
        });
    }
    event.waitUntil(
        caches.open(staticCacheName).then(async (cache) => {
            for (const file of filesToCache) {
                try {
                    const response = await fetch(file);

                    console.log(file, response.status);

                    if (response.ok) {
                        await cache.put(file, response.clone());
                    } else {
                        console.error("Failed:", file, response.status);
                    }
                } catch (err) {
                    console.error("Error:", file, err);
                }
            }
        }),
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
                    .map((cacheName) => caches.delete(cacheName)),
            );
        }),
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
                return caches.match("/offline"); // Pastikan path ke halaman offline sudah benar
            }),
    );
});
