<?php

declare(strict_types=1);

namespace Foxws\Pwa\Tests;

use Foxws\Pwa\PwaServiceProvider;
use Illuminate\Support\Once;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mirrors Laravel Octane's FlushOnce listener, which resets the
        // once() cache between requests. Without this, memoized values
        // (e.g. Pwa::manifestUrl()) would leak across test cases since
        // they all run within the same PHP process.
        Once::flush();
    }

    protected function getPackageProviders($app)
    {
        return [
            PwaServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // config()->set('database.default', 'testing');

        /*
         foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__ . '/../database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
         }
         */
    }
}
