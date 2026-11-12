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

    /**
     * The ignored path prefixes as JavaScript string literals, for the service
     * worker's IGNORED_PATHS array. The config value is a list, or a
     * comma-separated string from the PWA_IGNORE_PATHS env variable.
     */
    public static function ignorePaths(): string
    {
        $paths = Config::get('pwa.ignore_paths', []);

        if (is_string($paths)) {
            $paths = explode(',', $paths);
        }

        $paths = array_filter(array_map(
            fn (mixed $path): string => is_scalar($path) ? trim((string) $path) : '',
            (array) $paths,
        ));

        return implode(', ', array_map(
            fn (string $path): string => json_encode($path, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            $paths,
        ));
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
        return once(fn () => self::versionedAssetUrl(Config::string('pwa.manifest_path', 'manifest.json')));
    }

    /**
     * The service worker URL, suffixed with the file's last-modified time.
     * `updateViaCache: "none"` should already keep browsers from caching
     * this script, but some proxies/CDNs cache it anyway, so this guards
     * against that by changing the URL after every pwa:generate run.
     */
    public static function swUrl(): string
    {
        return once(fn () => self::versionedAssetUrl(Config::string('pwa.sw_path', 'sw.js')));
    }

    /**
     * Builds the public URL for a generated asset, suffixed with its
     * last-modified time to bust stale caches after each pwa:generate run.
     */
    private static function versionedAssetUrl(string $path): string
    {
        $destination = self::destinationPath($path);

        $version = File::exists($destination) ? File::lastModified($destination) : null;

        return filled($version) ? asset($path).'?v='.$version : asset($path);
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
