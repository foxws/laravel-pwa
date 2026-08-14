---
sidebar_position: 1
---

# Introduction

A minimal, opinionated Progressive Web App (PWA) package for Laravel. It
provides Blade directives for the PWA head and service worker registration,
and an Artisan command to generate your `manifest.json` and publish a `sw.js`
stub.

The included service worker uses a network-first strategy for navigation and
cache-first for static assets, while intentionally bypassing the cache for
[Inertia.js](https://inertiajs.com) (`X-Inertia`) and
[Livewire](https://livewire.laravel.com) (`X-Livewire`) requests to prevent
stale responses.

Continue to [Installation](./installation.md) to get started.
