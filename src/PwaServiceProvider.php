<?php

declare(strict_types=1);

namespace Foxws\Pwa;

use Foxws\Pwa\Commands\GenerateManifestCommand;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PwaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-pwa')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(GenerateManifestCommand::class);
    }

    public function packageBooted(): void
    {
        Blade::include('pwa::components.head', 'pwaHead');
        Blade::include('pwa::components.body', 'pwaSw');
    }
}
