---
sidebar_position: 3
---

# Usage

## Blade directives

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

This renders the theme-color meta tag, apple-touch-icon, manifest link, and
the service worker registration script.

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

The `@pwaSw` directive automatically picks up the CSP nonce from
`Vite::cspNonce()` when set.

## Icons

Icons are defined in a dedicated `icons` array in `config/pwa.php`, separate
from the manifest. Each entry supports a `disk` key pointing to any
configured Laravel filesystem disk. The `src` URL is resolved at generation
time via `Storage::disk()->url()`. Set `disk` to `null` to fall back to
`path` used as-is.

The default configuration assumes three icons — a **mobile** icon
(192×192), a **desktop** icon (512×512), and an **apple-touch-icon**. Create
the storage symlink and place all files there:

```bash
php artisan storage:link
```

```bash
$ ls storage/app/public/images/icons
storage/app/public/images/icons/apple-touch-icon.png
storage/app/public/images/icons/icon-192x192.png
storage/app/public/images/icons/icon-512x512.png
```

You can override each icon independently via `.env`:

```env
PWA_ICON_MOBILE_PATH=/storage/images/icons/icon-192x192.png
PWA_ICON_DESKTOP_PATH=/storage/images/icons/icon-512x512.png
PWA_APPLE_TOUCH_ICON=/storage/images/icons/apple-touch-icon.png
```

For S3 or other remote disks, set the respective `_DISK` variable to the
disk name — the URL will be resolved accordingly. Each icon can live on a
different disk.

## Generating the manifest and service worker

```bash
php artisan pwa:generate
```

This writes `public/manifest.json` from your config, and copies the `sw.js`
stub to `public/sw.js`. Both paths are configurable via `config/pwa.php`.

The service worker serves an offline fallback page from `public/offline.html`.
You must create this file yourself — see
[examples/offline.html](https://github.com/foxws/laravel-pwa/blob/main/examples/offline.html)
for a starting point.

## Disabling the service worker

Set `PWA_ENABLED=false` in your `.env` to disable the service worker in
local or staging environments. When disabled, `pwa:generate` writes a
self-unregistering service worker instead — on the next page load, any
previously installed SW will silently clear its caches and remove itself.
No Blade changes are required.

```env
PWA_ENABLED=false
```

The `@pwaHead` directive and `manifest.json` are unaffected; only the
service worker behaviour changes.
