<?php

declare(strict_types=1);

use Foxws\Pwa\Pwa;
use Foxws\Pwa\Support\CacheKey;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Once;

it('generates manifest.json in the public directory', function () {
    $path = public_path('manifest.json');

    File::delete($path);

    Artisan::call('pwa:generate');

    expect(File::exists($path))->toBeTrue();
});

it('manifest.json contains required keys', function () {
    Artisan::call('pwa:generate');

    $manifest = json_decode(File::get(public_path('manifest.json')), true);

    expect($manifest)
        ->toHaveKey('name')
        ->toHaveKey('short_name')
        ->toHaveKey('start_url')
        ->toHaveKey('display')
        ->toHaveKey('icons');
});

it('omits null values from manifest.json', function () {
    config()->set('pwa.manifest.shortcuts', null);
    config()->set('pwa.manifest.screenshots', null);

    Artisan::call('pwa:generate');

    $manifest = json_decode(File::get(public_path('manifest.json')), true);

    expect($manifest)
        ->not->toHaveKey('shortcuts')
        ->not->toHaveKey('screenshots');
});

it('respects manifest_path config for output location', function () {
    config()->set('pwa.manifest_path', 'pwa/app.json');

    $path = public_path('pwa/app.json');
    File::delete($path);

    Artisan::call('pwa:generate');

    expect(File::exists($path))->toBeTrue();

    File::delete($path);
});

it('publishes sw.js to the public directory', function () {
    $path = public_path('sw.js');

    File::delete($path);

    Artisan::call('pwa:generate');

    expect(File::exists($path))->toBeTrue();
});

it('respects sw_path config for service worker location', function () {
    config()->set('pwa.sw_path', 'custom-sw.js');

    $path = public_path('custom-sw.js');
    File::delete($path);

    Artisan::call('pwa:generate');

    expect(File::exists($path))->toBeTrue();

    File::delete($path);
});

it('injects a generated cache name into sw.js replacing the placeholder', function () {
    Artisan::call('pwa:generate');

    $contents = File::get(public_path('sw.js'));

    expect($contents)
        ->not->toContain('"sw-cache-v1"')
        ->toContain('"sw-');
});

it('CacheKey::generate returns a sw- prefixed timestamp string', function () {
    $key = CacheKey::generate();

    expect($key)
        ->toStartWith('sw-')
        ->toMatch('/^sw-\d+$/');
});

it('two CacheKey::generate calls within the same second return the same value', function () {
    expect(CacheKey::generate())->toBe(CacheKey::generate());
});

it('renders @pwaHead directive', function () {
    $view = Blade::render('@pwaHead');

    expect($view)
        ->toContain('<meta name="theme-color"')
        ->toContain('<link rel="apple-touch-icon"')
        ->toContain('<link rel="manifest"');
});

it('renders @pwaSw directive', function () {
    $view = Blade::render('@pwaSw');

    expect($view)
        ->toContain('navigator.serviceWorker')
        ->toContain('.register(');
});

it('resolves icon src via storage disk url', function () {
    Storage::fake('public');

    Config::set('pwa.icons', [
        [
            'disk' => 'public',
            'path' => 'images/icons/icon-512x512.png',
            'sizes' => '512x512',
            'type' => 'image/png',
        ],
    ]);

    Artisan::call('pwa:generate');

    $manifest = json_decode(File::get(public_path('manifest.json')), true);
    $src = $manifest['icons'][0]['src'];

    expect($src)->toBe(Storage::disk('public')->url('images/icons/icon-512x512.png'));
});

it('resolves icon src via path when disk is null', function () {
    Config::set('pwa.icons', [
        [
            'disk' => null,
            'path' => '/storage/images/icons/icon-512x512.png',
            'sizes' => '512x512',
            'type' => 'image/png',
        ],
    ]);

    Artisan::call('pwa:generate');

    $manifest = json_decode(File::get(public_path('manifest.json')), true);
    $src = $manifest['icons'][0]['src'];

    expect($src)->toBe('/storage/images/icons/icon-512x512.png');
});

it('resolves icon src via a custom s3 disk', function () {
    Storage::fake('s3');

    Config::set('pwa.icons', [
        [
            'disk' => 's3',
            'path' => 'icons/icon-512x512.png',
            'sizes' => '512x512',
            'type' => 'image/png',
        ],
    ]);

    Artisan::call('pwa:generate');

    $manifest = json_decode(File::get(public_path('manifest.json')), true);
    $src = $manifest['icons'][0]['src'];

    expect($src)->toBe(Storage::disk('s3')->url('icons/icon-512x512.png'));
});

it('omits icons from manifest when icons config is empty', function () {
    Config::set('pwa.manifest.icons', null);
    Config::set('pwa.icons', []);

    Artisan::call('pwa:generate');

    $manifest = json_decode(File::get(public_path('manifest.json')), true);

    expect($manifest)->not->toHaveKey('icons');
});

it('suffixes the manifest URL with the file\'s last-modified time', function () {
    Artisan::call('pwa:generate');

    $expected = File::lastModified(public_path('manifest.json'));

    expect(Pwa::manifestUrl())->toEndWith('/manifest.json?v='.$expected);
});

it('memoizes the manifest URL for the duration of a request via once()', function () {
    Artisan::call('pwa:generate');

    $first = Pwa::manifestUrl();
    touch(public_path('manifest.json'), File::lastModified(public_path('manifest.json')) + 60);

    expect(Pwa::manifestUrl())->toBe($first);
});

it('changes the manifest URL version on the next request after regenerating the manifest', function () {
    Artisan::call('pwa:generate');
    $first = Pwa::manifestUrl();

    // Simulates the request boundary: Octane's FlushOnce listener resets
    // this cache between requests, so the next request sees the new mtime.
    Once::flush();
    touch(public_path('manifest.json'), File::lastModified(public_path('manifest.json')) + 60);

    expect(Pwa::manifestUrl())->not->toBe($first);
});

it('returns an unversioned manifest URL when the file has not been generated yet', function () {
    File::delete(public_path('manifest.json'));

    expect(Pwa::manifestUrl())->toBe(asset('manifest.json'));
});
