<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Tambah ini
use App\Models\Setting; // Tambah ini
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Fix untuk beberapa database MySQL versi lama
        Schema::defaultStringLength(191);

        // BAGIAN PENTING:
        // Cek apakah tabel settings ada datanya, jika ada, bagikan ke semua View
        try {
            $setting = Setting::first();
            // Jika database kosong (belum di-seed), buat objek dummy agar tidak error
            if (!$setting) {
                $setting = new Setting();
                $setting->shop_name = 'TokoSaya';
                $setting->shop_phone = '628123456789';
                $setting->shop_address = 'Alamat belum diatur';
            }
            View::share('setting', $setting);
        } catch (\Exception $e) {
            // Biarkan kosong jika tabel belum ada (saat migrasi awal)
        }
        Paginator::useTailwind();
        {
        Paginator::useTailwind();

        // --- SHARE DATA SETTING KE SEMUA HALAMAN ---
        // Agar {{ $setting->nama_toko }} bisa dipakai di mana saja
        if (!app()->runningInConsole()) {
            $setting = Setting::first();
            View::share('setting', $setting);
        }
    }
    }

}