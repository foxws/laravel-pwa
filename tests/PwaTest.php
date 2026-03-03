<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

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

it('renders @pwa directive', function () {
    $view = Blade::render('@pwa');

    expect($view)
        ->toContain('<meta name="theme-color"')
        ->toContain('<link rel="apple-touch-icon"')
        ->toContain('<link rel="manifest"');
});

it('renders @sw directive', function () {
    $view = Blade::render('@sw');

    expect($view)
        ->toContain('navigator.serviceWorker')
        ->toContain('.register(');
});
