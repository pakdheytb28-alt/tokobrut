<?php

// 1. PENTING: Load "Kamus" Laravel (Autoloader) dulu!
require __DIR__ . '/../vendor/autoload.php';

// 2. Load Aplikasi Laravel
$app = require __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Fix Vercel Read-Only Error
|--------------------------------------------------------------------------
*/
$storage = '/tmp/storage';

// Buat folder storage sementara di /tmp jika belum ada
if (!is_dir($storage)) {
    mkdir($storage, 0777, true);
    mkdir($storage . '/framework/views', 0777, true);
}

// Paksa Laravel menggunakan storage di /tmp (karena Vercel Read-Only)
$app->useStoragePath($storage);

// 3. Jalankan Aplikasi
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);