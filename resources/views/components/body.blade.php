@use('Foxws\Pwa\Pwa')
@use('Illuminate\Support\Facades\Vite')

@props([
    'scope' => '/',
    'nonce' => Vite::cspNonce(),
    'swPath' => Pwa::swUrl(),
    'debug' => Pwa::debug(),
    'updateInterval' => Pwa::updateInterval(),
])

{{-- data-navigate-once: Livewire's wire:navigate re-runs <body> scripts on every visit; register only once. --}}
<script data-navigate-once @isset($nonce) nonce="{{ $nonce }}" @endisset>
    "use strict";

    if ("serviceWorker" in navigator) {
        // A new service worker taking control doesn't refresh the already-loaded
        // page on its own — reload once so the tab actually runs the new assets
        // instead of silently continuing to run the old bundle underneath it.
        // The first install also fires controllerchange (clients.claim()), but
        // that page already runs the current assets, so it must not reload.
        let hasController = Boolean(navigator.serviceWorker.controller);
        let refreshingAfterUpdate = false;

        navigator.serviceWorker.addEventListener("controllerchange", () => {
            if (!hasController) {
                hasController = true;

                return;
            }

            if (refreshingAfterUpdate) return;
            refreshingAfterUpdate = true;
            window.location.reload();
        });

        const registerServiceWorker = () => {
            navigator.serviceWorker
                .register(@js($swPath), { scope: @js($scope), updateViaCache: "none" })
                .then(
                    (registration) => {
                        @if($debug) console.log("Service worker registration succeeded:", registration); @endif
                        @if($updateInterval > 0)
                        const updateIntervalMs = {{ (int) $updateInterval * 60 * 60 * 1000 }};
                        const minimumCheckGapMs = 60 * 1000;
                        let lastCheckedAt = Date.now();

                        const checkForUpdate = () => {
                            if (Date.now() - lastCheckedAt < minimumCheckGapMs) return;
                            lastCheckedAt = Date.now();

                            registration.update().catch(() => {});
                            @if($debug) console.log("Service worker update check triggered."); @endif
                        };

                        setInterval(checkForUpdate, updateIntervalMs);

                        // A tab left open across a release (backgrounded, or restored
                        // from a previous session) won't hit the interval until it
                        // elapses — check again as soon as the tab is visible.
                        document.addEventListener("visibilitychange", () => {
                            if (document.visibilityState === "visible") {
                                checkForUpdate();
                            }
                        });
                        @endif
                    },
                    (error) => {
                        console.error("Service worker registration failed:", error);
                    }
                );
        };

        // The load event has already fired when this script runs late
        // (e.g. injected after a client-side navigation).
        if (document.readyState === "complete") {
            registerServiceWorker();
        } else {
            window.addEventListener("load", registerServiceWorker, { once: true });
        }
    } else {
        console.debug("Service workers are not supported.");
    }
</script>
