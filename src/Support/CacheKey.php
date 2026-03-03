<?php

declare(strict_types=1);

namespace Foxws\Pwa\Support;

class CacheKey
{
    /**
     * Generate a cache name derived from the current Unix timestamp.
     * The name rotates on every pwa:generate run, ensuring clients
     * always receive a fresh cache after each deployment.
     */
    public static function generate(): string
    {
        return 'pwa-' . (string) now()->getTimestamp();
    }
}
