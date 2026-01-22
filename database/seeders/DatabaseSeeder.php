<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin
        // Passwordnya: password123
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password123'), 
        ]);

        // 2. Buat Settingan Awal Toko
        Setting::create([
            'shop_name' => 'Toko Pribadi Saya',
            'shop_tagline' => 'Murah dan Terpercaya',
            'shop_phone' => '628123456789', // Ganti dengan no WA asli nanti di admin
            'shop_address' => 'Jl. Contoh No. 1, Semarang',
            'primary_color' => '#111827', // Warna Hitam
            'wa_greeting' => 'Halo Admin, saya mau pesan produk ini:',
        ]);
    }
}