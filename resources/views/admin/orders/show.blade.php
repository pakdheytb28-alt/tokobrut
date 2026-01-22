@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4 py-6">
    
    {{-- Header & Tombol Kembali --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Detail Pesanan: <span class="text-blue-600">#{{ $order->invoice }}</span>
        </h1>
        <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-800 bg-white border px-4 py-2 rounded shadow-sm transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KOLOM KIRI: INFO UTAMA --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- 1. DAFTAR BARANG (YANG ADA VARIANNYA) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 text-lg mb-4 border-b pb-2">
                    <i class="fa-solid fa-box-open text-[#EE4D2D] mr-2"></i> Item Pesanan
                </h3>
                
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <ul class="space-y-3">
                        {{-- Kita pecah string "Baju (Merah) (1x), Celana (2x)" menjadi list ke bawah --}}
                        @foreach(explode(',', $order->product_summary) as $item)
                            <li class="flex items-start gap-3 text-gray-700 font-medium border-b border-gray-200 last:border-0 pb-2 last:pb-0">
                                <i class="fa-solid fa-check-circle text-green-500 mt-1"></i>
                                <span>{{ trim($item) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="flex justify-between items-center mt-4 pt-4 border-t">
                    <span class="text-gray-600">Total Tagihan</span>
                    <span class="text-2xl font-bold text-[#EE4D2D]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- 2. STATUS PESANAN --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 text-lg mb-4">Ubah Status</h3>
                <form action="{{ route('orders.update', $order->id) }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    @method('PUT')
                    
                    <div class="w-full">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status Saat Ini</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-100 outline-none">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai / Dikirim</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                        Update
                    </button>
                </form>
            </div>
        </div>

        {{-- KOLOM KANAN: INFO PELANGGAN --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full">
                <h3 class="font-bold text-gray-800 text-lg mb-6 border-b pb-2">
                    <i class="fa-solid fa-user text-blue-500 mr-2"></i> Info Pelanggan
                </h3>

                {{-- Nama --}}
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase">Nama Penerima</label>
                    <div class="text-gray-800 font-bold text-lg">{{ $order->customer_name }}</div>
                </div>

                {{-- No WA + Tombol Chat --}}
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase">WhatsApp</label>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-gray-800 font-medium">{{ $order->customer_phone }}</span>
                        <a href="https://wa.me/{{ $order->customer_phone }}" target="_blank" class="bg-green-100 text-green-600 px-3 py-1 rounded text-xs font-bold hover:bg-green-200 transition">
                            <i class="fa-brands fa-whatsapp"></i> Chat
                        </a>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 uppercase">Alamat Pengiriman</label>
                    <div class="text-gray-600 mt-1 leading-relaxed bg-gray-50 p-3 rounded border border-gray-100 text-sm">
                        {{ $order->address }}
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="text-xs text-gray-400 border-t pt-4">
                    Dipesan pada: {{ $order->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

    </div>
</div>

@endsection