<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | PWA Manifest Output Path
    |--------------------------------------------------------------------------
    |
    | This option defines the output path for the generated manifest.json file.
    | The path is relative to the public/ directory. By default, it will be
    | generated at public/manifest.json.
    |
    */

    'path' => env('PWA_MANIFEST_PATH', 'manifest.json'),

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
        'id'               => env('PWA_ID', '/'),
        'name'             => env('APP_NAME', 'Laravel'),
        'short_name'       => env('PWA_SHORT_NAME', 'Laravel'),
        'description'      => env('PWA_DESCRIPTION', 'A Progressive Web Application setup for Laravel projects.'),
        'start_url'        => env('PWA_START_URL', '/'),
        'scope'            => env('PWA_SCOPE', '/'),
        'display'          => env('PWA_DISPLAY', 'standalone'),
        'orientation'      => env('PWA_ORIENTATION', 'any'),
        'background_color' => env('PWA_BACKGROUND_COLOR', '#ffffff'),
        'theme_color'      => env('PWA_THEME_COLOR', '#6777ef'),
        'lang'             => env('PWA_LANG', 'en'),
        'dir'              => env('PWA_DIR', 'ltr'),
        'icons' => [
            [
                'src'     => env('PWA_ICON_PATH', '/images/icons/icon-512x512.png'),
                'sizes'   => env('PWA_ICON_SIZES', '512x512'),
                'type'    => env('PWA_ICON_TYPE', 'image/png'),
                'purpose' => 'any maskable',
            ],
        ],

        // Advanced — add any additional manifest keys here. Null values are
        // omitted from the generated manifest.json automatically.
        'display_override'            => null, // e.g. ['window-controls-overlay', 'standalone']
        'prefer_related_applications' => null, // e.g. true
        'categories'                  => null, // e.g. ['productivity', 'utilities']
        'screenshots'                 => null, // e.g. [['src' => '/screenshots/1.png', 'sizes' => '1280x720', 'type' => 'image/png']]
        'shortcuts'                   => null, // e.g. [['name' => 'Home', 'url' => '/', 'icons' => [...]]]
    ],

];
