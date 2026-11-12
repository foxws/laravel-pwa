"use strict";

const CACHE_PREFIX = "sw-";
const CACHE_KEY = "CACHE_KEY_PLACEHOLDER";
const IGNORED_PATHS = [IGNORED_PATHS_PLACEHOLDER];
const OFFLINE_URL = "/offline.html";

/**
 * Requests carrying one of these headers are XHR/fetch calls whose responses
 * must never be cached: Inertia, Livewire, wire:navigate, htmx and generic XHR.
 */
const BYPASS_HEADERS = [
    "X-Inertia",
    "X-Livewire",
    "X-Livewire-Navigate",
    "HX-Request",
    "X-Requested-With",
];

self.addEventListener("install", (event) => {
    // Precache the offline fallback page so it is always available.
    // If the file does not exist the install still succeeds.
    event.waitUntil(
        caches
            .open(CACHE_KEY)
            .then((cache) => cache.add(OFFLINE_URL).catch(() => {}))
            .then(() => self.skipWaiting()),
    );
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        Promise.all([
            // Only remove caches this worker created, never the app's own caches.
            caches
                .keys()
                .then((keys) =>
                    Promise.all(
                        keys
                            .filter((key) => key.startsWith(CACHE_PREFIX) && key !== CACHE_KEY)
                            .map((key) => caches.delete(key)),
                    ),
                ),
            // Start page requests while the worker boots instead of after it.
            self.registration.navigationPreload?.enable(),
        ]).then(() => self.clients.claim()),
    );
});

/**
 * Only cache a response when it is a valid, non-opaque 2xx response.
 * Caching opaque responses (cross-origin without CORS) or error responses
 * would poison the cache with unusable entries.
 * Responses with cache-control: no-cache or private must not be stored.
 */
function isCacheable(response) {
    if (!response || response.status !== 200 || response.type === "opaque") {
        return false;
    }

    const cc = response.headers.get("cache-control") ?? "";
    if (
        cc.includes("no-cache") ||
        cc.includes("no-store") ||
        cc.includes("private")
    ) {
        return false;
    }

    return response.headers.get("vary") !== "*";
}

function offlineResponse() {
    return caches
        .match(OFFLINE_URL)
        .then((cached) => cached ?? Response.error());
}

self.addEventListener("fetch", (event) => {
    const { request } = event;

    if (request.method !== "GET") return;

    // Chrome throws on these when they are refetched from a service worker.
    if (request.cache === "only-if-cached" && request.mode !== "same-origin") return;

    // Returning without respondWith() hands the request back to the browser,
    // which is cheaper than piping it through fetch() in the worker.

    // Bypass the service worker entirely for ignored path prefixes (e.g. /api/).
    const url = new URL(request.url);
    if (IGNORED_PATHS.some((prefix) => url.pathname.startsWith(prefix))) return;

    // Range requests (e.g. video/audio streaming chunks) must bypass the cache —
    // returning a full cached response for a partial request breaks media seeking.
    if (request.headers.has("Range")) return;

    // XHR/fetch calls from Inertia, Livewire and htmx would serve stale data.
    if (BYPASS_HEADERS.some((header) => request.headers.has(header))) return;

    // Use network-first for navigation requests (HTML pages) so the server
    // always has the final say. Fall back to the offline page when offline.
    if (request.mode === "navigate") {
        event.respondWith(
            Promise.resolve(event.preloadResponse)
                .then((preloaded) => preloaded ?? fetch(request))
                .catch(() => offlineResponse()),
        );
        return;
    }

    // Cache-first for static assets (JS, CSS, images, fonts, etc.).
    // Only store valid responses; fall back gracefully when offline.
    event.respondWith(
        caches.match(request).then((cached) => {
            if (cached) return cached;

            return fetch(request)
                .then((response) => {
                    if (isCacheable(response)) {
                        const clone = response.clone();

                        // Keep the worker alive until the write finishes; ignore
                        // quota errors so a full cache never breaks the response.
                        event.waitUntil(
                            caches
                                .open(CACHE_KEY)
                                .then((cache) => cache.put(request, clone))
                                .catch(() => {}),
                        );
                    }

                    return response;
                })
                .catch(() => {
                    // Return a simple 503 for assets that cannot be served offline.
                    return new Response("Service unavailable", {
                        status: 503,
                        statusText: "Service Unavailable",
                    });
                });
        }),
    );
});
