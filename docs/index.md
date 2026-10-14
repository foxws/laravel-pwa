---
title: Introduction
metadata:
  role: Progressive Web App
  group: deploy
  eyebrow: "PWA · Service Worker · Manifest"
  desc: "Turn your Laravel app into an installable, offline-friendly PWA."
  requires: "PHP ^8.3"
  laravel: "12.x / 13.x"
  licence: MIT
---

# Introduction

Laravel PWA turns your Laravel app into a Progressive Web App (PWA). It's
small and opinionated, so there isn't much to set up.

It gives you:

- Blade directives that add the PWA head tags and register the service
  worker.
- An Artisan command that generates your `manifest.json` file and publishes
  a `sw.js` service worker.

The service worker keeps things fast without showing stale content:

- Pages are always fetched from the network first, so users see the latest
  version.
- Static assets, like images and scripts, are served from the cache first,
  so they load quickly.

It also skips the cache for requests from
[Inertia.js](https://inertiajs.com) (`X-Inertia`),
[Livewire](https://livewire.laravel.com) (`X-Livewire`,
`X-Livewire-Navigate`), and [htmx](https://htmx.org) (`HX-Request`), so
these frameworks never receive a stale, cached response.

> [!TIP]
> Let your AI agent do the hard parts. This package ships a [Laravel Boost](https://laravel.com/docs/boost) skill, `pwa-development`, that teaches your agent how to install, configure and use Laravel PWA. Install Boost and select the skill when prompted:
>
> ```bash
> composer require laravel/boost --dev
> php artisan boost:install
> ```
>
> Already using Boost? Run `php artisan boost:update --discover` to pick up the skill. Then just ask, for example: *"Turn this app into an installable PWA with our logo as the app icon."*

Continue to [Installation](./installation.md) to get started.
