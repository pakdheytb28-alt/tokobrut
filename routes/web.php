<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| FILE ROUTE UTAMA
|
*/

// ====================================================
// 1. HALAMAN DEPAN (FRONTEND)
// ====================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/p/{slug}', [FrontController::class, 'show'])->name('product.detail');

// Keranjang
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
// Route khusus Beli Langsung (Add to Cart + Redirect to Checkout)
Route::get('/buy-now/{id}', [App\Http\Controllers\CartController::class, 'buyNow'])->name('cart.buyNow');
Route::post('/checkout/process', [CheckoutController::class, 'process'])
    ->name('checkout.process')
    ->middleware('throttle:3,1');


// ====================================================
// 2. OTENTIKASI (LOGIN ADMIN)
// ====================================================

// JALUR UTAMA (Standard)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// JALUR ALTERNATIF (Penyebab Error Tadi)
// Kita izinkan GET (Buka Halaman) DAN POST (Kirim Login)
Route::get('/admin/login', [AuthController::class, 'showLoginForm']); 
Route::post('/admin/login', [AuthController::class, 'login']); // <--- INI OBATNYA


// ====================================================
// 3. HALAMAN ADMIN (BACKEND)
// ====================================================

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Hapus Massal (Wajib di atas resource)
    Route::delete('/products/bulk-delete', [ProductController::class, 'bulkDestroy'])->name('products.bulkDelete');
    Route::resource('products', ProductController::class);

    Route::delete('/categories/bulk-delete', [CategoryController::class, 'bulkDestroy'])->name('categories.bulkDelete');
    Route::resource('categories', CategoryController::class);

    // Pengaturan
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.updatePassword');

    // --- MANAJEMEN PESANAN (ORDERS) ---
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/shipped', [App\Http\Controllers\OrderController::class, 'markAsShipped'])->name('orders.shipped');
    // --- CHECKOUT (FORMULIR & PROSES) ---
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::delete('/orders/{id}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');
    
});

// --- JALUR PERBAIKAN: TAMBAH SOSMED EXTRA ---
// --- SETUP DATABASE PESANAN (JALANKAN SEKALI) ---

// --- JALUR DARURAT: PERBAIKI DATABASE (VERSI PASTI AMAN) ---
Route::get('/perbaiki-database-varian', function () {
    $tableName = 'products';
    $pesan = "<h1>Status Perbaikan Database:</h1><ul>";

    try {
        // 1. Cek & Buat Kolom 'variant_name'
        if (!\Illuminate\Support\Facades\Schema::hasColumn($tableName, 'variant_name')) {
            \Illuminate\Support\Facades\Schema::table($tableName, function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('variant_name')->nullable()->after('stock');
            });
            $pesan .= "<li style='color:green'>BERHASIL: Kolom 'variant_name' dibuat.</li>";
        } else {
            $pesan .= "<li style='color:blue'>INFO: Kolom 'variant_name' sudah ada.</li>";
        }

        // 2. Cek & Buat Kolom 'variant_options'
        if (!\Illuminate\Support\Facades\Schema::hasColumn($tableName, 'variant_options')) {
            \Illuminate\Support\Facades\Schema::table($tableName, function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->text('variant_options')->nullable()->after('variant_name');
            });
            $pesan .= "<li style='color:green'>BERHASIL: Kolom 'variant_options' dibuat.</li>";
        } else {
            $pesan .= "<li style='color:blue'>INFO: Kolom 'variant_options' sudah ada.</li>";
        }
        
        $pesan .= "</ul><h3>SELESAI! Database sudah siap. Silakan Edit Produk lagi.</h3>";
        return $pesan;

    } catch (\Exception $e) {
        return "<h3 style='color:red'>ERROR: " . $e->getMessage() . "</h3>";
    }
});