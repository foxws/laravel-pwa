<?php

declare(strict_types=1);

namespace Foxws\Pwa;

use Illuminate\Support\Facades\Config;

class Pwa
{
    public static function basePath(string $path = ''): string
    {
        return dirname(__DIR__).($path !== '' ? '/'.ltrim($path, '/') : '');
    }

    public static function destinationPath(string $configKey, string $default = ''): string
    {
        return public_path(Config::string($configKey, $default));
    }
}
