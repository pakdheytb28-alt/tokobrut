<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string'
        ]);

        // SOLUSI ANTI ERROR: Tambahkan kode acak 5 digit di belakang slug
        // Jadi kalau buat "Ikan" lagi, linknya jadi "ikan-x7z9q", tidak bentrok.
        $slug = Str::slug($request->name) . '-' . Str::random(5);

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'icon' => $request->icon ?? 'fa-solid fa-box'
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dibuat!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string'
        ]);

        // Update Slug juga dengan kode acak agar aman
        $slug = Str::slug($request->name) . '-' . Str::random(5);

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'icon' => $request->icon
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori dihapus.');
    }
    /**
     * Hapus Massal Kategori
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'Pilih minimal satu kategori.');
        }

        $idsArray = explode(',', $ids);

        // Hapus kategori yang dipilih
        // Catatan: Produk di dalamnya tidak terhapus, hanya kategori_id nya jadi null atau tetap (tergantung setting database)
        \App\Models\Category::whereIn('id', $idsArray)->delete();

        return redirect()->back()->with('success', count($idsArray) . ' Kategori berhasil dihapus!');
    }
}