@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-6xl">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Pengaturan Toko</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola branding, logo, sosial media, dan keamanan.</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="text-blue-600 text-sm font-semibold hover:underline">
            Lihat Website <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
        </a>
    </div>

    {{-- TAMPILKAN ERROR/SUKSES --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI: FORM UTAMA --}}
        {{-- PERBAIKAN 1: SAYA TAMBAHKAN id="mainForm" DI SINI --}}
        <div class="lg:col-span-2 space-y-8">
            <form id="mainForm" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- 1. IDENTITAS TOKO --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shadow-sm"><i class="fa-solid fa-store text-sm"></i></div>
                        <h2 class="font-bold text-gray-800 text-lg">Info Dasar</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-gray-600 text-xs font-bold uppercase mb-2">Nama Toko</label>
                                <input type="text" name="shop_name" value="{{ $setting->shop_name }}" class="w-full border-gray-200 rounded-lg p-3 text-sm bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-blue-100 transition" placeholder="Nama Toko Anda">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-xs font-bold uppercase mb-2">WhatsApp</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-gray-200 bg-gray-100 text-gray-600 text-sm font-bold">+62</span>
                                    <input type="number" name="shop_phone" value="{{ $setting->shop_phone }}" class="w-full border-gray-200 rounded-r-lg p-3 text-sm bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-blue-100 transition" placeholder="812xxx">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs font-bold uppercase mb-2">Slogan</label>
                            <input type="text" name="slogan" value="{{ $setting->slogan }}" class="w-full border-gray-200 rounded-lg p-3 text-sm bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-blue-100 transition" placeholder="Contoh: Murah & Terpercaya">
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs font-bold uppercase mb-2">Alamat</label>
                            <textarea name="address" rows="2" class="w-full border-gray-200 rounded-lg p-3 text-sm bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-blue-100 transition">{{ $setting->address }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- 2. LOGO & BRANDING --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                         <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 shadow-sm"><i class="fa-solid fa-image text-sm"></i></div>
                        <h2 class="font-bold text-gray-800 text-lg">Logo & Branding</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Logo --}}
                            <div>
                                <label class="block text-gray-600 text-xs font-bold uppercase mb-2">Logo Website</label>
                                <div class="flex items-start gap-4">
                                    <div class="w-20 h-20 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden relative">
                                        @if($setting->logo_path)
                                            <img src="{{ asset('storage/' . $setting->logo_path) }}" class="w-full h-full object-contain">
                                        @else
                                            <i class="fa-solid fa-image text-gray-300 text-2xl"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="logo" class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                        @if($setting->logo_path)
                                            <div class="mt-2 flex items-center gap-2">
                                                <input type="checkbox" name="delete_logo" id="del_logo" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer">
                                                <label for="del_logo" class="text-xs text-red-600 font-bold cursor-pointer hover:underline">Hapus Logo</label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Favicon --}}
                            <div>
                                <label class="block text-gray-600 text-xs font-bold uppercase mb-2">Favicon (Ikon Tab)</label>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden">
                                        @if($setting->favicon_path)
                                            <img src="{{ asset('storage/' . $setting->favicon_path) }}" class="w-full h-full object-contain">
                                        @else
                                            <i class="fa-solid fa-globe text-gray-300"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="favicon" class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                        @if($setting->favicon_path)
                                            <div class="mt-2 flex items-center gap-2">
                                                <input type="checkbox" name="delete_favicon" id="del_fav" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer">
                                                <label for="del_fav" class="text-xs text-red-600 font-bold cursor-pointer hover:underline">Hapus Favicon</label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Banner --}}
                        <div class="mt-8">
                            <label class="block text-gray-600 text-xs font-bold uppercase mb-2">Banner Utama</label>
                            @if($setting->banner_path)
                                <div class="relative w-full h-40 bg-gray-100 rounded-lg overflow-hidden border mb-3 group">
                                    <img src="{{ asset('storage/' . $setting->banner_path) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                        <div class="bg-white p-2 rounded-lg flex items-center gap-2">
                                            <input type="checkbox" name="delete_banner" id="del_ban" value="1" class="w-5 h-5 text-red-600 rounded cursor-pointer">
                                            <label for="del_ban" class="text-red-600 font-bold text-sm cursor-pointer">Hapus Banner</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="banner" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>
                </div>

                {{-- 3. SOSIAL MEDIA --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                         <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 shadow-sm"><i class="fa-solid fa-hashtag text-sm"></i></div>
                        <h2 class="font-bold text-gray-800 text-lg">Sosial Media & Marketplace</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Facebook --}}
                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-600 uppercase mb-2"><i class="fa-brands fa-facebook text-blue-600"></i> Facebook</label>
                            <input type="text" name="facebook" value="{{ $setting->facebook }}" class="w-full border-gray-200 rounded-lg p-2 text-sm" placeholder="https://facebook.com/...">
                        </div>
                        {{-- Instagram --}}
                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-600 uppercase mb-2"><i class="fa-brands fa-instagram text-pink-600"></i> Instagram</label>
                            <input type="text" name="instagram" value="{{ $setting->instagram }}" class="w-full border-gray-200 rounded-lg p-2 text-sm" placeholder="https://instagram.com/...">
                        </div>
                        {{-- TikTok --}}
                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-600 uppercase mb-2"><i class="fa-brands fa-tiktok text-black"></i> TikTok</label>
                            <input type="text" name="tiktok" value="{{ $setting->tiktok }}" class="w-full border-gray-200 rounded-lg p-2 text-sm" placeholder="https://tiktok.com/...">
                        </div>
                        {{-- YouTube --}}
                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-600 uppercase mb-2"><i class="fa-brands fa-youtube text-red-600"></i> YouTube</label>
                            <input type="text" name="youtube" value="{{ $setting->youtube }}" class="w-full border-gray-200 rounded-lg p-2 text-sm" placeholder="https://youtube.com/...">
                        </div>
                        {{-- Shopee --}}
                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-600 uppercase mb-2"><i class="fa-solid fa-bag-shopping text-orange-500"></i> Shopee</label>
                            <input type="text" name="shopee" value="{{ $setting->shopee }}" class="w-full border-gray-200 rounded-lg p-2 text-sm" placeholder="https://shopee.co.id/...">
                        </div>
                        {{-- Tokopedia --}}
                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-600 uppercase mb-2"><i class="fa-solid fa-store text-green-600"></i> Tokopedia</label>
                            <input type="text" name="tokopedia" value="{{ $setting->tokopedia }}" class="w-full border-gray-200 rounded-lg p-2 text-sm" placeholder="https://tokopedia.com/...">
                        </div>
                    </div>
                </div>

                {{-- TOMBOL SIMPAN --}}
                <div class="mt-8 flex justify-end pb-10">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-10 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center gap-2 text-lg">
                        <i class="fa-regular fa-floppy-disk"></i> Simpan Semua
                    </button>
                </div>
            </form>
        </div>

        {{-- KOLOM KANAN: PASSWORD & TEMA --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- WARNA TEMA (DIPERBAIKI) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-purple-50 px-6 py-4 border-b border-purple-100">
                    <h2 class="font-bold text-purple-800 text-sm uppercase">Warna Tema</h2>
                </div>
                <div class="p-6">
                    {{-- PERBAIKAN 2: Hilangkan tag <form> pembungkus di sini --}}
                    {{-- Kita pakai atribut form="mainForm" agar input ini dianggap bagian dari form di kiri --}}
                    <div class="flex items-center gap-4 p-3 border border-gray-200 rounded-xl bg-gray-50 hover:bg-white transition">
                        <input type="color" name="theme_color" value="{{ $setting->theme_color ?? '#3b82f6' }}" 
                               class="w-12 h-12 rounded-lg border-0 cursor-pointer p-0 shadow-sm"
                               form="mainForm"> {{-- KUNCI PERBAIKANNYA ADA DI SINI --}}
                        
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-700">Warna Dominan</p>
                            <p class="text-[10px] text-gray-400">Klik kotak warna untuk mengganti.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ganti Password --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="bg-red-50 px-6 py-4 border-b border-red-100"><h2 class="font-bold text-red-800 text-sm uppercase">Ganti Password</h2></div>
                <div class="p-6">
                    <form action="{{ route('settings.updatePassword') }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <input type="password" name="current_password" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-red-200" placeholder="Password Lama" required>
                            <input type="password" name="password" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-red-200" placeholder="Password Baru" required>
                            <input type="password" name="password_confirmation" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-red-200" placeholder="Ulangi Baru" required>
                            <button type="submit" class="w-full bg-red-50 text-red-600 hover:bg-red-100 font-bold py-2 rounded-lg text-sm mt-2 border border-red-100 transition">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection