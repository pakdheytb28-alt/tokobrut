@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kategori Produk</h1>
            <p class="text-gray-500 text-sm mt-1">Atur pengelompokan produk agar mudah ditemukan pembeli.</p>
        </div>
        
        <div class="flex gap-3">
            {{-- Tombol Hapus Massal --}}
            <button onclick="confirmBulkDelete()" id="btnBulkDelete" class="hidden bg-red-100 text-red-600 hover:bg-red-600 hover:text-white font-bold py-2.5 px-6 rounded-lg transition flex items-center gap-2">
                <i class="fa-solid fa-trash-can"></i> Hapus (<span id="countSelected">0</span>)
            </button>

            {{-- Tombol Tambah --}}
            <a href="{{ route('categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow hover:shadow-lg transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Kategori
            </a>
        </div>
    </div>

    {{-- FORM RAHASIA --}}
    <form id="formBulkDelete" action="{{ route('categories.bulkDelete') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="inputBulkIds">
    </form>

    {{-- TABEL KATEGORI --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" id="checkAll" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="p-4 font-semibold">Nama Kategori</th>
                        <th class="p-4 font-semibold">Slug (Link)</th>
                        <th class="p-4 font-semibold text-center">Jumlah Produk</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-blue-50/30 transition duration-150 group">
                            {{-- Checkbox --}}
                            <td class="p-4 text-center">
                                <input type="checkbox" value="{{ $category->id }}" class="checkItem w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                            </td>

                            {{-- Nama & Ikon --}}
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                                        <i class="{{ $category->icon ?? 'fa-solid fa-box' }}"></i>
                                    </div>
                                    <span class="font-bold text-gray-800">{{ $category->name }}</span>
                                </div>
                            </td>

                            {{-- Slug --}}
                            <td class="p-4 text-gray-500 text-sm font-mono">
                                /{{ $category->slug }}
                            </td>

                            {{-- Jumlah Produk --}}
                            <td class="p-4 text-center">
                                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $category->products->count() }} Item
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2 opacity-50 group-hover:opacity-100 transition">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="w-8 h-8 flex items-center justify-center rounded text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white transition">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </a>
                                    
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded text-red-600 bg-red-50 hover:bg-red-600 hover:text-white transition">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-10 text-center text-gray-400">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-end">
            {{ $categories->links() }}
        </div>
    </div>

    {{-- SCRIPT COPY-PASTE DARI PRODUK (LOGIKA SAMA) --}}
    <script>
        const checkAll = document.getElementById('checkAll');
        const checkItems = document.querySelectorAll('.checkItem');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const countSelected = document.getElementById('countSelected');
        const formBulkDelete = document.getElementById('formBulkDelete');
        const inputBulkIds = document.getElementById('inputBulkIds');

        checkAll.addEventListener('change', function() {
            checkItems.forEach(item => { item.checked = this.checked; });
            updateBulkButton();
        });

        checkItems.forEach(item => {
            item.addEventListener('change', function() {
                if(!this.checked) checkAll.checked = false;
                updateBulkButton();
            });
        });

        function updateBulkButton() {
            const checkedCount = document.querySelectorAll('.checkItem:checked').length;
            countSelected.innerText = checkedCount;
            if(checkedCount > 0) {
                btnBulkDelete.classList.remove('hidden');
                btnBulkDelete.classList.add('flex');
            } else {
                btnBulkDelete.classList.add('hidden');
                btnBulkDelete.classList.remove('flex');
            }
        }

        function confirmBulkDelete() {
            const checkedCount = document.querySelectorAll('.checkItem:checked').length;
            if(confirm('Yakin ingin menghapus ' + checkedCount + ' kategori terpilih?')) {
                let ids = [];
                document.querySelectorAll('.checkItem:checked').forEach(item => { ids.push(item.value); });
                inputBulkIds.value = ids.join(',');
                formBulkDelete.submit();
            }
        }
    </script>

@endsection