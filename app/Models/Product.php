<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // DAFTAR KOLOM YANG BOLEH DIISI (WAJIB ADA 'variants')
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'is_featured',
        'variants', // <--- INI KUNCINYA! JANGAN SAMPAI LUPA
        'variant_name',     // <--- KOLOM BARU (WAJIB ADA)
        'variant_options',  // <--- KOLOM BARU (WAJIB ADA)
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}