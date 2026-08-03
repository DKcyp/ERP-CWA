const CACHE_NAME = 'erp-cwa-v1';
const STATIC_CACHE = 'erp-cwa-static-v1';

// Asset yang dicache saat install (app shell)
const PRECACHE_URLS = [
    '/dashboard',
    '/manifest.json',
    '/logo.png',
    '/custom/assets/compiled/css/app.css',
    '/custom/assets/compiled/css/custom.css',
    '/custom/assets/compiled/css/iconly.css',
    '/custom/assets/compiled/js/app.js',
];

// Install: precache app shell
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => {
            return cache.addAll(PRECACHE_URLS).catch((err) => {
                console.warn('[SW] Precache failed (beberapa asset mungkin belum ada):', err);
            });
        })
    );
    self.skipWaiting();
});

// Activate: hapus cache lama
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME && name !== STATIC_CACHE)
                    .map((name) => caches.delete(name))
            );
        })
    );
    self.clients.claim();
});

// Fetch: strategi Network First untuk navigasi, Cache First untuk aset statis
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET, non-same-origin, dan API/AJAX calls
    if (
        request.method !== 'GET' ||
        !url.origin.includes(self.location.origin) ||
        url.pathname.startsWith('/api/') ||
        url.pathname.includes('_debugbar') ||
        request.headers.get('X-Requested-With') === 'XMLHttpRequest'
    ) {
        return;
    }

    // Strategi: Network First (navigasi HTML) — fallback ke cache
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
                    return response;
                })
                .catch(() => {
                    return caches.match(request).then((cached) => {
                        return cached || caches.match('/dashboard');
                    });
                })
        );
        return;
    }

    // Strategi: Cache First (CSS, JS, gambar, font)
    if (
        url.pathname.match(/\.(css|js|png|jpg|jpeg|svg|ico|woff2?|ttf|eot)$/i)
    ) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;
                return fetch(request).then((response) => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(STATIC_CACHE).then((cache) => cache.put(request, clone));
                    }
                    return response;
                });
            })
        );
        return;
    }

    // Default: Network only
    event.respondWith(fetch(request).catch(() => caches.match(request)));
});

// Background Sync placeholder (untuk future offline form submission)
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-data') {
        console.log('[SW] Background sync triggered');
    }
});

// Push Notification placeholder
self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : {};
    const title = data.title || 'ERP CWA';
    const options = {
        body: data.body || 'Ada notifikasi baru',
        icon: '/pwa/icons/icon-192x192.png',
        badge: '/pwa/icons/icon-72x72.png',
        vibrate: [100, 50, 100],
        data: { url: data.url || '/dashboard' },
    };
    event.waitUntil(self.registration.showNotification(title, options));
});

// Notification click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url || '/dashboard')
    );
});
