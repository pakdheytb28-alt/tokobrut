<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function show($slug)
    {
        // 1. Ambil Produk Detail
        $product = Product::with(['category', 'images'])->where('slug', $slug)->firstOrFail();

        // 2. Ambil "Produk Lainnya"
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($relatedProducts->count() < 1) {
            $relatedProducts = Product::where('id', '!=', $product->id)
                ->inRandomOrder()
                ->take(4)
                ->get();
        }

        // PERUBAHAN ADA DI SINI:
        // Sesuaikan dengan nama file Anda: 'details.blade.php' -> panggil 'details'
        return view('details', compact('product', 'relatedProducts'));
    }
}