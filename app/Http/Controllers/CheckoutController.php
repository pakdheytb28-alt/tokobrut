<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Setting; // <--- PENTING: Panggil Model Setting

class CheckoutController extends Controller
{
    public function index()
    {
        if(!session('cart') || count(session('cart')) < 1) {
            return redirect()->route('home')->with('error', 'Keranjang Anda kosong!');
        }
        return view('front.checkout');
    }

    public function process(Request $request)
    {
        // === A. ANTI SPAM ===
        if (session()->has('last_order_time')) {
            $lastOrder = session('last_order_time');
            if (Carbon::now()->diffInMinutes($lastOrder) < 2) { 
                return redirect()->back()->with('error', 'Mohon tunggu 2 menit sebelum order lagi.');
            }
        }

        // === B. VALIDASI ===
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|numeric|digits_between:10,15', 
            'address' => 'required|string|min:10', 
        ]);

        // === C. PERSIAPAN DATA ===
        $cart = session('cart');
        $totalPrice = 0;
        $productSummary = [];
        $waList = [];

        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
            
            $variantText = '';
            if(isset($item['variant']) && $item['variant']) {
                $variantText = " [" . $item['variant'] . "]";
            }

            $itemString = "▪ " . $item['name'] . $variantText . " (" . $item['quantity'] . "x)";
            $productSummary[] = $itemString;
            $waList[] = $itemString;
        }
        
        $summaryString = implode(', ', $productSummary);
        $waMessageList = implode("\n", $waList);
        $invoice = 'INV-' . date('ymd') . rand(100,999);

        // === D. SIMPAN DATABASE ===
        try {
            DB::table('orders')->insert([
                'invoice' => $invoice,
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'address' => $request->address,
                'product_summary' => $summaryString,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan pesanan.');
        }

        // === E. KIRIM WHATSAPP (OTOMATIS DARI ADMIN) ===
        
        // 1. Ambil data dari tabel settings (Baris Pertama)
        $setting = Setting::first(); 

        // 2. Cek kolom 'phone' atau 'whatsapp' di database Bapak
        // Ganti 'phone' dengan nama kolom yang ada di tabel settings Bapak
        $adminPhone = $setting->phone ?? '6280000000000'; 

        // 3. Pastikan format nomornya 62 (bukan 08)
        // Jika admin input 08, kita ubah jadi 62 otomatis
        if(substr($adminPhone, 0, 1) == '0') {
            $adminPhone = '62' . substr($adminPhone, 1);
        }

        $message = "Halo Admin, order baru via Web:\n\n";
        $message .= "*No. Invoice:* $invoice\n";
        $message .= "*Nama:* $request->name\n";
        $message .= "*No HP:* $request->phone\n";
        $message .= "*Alamat:* $request->address\n\n";
        $message .= "*Detail Pesanan:*\n";
        $message .= $waMessageList . "\n\n";
        $message .= "*Total Tagihan: Rp " . number_format($totalPrice, 0, ',', '.') . "*\n";
        $message .= "Mohon info pembayaran. Terima kasih!";

        // === F. BERSIHKAN & REDIRECT ===
        session()->put('last_order_time', Carbon::now());
        session()->forget('cart');

        return redirect("https://wa.me/$adminPhone?text=" . urlencode($message));
    }
}