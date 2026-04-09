<?php

declare(strict_types=1);

namespace Foxws\Pwa\Support;

use Foxws\Pwa\Pwa;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class GenerateSW
{
    public static function run(): void
    {
        $swPath = Pwa::destinationPath(Config::string('pwa.sw_path'));

        // Ensure the output directory exists before writing the service worker file
        File::ensureDirectoryExists(dirname($swPath));

        if (! Config::boolean('pwa.enabled', true)) {
            // Write a self-unregistering SW so any previously cached assets are
            // cleared and the service worker is removed on the next page load.
            File::put($swPath, self::unregisterScript());

            return;
        }

        $swSource = Pwa::basePath('resources/js/sw.js');

        // Replace both placeholders in a single pass
        $swContents = str_replace(
            ['CACHE_KEY_PLACEHOLDER', 'IGNORED_PATHS_PLACEHOLDER'],
            [CacheKey::generate(), Pwa::ignorePaths()],
            File::get($swSource),
        );

        // Write the service worker file
        File::put($swPath, $swContents);
    }

    private static function unregisterScript(): string
    {
        return <<<'JS'
"use strict";

self.addEventListener("install", () => self.skipWaiting());

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) => Promise.all(keys.map((key) => caches.delete(key))))
            .then(() => self.registration.unregister()),
    );
});
JS;
    }
