<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk (Admin)
     */
    public function index()
    {
        $products = Product::with(['category', 'images'])->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form tambah produk
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // 2. Siapkan Data
        $data = $request->all();
        
        // Buat Slug (Hanya saat bikin baru)
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);
        
        // Checkbox Unggulan
        $data['is_featured'] = $request->has('is_featured');

        // --- PERBAIKAN VARIAN (SIMPAN SIMPLE TEXT) ---
        // Pastikan di form admin name inputnya adalah 'variant_name' dan 'variant_options'
        $data['variant_name'] = $request->variant_name;       // Contoh: "Warna"
        $data['variant_options'] = $request->variant_options; // Contoh: "Merah, Biru, Hijau"
        // ---------------------------------------------

        // 3. Simpan Produk
        $product = Product::create($data);

        // 4. Upload Gambar (Multiple)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit produk
     */
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Mengupdate data produk
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 1. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // 2. Siapkan Data Update
        $data = $request->all();

        // --- PERBAIKAN FATAL: JANGAN UPDATE SLUG ---
        // $data['slug'] = Str::slug($request->name) . '-' . Str::random(5); // <--- KODE INI SAYA MATIKAN AGAR TIDAK 404
        
        $data['is_featured'] = $request->has('is_featured');

        // --- PERBAIKAN VARIAN (UPDATE SIMPLE TEXT) ---
        $data['variant_name'] = $request->variant_name;
        $data['variant_options'] = $request->variant_options;
        // ---------------------------------------------

        // Update Tabel Produk
        $product->update($data);

        // 3. Tambah Gambar Baru (Tanpa hapus yang lama)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus File Fisik
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        // Hapus Record di Database
        $product->images()->delete();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    // Hapus Massal (Checkbox)
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) return redirect()->back()->with('error', 'Pilih produk dulu.');

        $idsArray = explode(',', $ids);
        $products = Product::whereIn('id', $idsArray)->get();

        foreach ($products as $product) {
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            $product->images()->delete();
            $product->delete();
        }

        return redirect()->back()->with('success', count($idsArray) . ' Produk dihapus!');
    }
}