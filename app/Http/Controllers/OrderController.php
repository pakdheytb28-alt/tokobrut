<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1. Halaman Daftar Pesanan
    public function index()
    {
        // Ambil data dari tabel 'orders', urutkan dari yang terbaru
        $orders = DB::table('orders')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // 2. Halaman Detail Pesanan
    public function show($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        if(!$order) return redirect()->back();
        
        return view('admin.orders.show', compact('order'));
    }

    // 3. Aksi: Tandai Sudah Dikirim
    public function markAsShipped($id)
    {
        DB::table('orders')->where('id', $id)->update([
            'status' => 'shipped',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi DIKIRIM!');
    }
    // 4. Hapus Pesanan
    public function destroy($id)
    {
        DB::table('orders')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Pesanan berhasil dihapus!');
    }
}