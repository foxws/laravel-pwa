<?php

declare(strict_types=1);

namespace Foxws\Pwa\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string basePath(string $path = '')
 * @method static string destinationPath(string $path)
 * @method static string ignorePaths()
 * @method static string themeColor()
 * @method static string appleTouchIcon()
 * @method static string manifestUrl()
 * @method static string swUrl()
 * @method static bool debug()
 * @method static int updateInterval()
 *
 * @see \Foxws\Pwa\Pwa
 */
class Pwa extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Foxws\Pwa\Pwa::class;
    }
}
