@extends('layouts.frontend')

@section('content')

<div class="container mx-auto px-4 py-8">
    
    {{-- Breadcrumb --}}
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-primary transition">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-bold">Keranjang Belanja</span>
    </div>

    @if(session('cart') && count(session('cart')) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- BAGIAN KIRI: DAFTAR PRODUK --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                
                {{-- Header Tabel (Desktop) --}}
                <div class="hidden md:grid grid-cols-12 gap-4 p-4 bg-gray-50 border-b text-sm font-bold text-gray-600">
                    <div class="col-span-6">Produk</div>
                    <div class="col-span-2 text-center">Harga</div>
                    <div class="col-span-2 text-center">Jumlah</div>
                    <div class="col-span-2 text-center">Total</div>
                </div>

                {{-- Loop Item Keranjang --}}
                @php $totalBelanja = 0; @endphp
                @foreach(session('cart') as $id => $details)
                    @php 
                        $subtotal = $details['price'] * $details['quantity']; 
                        $totalBelanja += $subtotal;
                    @endphp
                    
                    <div class="p-4 border-b last:border-0 hover:bg-gray-50 transition relative group">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            
                            {{-- 1. Info Produk --}}
                            <div class="col-span-6 flex items-center gap-4">
                                {{-- Tombol Hapus --}}
                                <a href="{{ route('cart.remove', $id) }}" class="text-gray-300 hover:text-red-500 transition" title="Hapus Barang" onclick="return confirm('Hapus barang ini?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                                
                                {{-- Gambar --}}
                                <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded border border-gray-200 overflow-hidden">
                                    @if(isset($details['image']))
                                        <img src="{{ asset('storage/' . $details['image']) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-bag-shopping"></i></div>
                                    @endif
                                </div>
                                
                                {{-- Nama & Varian --}}
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm line-clamp-2">{{ $details['name'] }}</h3>
                                    
                                    {{-- INI BAGIAN PENTINGNYA: Tampilkan Varian Jika Ada --}}
                                    @if(isset($details['variant']) && $details['variant'])
                                        <div class="mt-1 inline-block bg-gray-100 border border-gray-200 text-gray-600 text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wide">
                                            {{ $details['variant'] }}
                                        </div>
                                    @endif

                                    <div class="text-xs text-gray-500 md:hidden mt-1">
                                        Rp {{ number_format($details['price'], 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Harga Satuan (Desktop) --}}
                            <div class="hidden md:block col-span-2 text-center text-sm text-gray-600">
                                Rp {{ number_format($details['price'], 0, ',', '.') }}
                            </div>

                            {{-- 3. Update Jumlah --}}
                            <div class="col-span-6 md:col-span-2 flex justify-center">
                                <div class="flex items-center border border-gray-300 rounded overflow-hidden h-8">
                                    {{-- Kurang --}}
                                    <form action="{{ route('cart.update') }}" method="POST" class="h-full">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="hidden" name="quantity" value="{{ $details['quantity'] - 1 }}">
                                        <button type="submit" class="px-2 h-full bg-gray-50 hover:bg-gray-200 text-gray-600 transition" 
                                            {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                    </form>
                                    
                                    {{-- Angka --}}
                                    <input type="text" readonly value="{{ $details['quantity'] }}" 
                                           class="w-10 text-center text-xs font-bold text-gray-700 h-full border-x border-gray-300 focus:outline-none">
                                    
                                    {{-- Tambah --}}
                                    <form action="{{ route('cart.update') }}" method="POST" class="h-full">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="hidden" name="quantity" value="{{ $details['quantity'] + 1 }}">
                                        <button type="submit" class="px-2 h-full bg-gray-50 hover:bg-gray-200 text-green-600 transition">+</button>
                                    </form>
                                </div>
                            </div>

                            {{-- 4. Subtotal --}}
                            <div class="col-span-6 md:col-span-2 text-right md:text-center font-bold text-[#EE4D2D] text-sm">
                                <span class="md:hidden text-gray-400 font-normal text-xs mr-1">Subtotal:</span>
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Kembali --}}
            <div class="mt-4">
                <a href="{{ route('home') }}" class="text-gray-500 text-sm font-medium hover:text-[#EE4D2D] transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
                </a>
            </div>
        </div>

        {{-- BAGIAN KANAN: RINGKASAN & CHECKOUT --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 sticky top-24">
                <h3 class="font-bold text-gray-800 text-lg mb-4 border-b pb-2">Ringkasan Belanja</h3>
                
                <div class="flex justify-between items-center mb-2 text-sm text-gray-600">
                    <span>Total Item</span>
                    <span>{{ count(session('cart')) }} Barang</span>
                </div>
                
                <div class="flex justify-between items-center mb-6 pt-4 border-t border-dashed">
                    <span class="text-base font-bold text-gray-800">Total Tagihan</span>
                    <span class="text-xl font-bold text-[#EE4D2D]">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</span>
                </div>

                {{-- TOMBOL CHECKOUT --}}
                <a href="{{ route('checkout.index') }}" class="block w-full bg-[#EE4D2D] hover:bg-[#d73211] text-white text-center font-bold py-3.5 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 mb-3 flex items-center justify-center gap-2">
                    Checkout Sekarang <i class="fa-solid fa-arrow-right"></i>
                </a>
                
                <div class="text-[10px] text-gray-400 text-center leading-tight">
                    <i class="fa-solid fa-truck-fast mr-1"></i> Isi alamat pengiriman di halaman selanjutnya.
                </div>
            </div>
        </div>

    </div>
    @else
        {{-- TAMPILAN KERANJANG KOSONG --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-16 text-center max-w-2xl mx-auto mt-10">
            <div class="mb-6 inline-block p-6 rounded-full bg-gray-50 text-gray-300">
                <i class="fa-solid fa-cart-shopping text-6xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Keranjangmu Kosong</h2>
            <p class="text-gray-500 mb-8">Sepertinya kamu belum menambahkan barang apapun. Yuk cari barang impianmu sekarang!</p>
            <a href="{{ route('home') }}" class="inline-block bg-[#EE4D2D] text-white px-10 py-3 rounded-lg font-bold hover:brightness-95 transition shadow-md">
                Mulai Belanja
            </a>
        </div>
    @endif

</div>

@endsection