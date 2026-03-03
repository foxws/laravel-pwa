<?php

namespace Foxws\Pwa\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Foxws\Pwa\Pwa
 */
class Pwa extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Foxws\Pwa\Pwa::class;
    }
}
