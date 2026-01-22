@extends('layouts.admin')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Ringkasan Toko</h1>
        <p class="text-gray-500 text-sm mt-1">Pantau performa tokomu hari ini.</p>
    </div>

    {{-- 1. KARTU STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        {{-- Card: Total Produk --}}
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Total Produk</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ \App\Models\Product::count() }}</h3>
                <p class="text-xs text-green-500 mt-2 font-medium flex items-center">
                    <i class="fa-solid fa-arrow-up mr-1"></i> Aktif dijual
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 text-xl">
                <i class="fa-solid fa-box"></i>
            </div>
        </div>

        {{-- Card: Kategori --}}
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Kategori</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ \App\Models\Category::count() }}</h3>
                <p class="text-xs text-blue-500 mt-2 font-medium flex items-center">
                    <i class="fa-solid fa-layer-group mr-1"></i> Variasi item
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-600 text-xl">
                <i class="fa-solid fa-tags"></i>
            </div>
        </div>

        {{-- Card: Total Stok (Opsional) --}}
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Total Stok Fisik</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ \App\Models\Product::sum('stock') }}</h3>
                <p class="text-xs text-orange-500 mt-2 font-medium flex items-center">
                    <i class="fa-solid fa-warehouse mr-1"></i> Unit barang
                </p>
            </div>
            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-orange-600 text-xl">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>
    </div>

    {{-- 2. MENU CEPAT (SHORTCUT) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Kolom Kiri: Quick Action --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fa-solid fa-rocket text-blue-600 mr-2"></i> Aksi Cepat
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('products.create') }}" class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition group cursor-pointer">
                    <i class="fa-solid fa-plus-circle text-2xl text-gray-400 group-hover:text-blue-600 mb-2"></i>
                    <span class="text-sm font-medium text-gray-600 group-hover:text-blue-700">Tambah Produk</span>
                </a>
                <a href="{{ route('categories.create') }}" class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition group cursor-pointer">
                    <i class="fa-solid fa-folder-plus text-2xl text-gray-400 group-hover:text-purple-600 mb-2"></i>
                    <span class="text-sm font-medium text-gray-600 group-hover:text-purple-700">Tambah Kategori</span>
                </a>
            </div>
        </div>

        {{-- Kolom Kanan: Info (Banner) --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-md p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xl font-bold mb-2">Kelola Toko Lebih Mudah!</h3>
                <p class="text-blue-100 text-sm mb-4 max-w-xs">Update stok, ganti harga, dan atur produk unggulan agar pembeli makin tertarik.</p>
                <a href="{{ route('settings.index') }}" class="inline-block bg-white text-blue-700 font-bold text-xs px-4 py-2 rounded shadow hover:bg-gray-100 transition">
                    Atur Info Toko
                </a>
            </div>
            {{-- Hiasan Background --}}
            <i class="fa-solid fa-store absolute -bottom-4 -right-4 text-9xl text-white opacity-10"></i>
        </div>
    </div>

@endsection