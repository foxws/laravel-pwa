# laravel-pwa

[![Latest Version on Packagist](https://img.shields.io/packagist/v/foxws/laravel-pwa.svg?style=flat-square)](https://packagist.org/packages/foxws/laravel-pwa)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/foxws/laravel-pwa/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/foxws/laravel-pwa/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/foxws/laravel-pwa/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/foxws/laravel-pwa/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/foxws/laravel-pwa.svg?style=flat-square)](https://packagist.org/packages/foxws/laravel-pwa)

A minimal, opinionated Progressive Web App (PWA) package for Laravel. It provides Blade directives for the PWA head and service worker registration, and an Artisan command to generate your `manifest.json` and publish a `sw.js` stub.

## Installation

```bash
composer require foxws/laravel-pwa
```

Publish the config file:

```bash
php artisan vendor:publish --tag="pwa-config"
```

## Usage

### Blade directives

Add `@pwaHead` inside your `<head>` and `@pwaSw` just before `</body>`:

```blade
<head>
    @pwaHead
</head>

<body>
    ...
    @pwaSw
</body>
```

This renders the theme-color meta tag, apple-touch-icon, manifest link, and the service worker registration script.

Both directives accept optional overrides:

```blade
@pwaHead(['themeColor' => '#ff0000', 'manifest' => '/custom.json'])

@pwaSw(['swPath' => '/sw.js', 'scope' => '/', 'debug' => true])
```

Or use them as Blade components:

```blade
<x-pwa-head theme-color="#ff0000" />

<x-pwa-sw sw-path="/sw.js" scope="/" />
```

The `@pwaSw` directive automatically picks up the CSP nonce from `Vite::cspNonce()` when set.

### Icons

Icons are defined in a dedicated `icons` array in `config/pwa.php`, separate from the manifest. Each entry supports a `disk` key pointing to any configured Laravel filesystem disk. The `src` URL is resolved at generation time via `Storage::disk()->url()`. Set `disk` to `null` to fall back to `asset()` with `path` used as-is.

The default configuration ships with two icons — a **mobile** icon (192×192) and a **desktop** icon (512×512). Create the storage symlink and place both files there:

```bash
php artisan storage:link
```

```
storage/app/public/images/icons/icon-192x192.png
storage/app/public/images/icons/icon-512x512.png
```

You can override each icon independently via `.env`:

```env
# Mobile icon (192×192)
PWA_ICON_DISK=public
PWA_ICON_MOBILE_PATH=images/icons/icon-192x192.png
PWA_ICON_MOBILE_SIZES=192x192
PWA_ICON_MOBILE_TYPE=image/png

# Desktop icon (512×512)
PWA_ICON_DESKTOP_DISK=public
PWA_ICON_DESKTOP_PATH=images/icons/icon-512x512.png
PWA_ICON_DESKTOP_SIZES=512x512
PWA_ICON_DESKTOP_TYPE=image/png
```

For S3 or other remote disks, set the respective `_DISK` variable to the disk name — the URL will be resolved accordingly. Each icon can live on a different disk.

### Generating the manifest and service worker

```bash
php artisan pwa:generate
```

This writes `public/manifest.json` from your config, and copies the `sw.js` stub to `public/sw.js`. Both paths are configurable via `config/pwa.php`.

The service worker serves an offline fallback page from `public/offline.html`. You must create this file yourself — see [examples/offline.html](examples/offline.html) for a starting point.

## Configuration

```php
// config/pwa.php

return [
    'manifest_path' => env('PWA_MANIFEST_PATH', 'manifest.json'),
    'sw_path'       => env('PWA_SW_PATH', 'sw.js'),

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
            'disk'  => env('PWA_ICON_DISK', 'public'),
            'path'  => env('PWA_ICON_MOBILE_PATH', 'images/icons/icon-192x192.png'),
            'sizes' => env('PWA_ICON_MOBILE_SIZES', '192x192'),
            'type'  => env('PWA_ICON_MOBILE_TYPE', 'image/png'),
        ],
        // Desktop icon
        [
            'disk'  => env('PWA_ICON_DESKTOP_DISK', 'public'),
            'path'  => env('PWA_ICON_DESKTOP_PATH', 'images/icons/icon-512x512.png'),
            'sizes' => env('PWA_ICON_DESKTOP_SIZES', '512x512'),
            'type'  => env('PWA_ICON_DESKTOP_TYPE', 'image/png'),
        ],
    ],
];
```

Any key set to `null` in the manifest array is omitted from the generated JSON. Advanced keys such as `shortcuts`, `screenshots`, and `categories` can be added to the manifest array as needed.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [francoism90](https://github.com/foxws)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
