<?php

declare(strict_types=1);

namespace Foxws\Pwa;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

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

        return implode(', ', array_map(fn (string $path): string => "'{$path}'", $paths));
    }

    public static function themeColor(): string
    {
        return Config::string('pwa.manifest.theme_color', '#000000');
    }

    public static function appleTouchIcon(): string
    {
        return asset(Config::string('pwa.apple_touch_icon', 'logo.png'));
    }

    /**
     * The manifest URL, suffixed with the file's last-modified time so
     * browsers and intermediate caches refetch it after every pwa:generate
     * run instead of serving a stale copy indefinitely.
     */
    public static function manifestUrl(): string
    {
        return once(function () {
            $path = Config::string('pwa.manifest_path', 'manifest.json');
            $destination = self::destinationPath($path);

            $version = File::exists($destination) ? File::lastModified($destination) : null;

            return filled($version) ? asset($path).'?v='.$version : asset($path);
        });
    }

    public static function swUrl(): string
    {
        return asset(Config::string('pwa.sw_path', 'sw.js'));
    }

    public static function debug(): bool
    {
        return Config::boolean('app.debug', false);
    }

    public static function updateInterval(): int
    {
        return Config::integer('pwa.update_interval', 24);
    }
}
