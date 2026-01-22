@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Tambah Kategori Pancing</h2>
        
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            
            {{-- Nama Kategori --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Kategori</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" placeholder="Contoh: Joran, Reel, atau Umpan" required>
            </div>

            {{-- Pilihan Icon (Tema Pancing) --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Pilih Ikon</label>
                
                <div class="flex gap-2 mb-3">
                    <div class="w-10 h-10 bg-gray-100 border rounded flex items-center justify-center text-xl text-blue-600">
                        <i id="iconPreview" class="fa-solid fa-fish"></i>
                    </div>
                    <input type="text" name="icon" id="iconInput" class="flex-1 border rounded px-3 py-2 bg-gray-50" placeholder="Pilih ikon di bawah..." readonly>
                </div>

                {{-- GRID ICON KHUSUS PANCING --}}
                <div class="grid grid-cols-4 gap-2">
                    {{-- 1. Ikan (Untuk kategori Ikan/Bibit) --}}
                    <button type="button" onclick="selectIcon('fa-solid fa-fish')" class="border p-2 rounded hover:bg-blue-50 hover:border-blue-500 flex flex-col items-center gap-1 transition">
                        <i class="fa-solid fa-fish text-lg text-gray-600"></i>
                        <span class="text-[10px]">Ikan</span>
                    </button>
                    
                    {{-- 2. Umpan (Cacing) --}}
                    <button type="button" onclick="selectIcon('fa-solid fa-worm')" class="border p-2 rounded hover:bg-blue-50 hover:border-blue-500 flex flex-col items-center gap-1 transition">
                        <i class="fa-solid fa-worm text-lg text-gray-600"></i>
                        <span class="text-[10px]">Umpan</span>
                    </button>

                    {{-- 3. Jangkar (Untuk Pemberat/Aksesoris Berat) --}}
                    <button type="button" onclick="selectIcon('fa-solid fa-anchor')" class="border p-2 rounded hover:bg-blue-50 hover:border-blue-500 flex flex-col items-center gap-1 transition">
                        <i class="fa-solid fa-anchor text-lg text-gray-600"></i>
                        <span class="text-[10px]">Jangkar</span>
                    </button>

                    {{-- 4. Air (Untuk Spot/Perlengkapan Air) --}}
                    <button type="button" onclick="selectIcon('fa-solid fa-water')" class="border p-2 rounded hover:bg-blue-50 hover:border-blue-500 flex flex-col items-center gap-1 transition">
                        <i class="fa-solid fa-water text-lg text-gray-600"></i>
                        <span class="text-[10px]">Air/Laut</span>
                    </button>

                    {{-- 5. Perahu (Untuk Kapal/Mesin Tempel) --}}
                    <button type="button" onclick="selectIcon('fa-solid fa-ship')" class="border p-2 rounded hover:bg-blue-50 hover:border-blue-500 flex flex-col items-center gap-1 transition">
                        <i class="fa-solid fa-ship text-lg text-gray-600"></i>
                        <span class="text-[10px]">Perahu</span>
                    </button>

                    {{-- 6. Ember (Untuk Wadah/Box) --}}
                    <button type="button" onclick="selectIcon('fa-solid fa-bucket')" class="border p-2 rounded hover:bg-blue-50 hover:border-blue-500 flex flex-col items-center gap-1 transition">
                        <i class="fa-solid fa-bucket text-lg text-gray-600"></i>
                        <span class="text-[10px]">Wadah</span>
                    </button>
                    
                    {{-- 7. Umum (Box) --}}
                    <button type="button" onclick="selectIcon('fa-solid fa-box-open')" class="border p-2 rounded hover:bg-blue-50 hover:border-blue-500 flex flex-col items-center gap-1 transition">
                        <i class="fa-solid fa-box-open text-lg text-gray-600"></i>
                        <span class="text-[10px]">Box/Set</span>
                    </button>

                    {{-- 8. Timbangan (Alat Ukur) --}}
                    <button type="button" onclick="selectIcon('fa-solid fa-scale-unbalanced')" class="border p-2 rounded hover:bg-blue-50 hover:border-blue-500 flex flex-col items-center gap-1 transition">
                        <i class="fa-solid fa-scale-unbalanced text-lg text-gray-600"></i>
                        <span class="text-[10px]">Alat Ukur</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700">Simpan Kategori</button>
        </form>
    </div>
</div>

<script>
    function selectIcon(iconClass) {
        document.getElementById('iconInput').value = iconClass;
        document.getElementById('iconPreview').className = iconClass;
    }
</script>
@endsection