@use('Illuminate\Support\Facades\Config')
@use('Illuminate\Support\Facades\Vite')

@props([
    'scope' => '/',
    'nonce' => Vite::cspNonce(),
    'swPath' => asset(Config::string('pwa.sw_path', 'sw.js')),
    'debug' => Config::boolean('app.debug', false),
    'updateInterval' => Config::integer('pwa.update_interval', 24),
])

<script @isset($nonce) nonce="{{ $nonce }}" @endisset>
    "use strict";

    if ("serviceWorker" in navigator) {
        window.addEventListener("load", () => {
            navigator.serviceWorker
                .register("{{ $swPath }}", { scope: "{{ $scope }}", updateViaCache: "none" })
                .then(
                    (registration) => {
                        @if($debug) console.log("Service worker registration succeeded:", registration); @endif
                        @if($updateInterval > 0)
                        setInterval(() => {
                            registration.update();
                            @if($debug) console.log("Service worker update check triggered."); @endif
                        }, {{ (int) $updateInterval * 60 * 60 * 1000 }});
                        @endif
                    },
                    (error) => {
                        @if($debug) console.error("Service worker registration failed:", error); @endif
                    }
                );
        });
    } else {
        @if($debug) console.warn("Service workers are not supported."); @endif
    }
</script>
