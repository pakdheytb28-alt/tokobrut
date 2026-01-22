<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Center - {{ config('app.name') }}</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- FontAwesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Google Fonts: Inter (Font standar dashboard modern) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-item.active { background-color: #EFF6FF; color: #2563EB; border-right: 3px solid #2563EB; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden">
        
        {{-- 1. SIDEBAR (MENU KIRI) --}}
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col justify-between">
            <div>
                {{-- Logo Area --}}
                <div class="h-16 flex items-center px-6 border-b border-gray-100">
                    <i class="fa-solid fa-store text-blue-600 text-xl mr-2"></i>
                    <span class="font-bold text-lg tracking-tight text-gray-800">Seller Center</span>
                </div>

                {{-- Menu Links --}}
                <nav class="mt-6 px-2 space-y-1">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line w-6"></i> Dashboard
                    </a>

                    <a href="{{ route('products.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-box w-6"></i> Produk Saya
                    </a>

                    <a href="{{ route('categories.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-tags w-6"></i> Kategori
                    </a>

                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Sistem</p>

                    <a href="{{ route('settings.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-gear w-6"></i> Pengaturan Toko
                    </a>
                    {{-- MENU PESANAN MASUK (BARU) --}}
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('orders.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i>
                    <span class="font-medium">Pesanan Masuk</span>
                    
                    {{-- Badge Notifikasi (Opsional: Hitung yang statusnya 'pending') --}}
                    @php
                        $pendingCount = \DB::table('orders')->where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                </nav>
            </div>

            {{-- Logout Button --}}
            <div class="p-4 border-t border-gray-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        <i class="fa-solid fa-right-from-bracket w-6"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- 2. MAIN CONTENT (KANAN) --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            
            {{-- Top Header --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
                {{-- Hamburger Mobile (Visible only on mobile) --}}
                <button class="md:hidden text-gray-600">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                {{-- Left: Page Title (Optional) --}}
                <div class="hidden md:block text-sm text-gray-500">
                    Halo, Selamat Datang Kembali! 👋
                </div>

                {{-- Right: Actions --}}
                <div class="flex items-center gap-4">
                    {{-- Tombol Lihat Toko --}}
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-medium px-3 py-1 bg-blue-50 rounded-full transition">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i> Lihat Toko
                    </a>
                    
                    {{-- Avatar Dummy --}}
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs border border-blue-200">
                        A
                    </div>
                </div>
            </header>

            {{-- Content Scrollable Area --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                {{-- Flash Message --}}
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                        <div class="flex items-center">
                            <i class="fa-solid fa-check-circle mr-2"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>