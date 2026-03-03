<?php

declare(strict_types=1);

namespace Foxws\Pwa\Support;

use Foxws\Pwa\Pwa;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateSW
{
    public static function run(): void
    {
        $swSource = Pwa::basePath('resources/js/sw.js');
        $swPath = Pwa::destinationPath(Config::string('pwa.sw_path'));

        // Generate a cache name based on the current timestamp to ensure it changes on each generation
        $cacheKey = CacheKey::generate();

        // Read the service worker template and replace the cache name placeholder with the generated cache value
        $swContents = Str::replaceFirst(
            'pwa-cache-v1',
            $cacheKey,
            File::get($swSource),
        );

        // Ensure the output directory exists before writing the service worker file
        File::ensureDirectoryExists(dirname($swPath));

        // Write the service worker file
        File::put($swPath, $swContents);
    }
}
