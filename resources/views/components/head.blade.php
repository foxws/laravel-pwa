@use('Illuminate\Support\Facades\Config')

@props([
    'themeColor' => Config::string('pwa.manifest.theme_color', '#000000'),
    'icon' => asset(Config::string('pwa.apple_touch_icon', 'logo.png')),
    'manifest' => asset(Config::string('pwa.manifest_path', 'manifest.json')),
])

<meta name="theme-color" content="{{ $themeColor }}">
<link rel="apple-touch-icon" href="{{ $icon }}">
<link rel="manifest" href="{{ $manifest }}">
