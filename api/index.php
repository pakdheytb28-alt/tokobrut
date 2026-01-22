<?php

// 1. Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Fix Vercel Read-Only Error (Versi Ultimate)
|--------------------------------------------------------------------------
| PENTING: Kita harus menyiapkan folder /tmp dan memberitahu Laravel
| untuk memakainya SEBELUM aplikasi dimulai (bootstrapped).
*/

// Tentukan jalur penyimpanan sementara di /tmp (memori Vercel)
$tmpPath = '/tmp';
$storagePath = $tmpPath . '/storage';
$cachePath = $tmpPath . '/bootstrap/cache';

// Buat folder-folder tersebut jika belum ada
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0777, true);
    mkdir($storagePath . '/framework/views', 0777, true); // Untuk View Cache
}
if (!is_dir($cachePath)) {
    mkdir($cachePath, 0777, true);
}

// 2. Trik Sulap: Paksa Laravel pakai file cache di /tmp
// Kita set Environment Variable ini SEBELUM memanggil bootstrap/app.php
// agar PackageManifest langsung mengarah ke sini.
$_ENV['APP_PACKAGES_CACHE'] = $cachePath . '/packages.php';
$_ENV['APP_SERVICES_CACHE'] = $cachePath . '/services.php';
$_ENV['APP_ROUTES_CACHE'] = $cachePath . '/routes-v7.php';
$_ENV['APP_CONFIG_CACHE'] = $cachePath . '/config.php';
$_ENV['APP_EVENTS_CACHE'] = $cachePath . '/events.php';

putenv('APP_PACKAGES_CACHE=' . $_ENV['APP_PACKAGES_CACHE']);
putenv('APP_SERVICES_CACHE=' . $_ENV['APP_SERVICES_CACHE']);
putenv('APP_ROUTES_CACHE=' . $_ENV['APP_ROUTES_CACHE']);
putenv('APP_CONFIG_CACHE=' . $_ENV['APP_CONFIG_CACHE']);
putenv('APP_EVENTS_CACHE=' . $_ENV['APP_EVENTS_CACHE']);

// 3. Baru sekarang kita panggil Aplikasi Laravel
$app = require __DIR__ . '/../bootstrap/app.php';

// 4. Pindahkan path storage utama ke /tmp juga
$app->useStoragePath($storagePath);

// 5. Jalankan Aplikasi
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);