@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Produk Baru</h1>
        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    {{-- Tampilkan Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative">
            <strong class="font-bold">Ada yang salah!</strong>
            <ul class="mt-1 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- KOLOM KIRI --}}
                <div>
                    {{-- Nama Produk --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               placeholder="Contoh: Kemeja Flannel Kotak" required>
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Harga --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ old('price') }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   placeholder="150000" required>
                        </div>

                        {{-- Stok --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Stok Total</label>
                            <input type="number" name="stock" value="{{ old('stock') }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   placeholder="50" required>
                        </div>
                    </div>

                    {{-- FITUR BARU: VARIAN PRODUK (SUDAH DIPERBAIKI NAMANYA) --}}
                    <div class="mt-2 bg-blue-50 p-4 rounded border border-blue-100">
                        <h3 class="font-bold text-gray-800 text-sm mb-3 border-b border-blue-200 pb-2">
                            <i class="fa-solid fa-tags mr-1"></i> Varian Produk (Opsional)
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Nama Varian --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Varian</label>
                                {{-- PERHATIKAN: name="variant_name" (sesuai controller) --}}
                                <input type="text" name="variant_name" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500" placeholder="Contoh: Warna">
                            </div>

                            {{-- Pilihan Varian --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pilihan (Pisah dengan Koma)</label>
                                {{-- PERHATIKAN: name="variant_options" (sesuai controller) --}}
                                <input type="text" name="variant_options" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500" placeholder="Contoh: Merah, Biru, Hijau, Hitam">
                            </div>
                        </div>

                        <p class="text-[10px] text-gray-500 mt-2 italic">
                            *Tips: Masukkan pilihan dipisahkan dengan tanda koma (,).
                        </p>
                    </div>

                </div>

                {{-- KOLOM KANAN --}}
                <div>
                    {{-- Checkbox Produk Unggulan --}}
                    <div class="mb-4 bg-yellow-50 p-4 rounded-lg border border-yellow-200 hover:bg-yellow-100 transition cursor-pointer">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured" class="w-5 h-5 text-yellow-600 rounded focus:ring-yellow-500 border-gray-300">
                            <span class="ml-3 text-gray-800 font-bold">
                                <i class="fa-solid fa-star text-yellow-500 mr-1"></i> Jadikan Produk Unggulan
                            </span>
                        </label>
                    </div>

                    {{-- Upload Gambar (Multiple) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Foto Produk (Bisa Banyak)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition relative">
                            <input type="file" name="images[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="text-gray-500">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl mb-2"></i>
                                <p class="text-sm">Klik atau Tarik foto ke sini</p>
                                <p class="text-xs text-gray-400 mt-1">(Tahan CTRL untuk pilih banyak)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Lengkap</label>
                        <textarea name="description" rows="5" 
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                  placeholder="Jelaskan detail produk..." required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end border-t pt-4 mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded shadow-lg transition">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Produk
                </button>
            </div>

        </form>
    </div>
</div>

@endsection