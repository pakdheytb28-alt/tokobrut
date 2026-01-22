@extends('layouts.frontend')

@section('content')

{{-- 1. BANNER HERO SECTION --}}
<div class="w-full mb-6">
    <div class="rounded-lg overflow-hidden shadow-md relative aspect-[3/1] md:aspect-[4/1] flex items-center justify-center"
         style="background-color: {{ $setting->banner_path ? '#000' : ($setting->theme_color ?? '#333') }};">
        
        @if(isset($setting->banner_path) && $setting->banner_path)
            <img src="{{ asset('storage/' . $setting->banner_path) }}" 
                 class="absolute inset-0 w-full h-full object-cover opacity-90">
        @endif

        {{-- Teks Banner (Opsional, jika tidak ada gambar) --}}
        <div class="relative z-10 text-center px-4">
            <h1 class="text-2xl md:text-4xl font-bold text-white drop-shadow-md mb-1 tracking-wide">
                {{ $setting->shop_name ?? 'Selamat Datang' }}
            </h1>
            <p class="text-sm md:text-lg text-white/90 font-medium drop-shadow-sm">
                {{ $setting->slogan ?? 'Belanja Murah & Terpercaya' }}
            </p>
        </div>
    </div>
</div>

{{-- 2. MENU KATEGORI --}}
<div class="bg-white p-4 mb-6 shadow-sm rounded-lg border border-gray-100">
    <div class="grid grid-cols-4 md:grid-cols-8 gap-6">
        <a href="{{ route('home') }}" class="flex flex-col items-center group hover:-translate-y-1 transition">
            <div class="w-12 h-12 rounded-full border border-gray-100 bg-white flex items-center justify-center group-hover:bg-blue-50 transition shadow-sm">
                <i class="fa-solid fa-border-all text-blue-600 text-xl"></i>
            </div>
            <div class="text-[10px] md:text-xs text-center text-gray-600 mt-2 font-medium">Semua</div>
        </a>
       @foreach($categories as $cat)
            <a href="{{ route('home', ['category' => $cat->slug]) }}" class="flex flex-col items-center group hover:-translate-y-1 transition">
                <div class="w-12 h-12 rounded-full border border-gray-100 bg-white flex items-center justify-center group-hover:bg-blue-50 transition shadow-sm">
                    {{-- INI KUNCINYA: PANGGIL IKON DARI DATABASE --}}
                    {{-- Jika database ada isinya pakai $cat->icon, jika kosong pakai fa-box --}}
                    <i class="{{ $cat->icon ?? 'fa-solid fa-box' }} text-gray-400 text-xl group-hover:text-blue-600"></i>
                </div>
                <div class="text-[10px] md:text-xs text-center text-gray-600 mt-2 font-medium line-clamp-1">{{ $cat->name }}</div>
            </a>
        @endforeach
    </div>
</div>

{{-- 3. PRODUK UNGGULAN (MODEL GRID / TAMPIL SEMUA) --}}
@if(isset($featuredProducts) && count($featuredProducts) > 0)
<div class="mb-8 mt-4">
    <div class="flex items-center justify-between mb-4 px-1">
        <h2 class="text-lg md:text-xl font-bold text-gray-800 uppercase tracking-wide flex items-center gap-2">
            <i class="fa-solid fa-star text-yellow-500"></i> PRODUK UNGGULAN
        </h2>
    </div>

    {{-- UBAH DARI FLEX (SLIDER) KE GRID (KOTAK-KOTAK) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach($featuredProducts as $product)
            
            <a href="{{ route('product.detail', $product->slug) }}" 
               class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-blue-500 transition duration-300 relative group flex flex-col h-full overflow-hidden">
                
                {{-- Foto --}}
                <div class="relative w-full aspect-square bg-gray-100 overflow-hidden">
                    @if($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i class="fa-regular fa-image text-3xl"></i>
                        </div>
                    @endif
                    
                    {{-- Badge Unggulan --}}
                    <div class="absolute top-2 right-2 bg-yellow-400 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>

                {{-- Info Produk --}}
                <div class="p-3 flex flex-col flex-grow">
                    <h3 class="text-sm text-gray-800 font-medium line-clamp-2 h-10 mb-1 leading-snug">
                        {{ $product->name }}
                    </h3>
                    
                    {{-- Harga & Stok --}}
                    <div class="mt-auto">
                        <div class="text-blue-600 font-bold text-base">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        <div class="text-[10px] text-gray-400 mt-1">
                            Stok: {{ $product->stock }}
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

{{-- 4. REKOMENDASI (GRID - BERSIH & RAPI) --}}
<div class="bg-white p-4 border-t-4 border-gray-100 rounded-t-xl">
    <div class="flex items-center gap-2 mb-4">
        <i class="fa-solid fa-thumbs-up text-blue-600"></i>
        <h2 class="text-base md:text-lg font-bold text-gray-800 uppercase">Rekomendasi Untukmu</h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        @forelse($products as $product)
            {{-- KARTU PRODUK GRID --}}
            {{-- 'flex flex-col h-full' menjamin kartu sama tinggi --}}
            <a href="{{ route('product.detail', $product->slug) }}" class="bg-white border border-gray-100 hover:border-blue-500 rounded-lg overflow-hidden hover:shadow-lg transition group flex flex-col h-full">
                
                {{-- Foto --}}
                <div class="relative w-full aspect-square bg-gray-100">
                    @if($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-regular fa-image text-3xl"></i></div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-3 flex flex-col flex-grow">
                    {{-- Nama Produk --}}
                    <div class="text-sm text-gray-800 font-medium line-clamp-2 mb-2 h-10 leading-snug group-hover:text-blue-600 transition">
                        {{ $product->name }}
                    </div>
                    
                    {{-- Harga (mt-auto agar selalu menempel di bawah) --}}
                    <div class="mt-auto">
                        <div class="text-blue-600 font-bold text-base">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="text-[10px] text-gray-400 mt-1">Stok: {{ $product->stock }}</div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-10 text-center text-gray-400">Belum ada produk.</div>
        @endforelse
    </div>

    {{-- Pagination (Tombol Halaman 1, 2, 3...) --}}
    <div class="mt-8 px-4 pb-8">
        {{ $products->links() }}
    </div>
</div>

@endsection