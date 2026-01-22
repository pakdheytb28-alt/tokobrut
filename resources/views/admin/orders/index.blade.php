@extends('layouts.admin')

@section('content')
    
    {{-- HEADER HALAMAN --}}
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pesanan Masuk</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola dan pantau status pesanan dari pelanggan.</p>
        </div>
    </div>

    {{-- ALERT SUKSES (Muncul jika berhasil hapus atau update) --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center gap-3">
            <i class="fa-solid fa-check-circle text-lg"></i>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- TABEL PESANAN --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Jika tidak ada data --}}
        @if($orders->count() <= 0)
            <div class="p-10 text-center text-gray-400">
                <i class="fa-solid fa-folder-open text-4xl mb-3"></i>
                <p>Belum ada pesanan masuk.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase border-b border-gray-100 tracking-wider">
                            <th class="p-4 font-bold">Invoice</th>
                            <th class="p-4 font-bold">Pelanggan</th>
                            <th class="p-4 font-bold">Total</th>
                            <th class="p-4 font-bold text-center">Status</th>
                            <th class="p-4 font-bold text-center">Tanggal</th>
                            <th class="p-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                
                                {{-- 1. Invoice --}}
                                <td class="p-4">
                                    <span class="font-mono text-sm text-blue-600 font-bold bg-blue-50 px-2 py-1 rounded">
                                        #{{ $order->invoice }}
                                    </span>
                                </td>

                                {{-- 2. Info Pelanggan --}}
                                <td class="p-4">
                                    <div class="font-bold text-gray-800 text-sm">{{ $order->customer_name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        <i class="fa-brands fa-whatsapp text-green-500 mr-1"></i> {{ $order->customer_phone }}
                                    </div>
                                </td>

                                {{-- 3. Total Harga --}}
                                <td class="p-4 font-bold text-gray-700 text-sm">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>

                                {{-- 4. Status Badge --}}
                                <td class="p-4 text-center">
                                    @if($order->status == 'pending')
                                        <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-[10px] uppercase font-bold px-2 py-1 rounded-full">
                                            <i class="fa-solid fa-clock"></i> Perlu Dikirim
                                        </span>
                                    @elseif($order->status == 'shipped')
                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-[10px] uppercase font-bold px-2 py-1 rounded-full">
                                            <i class="fa-solid fa-truck"></i> Sedang Dikirim
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-[10px] uppercase font-bold px-2 py-1 rounded-full">
                                            <i class="fa-solid fa-check"></i> Selesai
                                        </span>
                                    @endif
                                </td>

                                {{-- 5. Tanggal --}}
                                <td class="p-4 text-center text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                                </td>

                                {{-- 6. Tombol Aksi (Detail & Hapus) --}}
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        {{-- Tombol Detail --}}
                                        <a href="{{ route('orders.show', $order->id) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm border border-blue-100 hover:border-blue-600">
                                            Detail
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pesanan ini? Data yang dihapus tidak bisa dikembalikan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-white text-red-500 hover:bg-red-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm border border-red-100 hover:border-red-500" title="Hapus Permanen">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $orders->links() }}
            </div>
        @endif

    </div>

@endsection