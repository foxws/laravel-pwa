@use('Illuminate\Support\Facades\Config')
@use('Illuminate\Support\Facades\Vite')

@props([
    'scope' => '/',
    'nonce' => Vite::cspNonce(),
    'swPath' => Config::string('pwa.sw_path', 'sw.js'),
    'debug' => Config::boolean('app.debug', false),
])

<script src="{{ $swPath }}" @isset($nonce) nonce="{{ $nonce }}" @endisset></script>

<script @isset($nonce) nonce="{{ $nonce }}" @endisset>
    "use strict";

    if ("serviceWorker" in navigator) {
        window.addEventListener("load", () => {
            navigator.serviceWorker
                .register("{{ $swPath }}", { scope: "{{ $scope }}" })
                .then(
                    (registration) => {
                        @if($debug) console.log("Service worker registration succeeded:", registration); @endif
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
