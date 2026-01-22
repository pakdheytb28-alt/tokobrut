<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung data asli dari database
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        
        // Kita belum punya tabel pengunjung/order, jadi sementara kita kasih 0 atau random
        // Nanti bisa dikembangkan untuk hitung jumlah klik WA (jika ada log-nya)
        $todayVisitors = rand(10, 50); 

        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'todayVisitors'));
    }
}