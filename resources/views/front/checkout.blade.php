@extends('layouts.frontend')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-primary transition">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('cart.index') }}" class="hover:text-primary transition">Keranjang</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-bold">Checkout</span>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: FORMULIR ALAMAT --}}
            <div class="md:col-span-2">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-[#EE4D2D]"></i> Alamat Pengiriman
                    </h2>

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penerima</label>
                        <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition outline-none" placeholder="Nama Lengkap Anda" required>
                    </div>

                    {{-- WA --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp</label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-blue-100 focus-within:border-blue-500 transition">
                            <div class="bg-gray-100 px-3 py-3 text-gray-500 font-bold border-r border-gray-300">+62</div>
                            <input type="number" name="phone" class="w-full px-4 py-3 outline-none" placeholder="81234567890" required>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition outline-none" placeholder="Nama Jalan, No Rumah, RT/RW, Kecamatan, Kota..." required></textarea>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: RINGKASAN PESANAN (BAGIAN YANG DIPERBAIKI) --}}
            <div class="md:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-24">
                    <h3 class="font-bold text-gray-800 mb-4 text-lg">Ringkasan Pesanan</h3>
                    
                    {{-- LOOP BARANG --}}
                    <div class="space-y-3 mb-6 max-h-80 overflow-y-auto pr-2">
                        @php $total = 0; @endphp
                        @if(session('cart'))
                            @foreach(session('cart') as $item)
                                @php $total += $item['price'] * $item['quantity']; @endphp
                                
                                <div class="flex gap-3 text-sm border-b border-gray-50 pb-3 last:border-0">
                                    {{-- Foto Kecil --}}
                                    <div class="w-12 h-12 bg-gray-100 rounded border overflow-hidden shrink-0">
                                        @if(!empty($item['image']))
                                            <img src="{{ asset('storage/' . $item['image']) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-bag-shopping"></i></div>
                                        @endif
                                    </div>

                                    {{-- Detail Nama & Varian --}}
                                    <div class="flex-1">
                                        <div class="font-bold text-gray-800 line-clamp-1">{{ $item['name'] }}</div>
                                        
                                        {{-- !!! MENAMPILKAN VARIAN DI SINI !!! --}}
                                        @if(isset($item['variant']) && $item['variant'])
                                            <div class="text-[10px] text-blue-600 bg-blue-50 px-2 py-0.5 rounded inline-block mb-1 border border-blue-100 font-bold uppercase mt-1">
                                                {{ $item['variant'] }}
                                            </div>
                                        @endif

                                        <div class="text-gray-500 text-xs mt-0.5">
                                            {{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="border-t border-dashed pt-4 mb-4">
                        <div class="flex justify-between items-center text-sm mb-2 text-gray-600">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm mb-2 text-green-600">
                            <span>Ongkos Kirim</span>
                            <span class="text-xs bg-green-100 px-2 py-1 rounded font-bold">Cek via WA</span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold text-[#EE4D2D] mt-3">
                            <span>Total</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#EE4D2D] hover:bg-[#d73211] text-white font-bold py-3.5 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                        Buat Pesanan Sekarang
                    </button>
                    
                    <p class="text-[10px] text-gray-400 text-center mt-3 leading-tight">
                        Anda akan diarahkan ke WhatsApp untuk menyelesaikan pembayaran.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>

@endsection