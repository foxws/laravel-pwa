<?php

namespace Foxws\Pwa\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class GenerateManifestCommand extends Command
{
    public $signature = 'pwa:generate';

    public $description = 'Generate the PWA manifest.json file';

    public function handle(): int
    {
        // Get the manifest configuration
        $manifest = Config::array('pwa.manifest', []);

        // Filter out null values to avoid including them in the manifest
        $contents = Collection::make($manifest)
            ->filter()
            ->toArray();

        // Determine the output path, defaulting to public/manifest.json
        $path = public_path($this->option('path') ?? Config::string('pwa.path', 'manifest.json'));

        // Ensure the directory exists and write the manifest.json file
        File::ensureDirectoryExists(dirname($path));

        // Write the manifest.json file with pretty print and unescaped slashes/unicode
        File::put($path, json_encode($contents, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->components->info("Manifest written to: {$path}");

        return self::SUCCESS;
    }
}
