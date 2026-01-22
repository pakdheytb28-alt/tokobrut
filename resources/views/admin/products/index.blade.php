@extends('layouts.admin')

@section('content')

    {{-- HEADER: JUDUL, TOMBOL TAMBAH, & TOMBOL HAPUS MASSAL --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola katalog produk, harga, dan stok di sini.</p>
        </div>
        
        <div class="flex gap-3">
            {{-- Tombol Hapus Massal (Awalnya Sembunyi / Disabled) --}}
            <button onclick="confirmBulkDelete()" id="btnBulkDelete" class="hidden bg-red-100 text-red-600 hover:bg-red-600 hover:text-white font-bold py-2.5 px-6 rounded-lg transition flex items-center gap-2">
                <i class="fa-solid fa-trash-can"></i> Hapus (<span id="countSelected">0</span>)
            </button>

            {{-- Tombol Tambah --}}
            <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow hover:shadow-lg transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Baru
            </a>
        </div>
    </div>

    {{-- FORM RAHASIA UNTUK KIRIM DATA HAPUS MASSAL --}}
    <form id="formBulkDelete" action="{{ route('products.bulkDelete') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="inputBulkIds">
    </form>

    {{-- KONTEN UTAMA: TABEL --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        
        {{-- Filter Bar --}}
        <div class="p-4 border-b border-gray-100 flex gap-3 bg-gray-50/50">
            <div class="relative flex-1 max-w-sm">
                <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                <input type="text" placeholder="Cari nama produk..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                        {{-- CHECKBOX MASTER --}}
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" id="checkAll" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="p-4 font-semibold">Info Produk</th>
                        <th class="p-4 font-semibold">Kategori</th>
                        <th class="p-4 font-semibold">Harga</th>
                        <th class="p-4 font-semibold text-center">Stok</th>
                        <th class="p-4 font-semibold text-center">Status</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-blue-50/30 transition duration-150 group">
                            
                            {{-- CHECKBOX ITEM --}}
                            <td class="p-4 text-center">
                                <input type="checkbox" value="{{ $product->id }}" class="checkItem w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                            </td>

                            {{-- 1. Info Produk --}}
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0 relative">
                                        @if($product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-regular fa-image"></i></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800 text-sm line-clamp-1 max-w-[200px]" title="{{ $product->name }}">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">ID: #{{ $product->id }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Kategori --}}
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    <i class="{{ $product->category->icon ?? 'fa-solid fa-box' }} text-[10px]"></i>
                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>

                            {{-- 3. Harga --}}
                            <td class="p-4 text-sm font-semibold text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</td>

                            {{-- 4. Stok --}}
                            <td class="p-4 text-center">
                                @if($product->stock <= 5)
                                    <span class="text-red-600 font-bold text-sm flex flex-col items-center">{{ $product->stock }} <span class="text-[10px] font-normal text-red-400">Menipis!</span></span>
                                @else
                                    <span class="text-green-600 font-bold text-sm">{{ $product->stock }}</span>
                                @endif
                            </td>

                            {{-- 5. Status --}}
                            <td class="p-4 text-center">
                                @if($product->is_featured)
                                    <div class="inline-flex flex-col items-center text-yellow-500"><i class="fa-solid fa-star text-lg drop-shadow-sm"></i><span class="text-[9px] font-bold mt-1 uppercase">Unggulan</span></div>
                                @else
                                    <span class="text-gray-300 text-xs"><i class="fa-regular fa-star text-lg"></i></span>
                                @endif
                            </td>

                            {{-- 6. Aksi (Manual) --}}
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2 opacity-50 group-hover:opacity-100 transition">
                                    <a href="{{ route('products.edit', $product->id) }}" class="w-8 h-8 flex items-center justify-center rounded text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white transition"><i class="fa-solid fa-pen-to-square text-sm"></i></a>
                                    
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded text-red-600 bg-red-50 hover:bg-red-600 hover:text-white transition"><i class="fa-solid fa-trash text-sm"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-10 text-center text-gray-400">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-end">
            {{ $products->links() }}
        </div>
    </div>

    {{-- SCRIPT: LOGIKA CHECKBOX --}}
    <script>
        const checkAll = document.getElementById('checkAll');
        const checkItems = document.querySelectorAll('.checkItem');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const countSelected = document.getElementById('countSelected');
        const formBulkDelete = document.getElementById('formBulkDelete');
        const inputBulkIds = document.getElementById('inputBulkIds');

        // 1. Fungsi Toggle Check All
        checkAll.addEventListener('change', function() {
            checkItems.forEach(item => {
                item.checked = this.checked;
            });
            updateBulkButton();
        });

        // 2. Fungsi Cek Item Satu per Satu
        checkItems.forEach(item => {
            item.addEventListener('change', function() {
                // Jika ada satu yang tidak dicentang, matikan centang master
                if(!this.checked) {
                    checkAll.checked = false;
                }
                updateBulkButton();
            });
        });

        // 3. Update Tampilan Tombol Hapus
        function updateBulkButton() {
            // Hitung berapa yang dicentang
            const checkedCount = document.querySelectorAll('.checkItem:checked').length;
            
            countSelected.innerText = checkedCount;

            if(checkedCount > 0) {
                // Munculkan tombol merah
                btnBulkDelete.classList.remove('hidden');
                btnBulkDelete.classList.add('flex');
            } else {
                // Sembunyikan tombol
                btnBulkDelete.classList.add('hidden');
                btnBulkDelete.classList.remove('flex');
            }
        }

        // 4. Konfirmasi & Kirim Data Hapus
        function confirmBulkDelete() {
            const checkedCount = document.querySelectorAll('.checkItem:checked').length;
            if(confirm('Yakin ingin menghapus ' + checkedCount + ' produk terpilih? Tindakan ini tidak bisa dibatalkan!')) {
                
                // Kumpulkan ID menjadi Array [1, 5, 8]
                let ids = [];
                document.querySelectorAll('.checkItem:checked').forEach(item => {
                    ids.push(item.value);
                });

                // Masukkan ke Input Hidden & Submit Form
                inputBulkIds.value = ids.join(','); // Jadinya string "1,5,8"
                formBulkDelete.submit();
            }
        }
    </script>

@endsection