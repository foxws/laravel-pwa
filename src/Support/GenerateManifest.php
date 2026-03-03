<?php

declare(strict_types=1);

namespace Foxws\Pwa\Support;

use Foxws\Pwa\Pwa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GenerateManifest
{
    public static function run(): void
    {
        $manifest = Config::array('pwa.manifest', []);

        // Resolve icons from the dedicated icons config, building the src URL
        // via the configured Storage disk (or asset() when disk is null)
        $icons = Collection::make(Config::array('pwa.icons', []))
            ->map(function (array $icon): array {
                $disk = $icon['disk'] ?? null;
                $path = $icon['path'] ?? '';

                $src = $disk !== null
                    ? Storage::disk($disk)->url($path)
                    : asset($path);

                return array_merge($icon, ['src' => $src]);
            })
            ->values()
            ->all();

        // Only include the icons key in the manifest if there are icons configured, to avoid an empty icons array in the output
        if (filled($icons)) {
            $manifest['icons'] = $icons;
        }

        // Filter out null values to avoid including them in the manifest
        $contents = Collection::make($manifest)
            ->filter()
            ->toArray();

        // Determine the output path for the manifest.json file, defaulting to public/manifest.json
        $path = Pwa::destinationPath(Config::string('pwa.manifest_path'));

        // Ensure the output directory exists before writing the manifest file
        File::ensureDirectoryExists(dirname($path));

        // Write the manifest.json file with pretty formatting and unescaped slashes/unicode
        File::put($path, json_encode($contents, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
