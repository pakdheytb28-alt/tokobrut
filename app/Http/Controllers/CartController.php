<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // 1. TAMPILKAN HALAMAN KERANJANG
    public function index()
    {
        // Sesuaikan dengan lokasi file Bapak ('cart.blade.php' ada di luar folder front)
        return view('cart');
    }

    // 2. TAMBAH KE KERANJANG (Tombol Keranjang)
    public function add(Request $request, $id)
    {
        $this->saveToCart($request, $id);
        return redirect()->back()->with('success', 'Berhasil masuk keranjang!');
    }

    // 3. BELI SEKARANG (Tombol Beli Langsung)
    public function buyNow(Request $request, $id)
    {
        // PENTING: Kita panggil fungsi simpan yang sama agar VARIAN ikut tersimpan
        $this->saveToCart($request, $id);
        
        // Bedanya: Langsung lempar ke halaman checkout
        return redirect()->route('checkout.index');
    }

    // --- LOGIKA PENYIMPANAN UTAMA (Private) ---
    private function saveToCart($request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // 1. Tangkap Input Varian & Jumlah
        $variant = $request->input('variant'); // Contoh: "MERAH"
        $quantity = (int) $request->input('quantity', 1);

        // 2. Buat ID Unik (Agar MERAH & HIJAU terpisah)
        $cartID = $id;
        if($variant) {
            // ID jadi misal: "32_merah"
            $cartID = $id . '_' . Str::slug($variant);
        }

        // 3. Cek Gambar
        $imagePath = null;
        if($product->images && $product->images->count() > 0) {
            $imagePath = $product->images->first()->image_path;
        } else {
            $imagePath = $product->image;
        }

        // 4. Masukkan/Update Session
        if(isset($cart[$cartID])) {
            $cart[$cartID]['quantity'] += $quantity;
        } else {
            $cart[$cartID] = [
                "product_id" => $product->id,
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $imagePath,
                "variant" => $variant // <--- KITA SIMPAN VARIAN DI SINI
            ];
        }

        session()->put('cart', $cart);
    }

    // 4. UPDATE JUMLAH (+ / -)
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $qty = (int) $request->quantity;
            if($qty < 1) $qty = 1;

            if(isset($cart[$request->id])) {
                $cart[$request->id]["quantity"] = $qty;
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Jumlah berhasil diupdate!');
        }
    }

    // 5. HAPUS BARANG
    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Barang berhasil dihapus.');
    }
}