<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $setting->meta_description ?? 'Belanja Online Aman dan Nyaman' }}">
    <title>{{ $setting->shop_name ?? 'TokoSaya' }} | {{ $setting->slogan ?? 'Jual Beli Online' }}</title>
    
    @if(isset($setting) && $setting->favicon_path)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $setting->favicon_path) }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #F5F5F5; }
        :root { --primary-color: {{ $setting->theme_color ?? '#EE4D2D' }}; }
        
        .bg-primary { background-color: var(--primary-color) !important; }
        .text-primary { color: var(--primary-color) !important; }
        .border-primary { border-color: var(--primary-color) !important; }
        
        /* Shopee-like Gradient Header */
        .header-gradient {
            background: linear-gradient(-180deg, var(--primary-color), var(--primary-color));
        }
    </style>
</head>
<body class="flex flex-col min-h-screen text-gray-800 font-sans">

    {{-- HEADER --}}
    <header class="header-gradient text-white sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-4 py-3 md:py-4 flex flex-col md:flex-row items-center gap-4">
            
            {{-- BAGIAN 1: LOGO & NAMA TOKO (SUDAH DIPERBAIKI) --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 md:mr-4 self-start md:self-center shrink-0 hover:opacity-90 transition group">
                
                {{-- A. LOGO / IKON --}}
                @if(isset($setting) && $setting->logo_path)
                    {{-- Jika ada logo upload --}}
                    <div class="bg-white p-1 rounded-md shadow-sm">
                        <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="Logo" class="h-10 w-auto object-contain">
                    </div>
                @else
                    {{-- Jika tidak ada logo, pakai Ikon Tas --}}
                    <i class="fa-solid fa-bag-shopping text-3xl"></i>
                @endif

                {{-- B. NAMA TOKO (Ditaruh diluar IF agar SELALU MUNCUL) --}}
                <div class="flex flex-col text-white">
                    <span class="text-xl md:text-2xl font-bold whitespace-nowrap leading-none tracking-tight">
                        {{ $setting->shop_name ?? 'Toko Saya' }}
                    </span>
                    {{-- Slogan Kecil di bawah nama toko --}}
                    @if(!empty($setting->slogan))
                        <span class="text-[10px] opacity-90 hidden md:block mt-0.5 font-light tracking-wide">{{ $setting->slogan }}</span>
                    @endif
                </div>
            </a>

            {{-- BAGIAN 2: SEARCH BAR --}}
            <form action="{{ route('home') }}" method="GET" class="w-full relative flex shadow-sm rounded-sm overflow-hidden bg-white">
                <input type="text" name="q" value="{{ request('q') }}"
                    class="w-full px-4 py-2 text-gray-700 focus:outline-none text-sm" 
                    placeholder="Cari produk di toko ini...">
                <button type="submit" class="bg-[#fb5533] px-5 text-white hover:brightness-95 transition flex items-center justify-center">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            {{-- BAGIAN 3: CART (KERANJANG) --}}
            <a href="{{ route('cart.index') }}" class="relative group hidden md:block px-2 shrink-0 ml-2">
                <i class="fa-solid fa-cart-shopping text-2xl opacity-90 hover:opacity-100 transition"></i>
                
                {{-- Hitung Item (Pakai Session agar aman tanpa database cart) --}}
                @php 
                    $cartCount = 0;
                    if(session('cart')) { foreach(session('cart') as $item) { $cartCount += $item['quantity']; } }
                @endphp

                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-2 bg-white text-primary text-xs font-bold rounded-full border border-primary px-1.5 py-0.5 shadow-sm">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            
            {{-- Cart Mobile --}}
            <a href="{{ route('cart.index') }}" class="absolute top-4 right-4 md:hidden text-white">
                <i class="fa-solid fa-cart-shopping text-xl"></i>
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center border border-white">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow py-8 container mx-auto px-4">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-gray-100 pt-10 pb-6 mt-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                
                {{-- 1. Identitas --}}
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        @if(isset($setting) && $setting->logo_path)
                            <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="Logo" class="h-8 w-auto">
                        @else
                            <i class="fa-solid fa-bag-shopping text-primary text-2xl"></i>
                        @endif
                        <span class="text-xl font-bold text-gray-800 tracking-tight">
                            {{ $setting->shop_name ?? 'TokoSaya' }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">
                        {{ $setting->slogan ?? 'Belanja puas, harga pas.' }}
                    </p>
                </div>

                {{-- 2. Alamat --}}
                <div class="col-span-1 md:col-span-1">
                    <h3 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Alamat Toko</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        {{ $setting->address ?? 'Alamat belum diatur.' }}
                    </p>
                </div>

                {{-- 3. Kontak --}}
                <div class="col-span-1 md:col-span-1">
                    <h3 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Layanan Pelanggan</h3>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li class="flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-green-500 text-lg"></i>
                            <span>+62 {{ $setting->shop_phone ?? '-' }}</span>
                        </li>
                    </ul>
                </div>

                {{-- 4. Sosmed --}}
                <div class="col-span-1 md:col-span-1">
                    <h3 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Ikuti Kami</h3>
                    <div class="flex flex-wrap gap-2">
                        @if(!empty($setting->facebook))
                            <a href="{{ $setting->facebook }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-blue-600 hover:text-white transition"><i class="fa-brands fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($setting->instagram))
                            <a href="{{ $setting->instagram }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-pink-500 hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
                        @endif
                        @if(!empty($setting->tiktok))
                            <a href="{{ $setting->tiktok }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-black hover:text-white transition"><i class="fa-brands fa-tiktok"></i></a>
                        @endif
                        @if(!empty($setting->youtube))
                            <a href="{{ $setting->youtube }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-red-600 hover:text-white transition"><i class="fa-brands fa-youtube"></i></a>
                        @endif
                        @if(!empty($setting->shopee))
                            <a href="{{ $setting->shopee }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-orange-500 hover:text-white transition"><i class="fa-solid fa-bag-shopping"></i></a>
                        @endif
                        @if(!empty($setting->tokopedia))
                            <a href="{{ $setting->tokopedia }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-green-600 hover:text-white transition"><i class="fa-solid fa-store"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 text-center">
                <p class="text-gray-400 text-xs">
                    &copy; {{ date('Y') }} {{ $setting->shop_name ?? 'Toko Online' }}. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    {{-- TOMBOL WA MELAYANG --}}
    @php
        $waLink = "https://wa.me/62" . ($setting->shop_phone ?? '') . "?text=Halo%20Admin,%20saya%20mau%20tanya%20produk...";
    @endphp
    <a href="{{ $waLink }}" target="_blank" class="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white p-3 md:p-4 rounded-full shadow-xl hover:scale-110 transition duration-300 flex items-center justify-center group">
        <i class="fa-brands fa-whatsapp text-3xl"></i>
    </a>

</body>
</html>