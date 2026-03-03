<?php

declare(strict_types=1);

namespace Foxws\Pwa\Commands;

use Foxws\Pwa\Support\GenerateManifest;
use Foxws\Pwa\Support\GenerateSW;
use Illuminate\Console\Command;

class GenerateManifestCommand extends Command
{
    public $signature = 'pwa:generate';

    public $description = 'Generate the PWA manifest.json file';

    public function handle(): int
    {
        // Generate the manifest.json file
        GenerateManifest::run();

        // Generate the service worker file
        GenerateSW::run();

        $this->components->info('PWA manifest and service worker generated successfully.');

        return self::SUCCESS;
    }
}
