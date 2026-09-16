---
section: Usage
order: 1
---

# Usage

## Blade directives

Add `@pwaHead` inside your `<head>` tag, and `@pwaSw` just before
`</body>`:

```blade
<head>
    @pwaHead
</head>

<body>
    ...
    @pwaSw
</body>
```

This adds the theme-color meta tag, the apple-touch-icon, the manifest
link, and the script that registers the service worker.

You can override the defaults by passing an array to either directive:

```blade
@pwaHead(['themeColor' => '#ff0000', 'manifest' => '/custom.json'])

@pwaSw(['swPath' => '/sw.js', 'scope' => '/', 'debug' => true])
```

Or use them as Blade components instead:

```blade
<x-pwa-head theme-color="#ff0000" />

<x-pwa-sw sw-path="/sw.js" scope="/" />
```

If your app sets a CSP nonce with `Vite::cspNonce()`, `@pwaSw` picks it up
automatically.

## Icons

Icons are listed in the `icons` array in `config/pwa.php`, separate from
the rest of the manifest. Each icon is defined by these keys:

| Key | Description |
| --- | --- |
| `disk` | The Laravel filesystem disk the icon lives on. Set to `null` to use `path` as-is instead. |
| `path` | Path to the icon file. |
| `sizes` | Icon dimensions, e.g. `192x192`. |
| `type` | The icon's MIME type, e.g. `image/png`. |

When `disk` is set, the icon's URL is resolved at generation time with
`Storage::disk()->url()`.

By default, the package expects three icons: a **mobile** icon
(192×192), a **desktop** icon (512×512), and an **apple-touch-icon**.
Create the storage symlink and add the files:

```bash
php artisan storage:link
```

```bash
$ ls storage/app/public/images/icons
storage/app/public/images/icons/apple-touch-icon.png
storage/app/public/images/icons/icon-192x192.png
storage/app/public/images/icons/icon-512x512.png
```

You can override each icon's path individually via `.env`:

```env
PWA_ICON_MOBILE_PATH=/storage/images/icons/icon-192x192.png
PWA_ICON_DESKTOP_PATH=/storage/images/icons/icon-512x512.png
PWA_APPLE_TOUCH_ICON=/storage/images/icons/apple-touch-icon.png
```

For S3 or another remote disk, set the matching `_DISK` variable to that
disk's name and the URL is resolved accordingly. Each icon can live on
its own disk.

## Generating the manifest and service worker

```bash
php artisan pwa:generate
```

This writes `public/manifest.json` from your config, and copies the
`sw.js` stub to `public/sw.js`. Both file paths can be changed in
`config/pwa.php`.

The service worker shows an offline fallback page from
`public/offline.html` when there's no connection. You need to create
this file yourself — see
[examples/offline.html](https://github.com/foxws/laravel-pwa/blob/main/examples/offline.html)
for a starting point.

## Disabling the service worker

Set `PWA_ENABLED=false` in your `.env` file to turn off the service
worker, for example in local or staging environments:

```env
PWA_ENABLED=false
```

When disabled, `pwa:generate` writes a self-unregistering service worker
instead of the normal one. On the next page load, any service worker a
user already installed quietly clears its caches and removes itself. You
don't need to change any Blade templates for this.

Disabling the service worker doesn't affect `@pwaHead` or
`manifest.json` — only the service worker itself is turned off.
