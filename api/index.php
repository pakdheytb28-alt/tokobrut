<?php

/*
|--------------------------------------------------------------------------
| Fix Vercel Read-Only Error
|--------------------------------------------------------------------------
| Kita paksa Laravel menggunakan folder /tmp (satu-satunya folder
| yang boleh ditulis di Vercel) untuk menyimpan cache & log.
*/

// 1. Siapkan folder writable di /tmp
$storage = '/tmp/storage';
if (!is_dir($storage)) {
    mkdir($storage, 0777, true);
    mkdir($storage . '/framework/views', 0777, true); // Tambahan untuk view cache
}

// 2. Load Aplikasi Laravel
$app = require __DIR__ . '/../bootstrap/app.php';

// 3. JURUS KUNCI: Pindahkan Storage Path ke /tmp
$app->useStoragePath($storage);

// 4. Jalankan Aplikasi (Standar Laravel)
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);