---
sidebar_position: 4
---

# Configuration

```php title="config/pwa.php"
return [
    'enabled'       => env('PWA_ENABLED', true),
    'manifest_path' => env('PWA_MANIFEST_PATH', 'manifest.json'),
    'sw_path'       => env('PWA_SW_PATH', 'sw.js'),
    'ignore_paths'  => ['/api/', '/livewire/', '/_inertia/'],
    'manifest' => [
        'id'             => env('PWA_ID', '/'),
        'name'           => env('APP_NAME', 'Laravel'),
        'short_name'     => env('PWA_SHORT_NAME', 'Laravel'),
        'description'    => env('PWA_DESCRIPTION', 'A Progressive Web Application setup for Laravel projects.'),
        'start_url'      => env('PWA_START_URL', '/'),
        'scope'          => env('PWA_SCOPE', '/'),
        'display_override' => ['fullscreen', 'standalone'],
        'display'        => env('PWA_DISPLAY', 'fullscreen'),
        'orientation'    => env('PWA_ORIENTATION', 'any'),
        'background_color' => env('PWA_BACKGROUND_COLOR', '#ffffff'),
        'theme_color'    => env('PWA_THEME_COLOR', '#6777ef'),
        'lang'           => env('PWA_LANG', 'en'),
        'dir'            => env('PWA_DIR', 'ltr'),
    ],
    'icons' => [
        // Mobile icon
        [
            'disk'  => env('PWA_ICON_DISK', null),
            'path'  => env('PWA_ICON_MOBILE_PATH', '/storage/images/icons/icon-192x192.png'),
            'sizes' => env('PWA_ICON_MOBILE_SIZES', '192x192'),
            'type'  => env('PWA_ICON_MOBILE_TYPE', 'image/png'),
        ],
        // Desktop icon
        [
            'disk'  => env('PWA_ICON_DISK', null),
            'path'  => env('PWA_ICON_DESKTOP_PATH', '/storage/images/icons/icon-512x512.png'),
            'sizes' => env('PWA_ICON_DESKTOP_SIZES', '512x512'),
            'type'  => env('PWA_ICON_DESKTOP_TYPE', 'image/png'),
        ],
    ],
    'apple_touch_icon' => env('PWA_APPLE_TOUCH_ICON', '/storage/images/icons/apple-touch-icon.png'),
];
```

Any key set to `null` in the manifest array is omitted from the generated
JSON. Advanced keys such as `shortcuts`, `screenshots`, and `categories` can
be added to the manifest array as needed.
