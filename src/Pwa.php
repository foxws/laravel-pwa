<?php

declare(strict_types=1);

namespace Foxws\Pwa;

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
}
