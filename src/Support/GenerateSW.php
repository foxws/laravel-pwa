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

        $swSource = Config::boolean('pwa.enabled', true)
            ? Pwa::basePath('resources/js/sw.js')
            : Pwa::basePath('resources/js/sw-unregister.js');

        // Replace both placeholders in a single pass (no-op for the unregister script)
        $swContents = str_replace(
            ['CACHE_KEY_PLACEHOLDER', 'IGNORED_PATHS_PLACEHOLDER'],
            [CacheKey::generate(), Pwa::ignorePaths()],
            File::get($swSource),
        );

        // Write the service worker file
        File::put($swPath, $swContents);
    }
}
