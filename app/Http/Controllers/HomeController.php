<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Pengaturan Toko
        $setting = Setting::first();

        // 2. Query Dasar Produk
        $query = Product::query();

        // 3. Filter Pencarian (Jika user mengetik di search bar)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 4. Filter Kategori (Jika user klik ikon kategori)
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // 5. AMBIL DATA REKOMENDASI (Semua Produk Terbaru)
        // Batasi hanya 12 produk per halaman
        $products = $query->latest()->paginate(12)->withQueryString();
        
        // 6. AMBIL KATEGORI (Untuk Menu Bulat)
        $categories = Category::all();

        // 7. AMBIL PRODUK UNGGULAN (KHUSUS SLIDER)
        // Logikanya: Cari produk yang 'is_featured' = 1 (true)
        $featuredProducts = Product::where('is_featured', true)->latest()->take(10)->get();

        // 8. Kirim semua data ke tampilan 'welcome'
        return view('welcome', compact('products', 'categories', 'featuredProducts', 'setting'));
    }
}