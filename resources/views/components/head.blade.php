@use('Illuminate\Support\Facades\Config')

@props([
    'themeColor' => Config::string('pwa.manifest.theme_color', '#000000'),
    'icon' => Config::string('pwa.manifest.icons.src', 'logo.png'),
    'manifest' => Config::string('pwa.path', 'manifest.json'),
])

<meta name="theme-color" content="{{ $themeColor }}">
<link rel="apple-touch-icon" href="{{ asset($icon) }}">
<link rel="manifest" href="{{ asset($manifest) }}">
