@props([
    'themeColor' => config('pwa.manifest.theme_color', '#000000'),
    'icon'       => config('pwa.manifest.icons.0.src', 'logo.png'),
    'manifest'   => '/manifest.json',
])

<meta name="theme-color" content="{{ $themeColor }}">
<link rel="apple-touch-icon" href="{{ asset($icon) }}">
<link rel="manifest" href="{{ $manifest }}">
