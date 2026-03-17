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
        $swSource = Pwa::basePath('resources/js/sw.js');
        $swPath = Pwa::destinationPath(Config::string('pwa.sw_path'));

        // Replace both placeholders in a single pass
        $swContents = str_replace(
            ['CACHE_KEY_PLACEHOLDER', 'IGNORED_PATHS_PLACEHOLDER'],
            [CacheKey::generate(), Pwa::ignorePaths()],
            File::get($swSource),
        );

        // Ensure the output directory exists before writing the service worker file
        File::ensureDirectoryExists(dirname($swPath));

        // Write the service worker file
        File::put($swPath, $swContents);
    }
}
