<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | PWA Manifest
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for the Progressive Web Application
    | (PWA) manifest. The manifest provides metadata about the application,
    | such as its name, icons, and theme colors, which are used when the
    | application is installed on a user's device. You can customize these
    | settings to match your application's branding and requirements.
    |
    */

    'manifest' => [
        'name' => env('APP_NAME', 'Laravel'),
        'short_name' => env('PWA_SHORT_NAME', 'Laravel'),
        'background_color' => env('PWA_BACKGROUND_COLOR', '#ffffff'),
        'display' => env('PWA_DISPLAY', 'standalone'),
        'description' => env('PWA_DESCRIPTION', 'A Progressive Web Application setup for Laravel projects.'),
        'theme_color' => env('PWA_THEME_COLOR', '#6777ef'),
        'icons' => [
            [
                'src' => env('PWA_ICON_PATH', '/images/icons/icon-512x512.png'),
                'sizes' => env('PWA_ICON_SIZES', '512x512'),
                'type' => env('PWA_ICON_TYPE', 'image/png'),
            ],
        ],
    ],

];
