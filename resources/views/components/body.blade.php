@use('Foxws\Pwa\Pwa')
@use('Illuminate\Support\Facades\Vite')

@props([
    'scope' => '/',
    'nonce' => Vite::cspNonce(),
    'swPath' => Pwa::swUrl(),
    'debug' => Pwa::debug(),
    'updateInterval' => Pwa::updateInterval(),
])

<script @isset($nonce) nonce="{{ $nonce }}" @endisset>
    "use strict";

    if ("serviceWorker" in navigator) {
        // A new service worker taking control doesn't refresh the already-loaded
        // page on its own — reload once so the tab actually runs the new assets
        // instead of silently continuing to run the old bundle underneath it.
        let refreshingAfterUpdate = false;
        navigator.serviceWorker.addEventListener("controllerchange", () => {
            if (refreshingAfterUpdate) return;
            refreshingAfterUpdate = true;
            window.location.reload();
        });

        window.addEventListener("load", () => {
            navigator.serviceWorker
                .register("{{ $swPath }}", { scope: "{{ $scope }}", updateViaCache: "none" })
                .then(
                    (registration) => {
                        @if($debug) console.log("Service worker registration succeeded:", registration); @endif
                        @if($updateInterval > 0)
                        const checkForUpdate = () => {
                            registration.update();
                            @if($debug) console.log("Service worker update check triggered."); @endif
                        };

                        setInterval(checkForUpdate, {{ (int) $updateInterval * 60 * 60 * 1000 }});

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
        });
    } else {
        console.debug("Service workers are not supported.");
    }
</script>
