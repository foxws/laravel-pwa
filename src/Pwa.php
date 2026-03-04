<?php

declare(strict_types=1);

namespace Foxws\Pwa;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class Pwa
{
    public static function basePath(string $path = ''): string
    {
        return dirname(__DIR__).($path !== '' ? '/'.ltrim($path, '/') : '');
    }

    public static function destinationPath(string $path): string
    {
        return public_path($path);
    }

    public static function ignorePaths(): string
    {
        $paths = Config::array('pwa.ignore_paths', []);

        return Collection::make($paths)
            ->map(fn (string $path): string => "'{$path}'")
            ->implode(', ');
    }
}
