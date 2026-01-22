@extends('layouts.frontend')

@section('content')

<div class="container mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="#" class="hover:text-blue-600">{{ $product->category->name ?? 'Kategori' }}</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-bold line-clamp-1">{{ $product->name }}</span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            
            {{-- BAGIAN KIRI: GALERI FOTO --}}
            <div class="space-y-4">
                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border relative group">
                    @if($product->images && $product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                             class="w-full h-full object-cover transition duration-500 group-hover:scale-105" id="mainImage">
                    @elseif($product->image)
                         <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover" id="mainImage">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i class="fa-regular fa-image text-5xl"></i>
                        </div>
                    @endif
                    
                    @if($product->is_featured)
                        <div class="absolute top-4 left-4 bg-yellow-400 text-white text-xs font-bold px-3 py-1 rounded shadow z-10">
                            <i class="fa-solid fa-star"></i> Unggulan
                        </div>
                    @endif
                </div>

                {{-- Thumbnail --}}
                @if($product->images && $product->images->count() > 1)
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach($product->images as $img)
                            <button onclick="changeImage('{{ asset('storage/' . $img->image_path) }}')" 
                                    class="w-16 h-16 border rounded hover:border-blue-600 focus:ring-2 ring-blue-200 overflow-hidden flex-shrink-0 transition">
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- BAGIAN KANAN: INFO & FORM BELI --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded uppercase tracking-wider">
                        {{ $product->category->name ?? 'Umum' }}
                    </span>
                    <span class="text-xs text-gray-500">
                        Stok Tersedia: <strong class="{{ $product->stock > 0 ? 'text-gray-800' : 'text-red-500' }}">{{ $product->stock }}</strong>
                    </span>
                </div>

                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 leading-tight">
                    {{ $product->name }}
                </h1>

                <div class="text-3xl font-bold text-[#EE4D2D] mb-6">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                <hr class="border-gray-100 mb-6">

                {{-- FORM START --}}
                {{-- Secara default form ini mengarah ke Keranjang (cart.add) --}}
                <form action="{{ route('cart.add', $product->id) }}" method="GET">
                    
                    {{-- === BAGIAN VARIAN === --}}
                    @if($product->variant_name && $product->variant_options)
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <h3 class="text-sm font-bold text-gray-800 uppercase mb-3">
                                Pilih {{ $product->variant_name }}:
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $product->variant_options) as $option)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="variant" value="{{ trim($option) }}" class="peer sr-only" required>
                                        <div class="px-4 py-2 bg-white border border-gray-200 rounded text-sm text-gray-600 peer-checked:bg-[#EE4D2D] peer-checked:text-white peer-checked:border-[#EE4D2D] hover:border-[#EE4D2D] transition select-none shadow-sm">
                                            {{ trim($option) }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- === END VARIAN === --}}

                    {{-- INPUT JUMLAH --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Jumlah Pembelian</label>
                        <div class="flex items-center w-32 border border-gray-300 rounded-lg overflow-hidden shadow-sm">
                            <button type="button" onclick="updateQty(-1)" class="w-10 h-10 bg-gray-50 hover:bg-gray-200 text-gray-600 transition">-</button>
                            <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="{{ $product->stock }}" 
                                   class="w-full h-10 text-center border-none focus:ring-0 text-gray-800 font-bold bg-white">
                            <button type="button" onclick="updateQty(1)" class="w-10 h-10 bg-gray-50 hover:bg-gray-200 text-gray-600 transition">+</button>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="flex flex-col md:flex-row gap-4 mt-8">
                        @if($product->stock > 0)
                            {{-- TOMBOL 1: MASUK KERANJANG --}}
                            <button type="submit" class="flex-1 bg-green-50 border border-green-500 text-green-600 hover:bg-green-100 font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition">
                                <i class="fa-solid fa-cart-plus"></i> Masukkan Keranjang
                            </button>

                            {{-- TOMBOL 2: BELI SEKARANG (SUDAH DIPERBARUI) --}}
                            {{-- Menggunakan 'formaction' untuk mengubah tujuan form ke 'cart.buyNow' --}}
                            <button type="submit" formaction="{{ route('cart.buyNow', $product->id) }}" class="flex-1 bg-[#EE4D2D] hover:bg-[#d73211] text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-bag-shopping"></i> Beli Sekarang
                            </button>
                        @else
                            <button type="button" disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3 rounded-lg cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </div>
                </form>
                {{-- FORM END --}}

                <div class="mt-8 pt-8 border-t border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg mb-3">Deskripsi Produk</h3>
                    <div class="text-gray-600 leading-relaxed text-sm whitespace-pre-line bg-gray-50 p-4 rounded-lg border border-gray-100">
                        {{ $product->description }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- PRODUK LAINNYA --}}
    <div class="mt-12 border-t pt-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-gray-800 text-xl border-l-4 border-blue-600 pl-3">Produk Lainnya</h3>
            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @forelse($relatedProducts as $related)
                @php $link = isset($related->slug) ? route('product.detail', $related->slug) : '#'; @endphp
                <a href="{{ $link }}" class="bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md hover:border-blue-500 transition duration-300 flex flex-col h-full overflow-hidden group">
                    <div class="relative w-full aspect-square bg-gray-100 overflow-hidden">
                        @if($related->images && $related->images->count() > 0)
                            <img src="{{ asset('storage/' . $related->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @elseif($related->image)
                             <img src="{{ asset('storage/' . $related->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-regular fa-image text-3xl"></i></div>
                        @endif
                    </div>
                    <div class="p-3 flex flex-col flex-grow">
                        <div class="text-sm text-gray-800 font-medium line-clamp-2 mb-2 h-10 leading-snug group-hover:text-blue-600 transition">{{ $related->name }}</div>
                        <div class="mt-auto">
                            <div class="text-[#EE4D2D] font-bold text-base">Rp {{ number_format($related->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-8 text-center text-gray-400 border-2 border-dashed rounded-lg">Tidak ada produk lain di kategori ini.</div>
            @endforelse
        </div>
    </div>

</div>

<script>
    function changeImage(src) { document.getElementById('mainImage').src = src; }
    function updateQty(amount) {
        let input = document.getElementById('qtyInput');
        let currentVal = parseInt(input.value);
        let maxVal = parseInt(input.getAttribute('max'));
        if(isNaN(maxVal)) maxVal = 999;
        let newVal = currentVal + amount;
        if(newVal >= 1 && newVal <= maxVal) input.value = newVal;
    }
</script>

@endsection