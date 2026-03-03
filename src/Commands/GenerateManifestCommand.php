<?php

declare(strict_types=1);

namespace Foxws\Pwa\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GenerateManifestCommand extends Command
{
    public $signature = 'pwa:generate';

    public $description = 'Generate the PWA manifest.json file';

    public function handle(): int
    {
        // Get the manifest configuration
        $manifest = Config::array('pwa.manifest', []);

        // Resolve icons from the dedicated icons config, building the src URL
        // via the configured Storage disk (or asset() when disk is null).
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

        if (filled($icons)) {
            $manifest['icons'] = $icons;
        }

        // Filter out null values to avoid including them in the manifest
        $contents = Collection::make($manifest)
            ->filter()
            ->toArray();

        // Determine the output path, defaulting to public/manifest.json
        $path = public_path(Config::string('pwa.manifest_path', 'manifest.json'));

        // Ensure the directory exists and write the manifest.json file
        File::ensureDirectoryExists(dirname($path));

        // Write the manifest.json file with pretty print and unescaped slashes/unicode
        File::put($path, json_encode($contents, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->components->info("Manifest written to: {$path}");

        $swSource = __DIR__.'/../../resources/js/sw.js';
        $swPath = public_path(Config::string('pwa.sw_path', 'sw.js'));

        File::ensureDirectoryExists(dirname($swPath));
        File::copy($swSource, $swPath);

        $this->components->info("Service worker written to: {$swPath}");

        return self::SUCCESS;
    }
}
