<?php

// 1. PENTING: Load "Kamus" (Autoloader) agar Laravel dikenali
require __DIR__ . '/../vendor/autoload.php';

// 2. Load Aplikasi Laravel
$app = require __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Fix Vercel Read-Only Error (Jurus Anti-Gagal)
|--------------------------------------------------------------------------
| Kita paksa Laravel menggunakan folder /tmp untuk menyimpan cache & log.
| Folder /tmp adalah satu-satunya tempat yang boleh ditulis di Vercel.
*/

$storage = '/tmp/storage';

// Buat folder storage sementara jika belum ada
if (!is_dir($storage)) {
    mkdir($storage, 0777, true);
    mkdir($storage . '/framework/views', 0777, true);
}

// Perintahkan Laravel untuk memakai folder /tmp tersebut
$app->useStoragePath($storage);

// 3. Jalankan Aplikasi
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);