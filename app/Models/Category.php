<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // KITA TAMBAHKAN 'icon' DI SINI AGAR BISA DISIMPAN
    protected $fillable = [
        'name',
        'slug',
        'icon' // <--- WAJIB ADA!
    ];

    // Relasi: Satu kategori punya banyak produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}