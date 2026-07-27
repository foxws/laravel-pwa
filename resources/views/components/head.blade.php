@use('Foxws\Pwa\Pwa')

@props([
    'themeColor' => Pwa::themeColor(),
    'icon' => Pwa::appleTouchIcon(),
    'manifest' => Pwa::manifestUrl(),
])

<meta name="theme-color" content="{{ $themeColor }}">
<link rel="apple-touch-icon" href="{{ $icon }}">
<link rel="manifest" href="{{ $manifest }}">
