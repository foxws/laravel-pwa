---
title: Introduction
metadata:
  role: Progressive Web App
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

Continue to [Installation](./installation.md) to get started.
