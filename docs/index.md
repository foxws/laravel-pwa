---
title: Introduction
metadata:
  role: Progressive Web App
  group: deploy
  eyebrow: "PWA · Service Worker · Manifest"
  desc: "Turn your Laravel app into an installable, offline-friendly PWA."
  lead: "Make your Laravel app installable, with a manifest, icons and a service worker that never serves stale Inertia, Livewire or htmx responses."
  requires: "PHP ^8.3"
  laravel: "12.x / 13.x"
  licence: MIT
  used_by:
    name: Stry
    desc: "A self-hosted video streaming app."
    href: "https://github.com/francoism90/stry"
---

# Introduction

Laravel PWA turns your Laravel app into a Progressive Web App (PWA): people can install it on their phone or desktop, like a native app. It's small and opinionated, so there's little to set up.

Add two Blade directives to your layout:

```blade
<head>
    @pwaHead
</head>

<body>
    ...
    @pwaSw
</body>
```

Then generate the manifest and the service worker:

```bash
php artisan pwa:generate
```

## What the service worker does

It keeps your app fast without ever showing stale content:

- Pages always come from the network first, so people see the latest version.
- Static files, like images and scripts, come from the cache first, so they load quickly.
- Requests from [Inertia.js](https://inertiajs.com), [Livewire](https://livewire.laravel.com) and [htmx](https://htmx.org) skip the cache entirely, so these frameworks never get an old response.

## Installation

```bash
composer require foxws/laravel-pwa
php artisan vendor:publish --tag="pwa-config"
```

## Learn more

- [Installation](installation.md)
- [Usage](usage.md): the Blade directives, icons and the manifest.
- [Configuration](configuration.md): every option in `config/pwa.php`.
