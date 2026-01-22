@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Produk: {{ $product->name }}</h1>
        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative">
            <strong class="font-bold">Periksa inputan Anda!</strong>
            <ul class="mt-1 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- KOLOM KIRI --}}
                <div>
                    {{-- Nama --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                        <select name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Harga & Stok --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Stok</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                    </div>

                    {{-- VARIAN PRODUK (SUDAH DIPERBAIKI SESUAI CONTROLLER) --}}
                    <div class="mt-2 bg-blue-50 p-4 rounded border border-blue-100">
                        <h3 class="font-bold text-gray-800 text-sm mb-3 border-b border-blue-200 pb-2">
                            <i class="fa-solid fa-tags mr-1"></i> Edit Varian
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Nama Varian --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Varian</label>
                                {{-- PERHATIKAN: name="variant_name" --}}
                                <input type="text" name="variant_name" value="{{ old('variant_name', $product->variant_name) }}" 
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500" placeholder="Contoh: Warna">
                            </div>

                            {{-- Pilihan Varian --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pilihan (Pisah Koma)</label>
                                {{-- PERHATIKAN: name="variant_options" --}}
                                <input type="text" name="variant_options" value="{{ old('variant_options', $product->variant_options) }}" 
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500" placeholder="Contoh: Merah, Biru, Hijau">
                            </div>
                        </div>

                        <p class="text-[10px] text-gray-500 mt-2 italic">
                            *Tips: Pisahkan pilihan dengan koma (Misal: S, M, L, XL).
                        </p>
                    </div>

                </div>

                {{-- KOLOM KANAN --}}
                <div>
                    {{-- Checkbox Unggulan --}}
                    <div class="mb-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" class="w-5 h-5 text-yellow-600 rounded focus:ring-yellow-500 border-gray-300"
                                   {{ $product->is_featured ? 'checked' : '' }}>
                            <span class="ml-3 text-gray-800 font-bold">
                                <i class="fa-solid fa-star text-yellow-500 mr-1"></i> Jadikan Produk Unggulan
                            </span>
                        </label>
                    </div>

                    {{-- Galeri Foto Saat Ini --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Foto Saat Ini</label>
                        @if($product->images->count() > 0)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($product->images as $image)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-20 object-cover rounded border">
                                        {{-- Anda bisa menambah tombol hapus per gambar di sini nanti jika mau --}}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-400 italic text-sm border-2 border-dashed p-4 rounded text-center">Belum ada foto.</p>
                        @endif
                    </div>

                    {{-- Tambah Foto Baru --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tambah Foto Baru</label>
                        <div class="border-2 border-dashed border-blue-300 rounded-lg p-6 text-center hover:bg-blue-50 transition relative">
                            <input type="file" name="images[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="text-blue-500">
                                <i class="fa-solid fa-images text-3xl mb-2"></i>
                                <p class="text-sm">Klik tambah foto</p>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Lengkap</label>
                        <textarea name="description" rows="6" 
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                  required>{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end border-t pt-6 mt-4 gap-4">
                <a href="{{ route('products.index') }}" class="text-gray-500 font-medium px-4">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded shadow-lg transition">
                    <i class="fa-solid fa-save mr-2"></i> Update Produk
                </button>
            </div>
        </form>
    </div>
</div>

@endsection