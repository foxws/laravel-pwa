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
php artisan vendor:publish --tag="laravel-pwa-config"
```

## Usage

### Blade directives

Add `@pwa` inside your `<head>` and `@sw` just before `</body>`:

```blade
<head>
    @pwa
</head>

<body>
    ...
    @sw
</body>
```

This renders the theme-color meta tag, apple-touch-icon, manifest link, and the service worker registration script.

Both directives accept optional overrides:

```blade
@pwa(['themeColor' => '#ff0000', 'manifest' => '/custom.json'])

@sw(['swPath' => '/sw.js', 'scope' => '/', 'debug' => true])
```

Or use them as Blade components:

```blade
<x-pwa theme-color="#ff0000" />

<x-sw sw-path="/sw.js" scope="/" />
```

The `@sw` directive automatically picks up the CSP nonce from `Vite::cspNonce()` when set.

### Icons

Icons are defined in a dedicated `icons` array in `config/pwa.php`, separate from the manifest. Each entry supports a `disk` key pointing to any configured Laravel filesystem disk. The `src` URL is resolved at generation time via `Storage::disk()->url()`. Set `disk` to `null` to fall back to `asset()` with `path` used as-is.

The default configuration expects a 512×512 PNG on the `public` disk. Create the storage symlink and place your icon there:

```bash
php artisan storage:link
```

```
storage/app/public/images/icons/icon-512x512.png
```

You can override the disk and path via `.env`:

```env
PWA_ICON_DISK=public
PWA_ICON_PATH=images/icons/icon-512x512.png
```

For S3 or other remote disks, set `PWA_ICON_DISK` to the disk name — the URL will be resolved accordingly.

### Generating the manifest and service worker

```bash
php artisan pwa:generate
```

This writes `public/manifest.json` from your config, and copies the `sw.js` stub to `public/sw.js`. Both paths are configurable via `config/pwa.php`.

## Configuration

```php
// config/pwa.php

return [
    'manifest_path' => env('PWA_MANIFEST_PATH', 'manifest.json'),
    'sw_path'       => env('PWA_SW_PATH', 'sw.js'),

    'manifest' => [
        'id'          => env('PWA_ID', '/'),
        'name'        => env('APP_NAME', 'Laravel'),
        'short_name'  => env('PWA_SHORT_NAME', 'Laravel'),
        'start_url'   => env('PWA_START_URL', '/'),
        'display'     => env('PWA_DISPLAY', 'standalone'),
        'theme_color' => env('PWA_THEME_COLOR', '#6777ef'),
        // ...
    ],
];
```

Any key set to `null` in the manifest array is omitted from the generated JSON. Advanced keys such as `shortcuts`, `screenshots`, `categories`, and `display_override` are pre-defined in the config as `null` — uncomment and fill them in as needed.

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
