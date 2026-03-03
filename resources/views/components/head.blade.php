@use('Illuminate\Support\Facades\Config')

@props([
    'themeColor' => Config::string('pwa.manifest.theme_color', '#000000'),
    'icon' => asset(Config::string('pwa.manifest.icons.src', 'logo.png')),
    'manifest' => asset('/manifest.json'),
])

<meta name="theme-color" content="{{ $themeColor }}">
<link rel="apple-touch-icon" href="{{ $icon }}">
<link rel="manifest" href="{{ $manifest }}">
