<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel categories
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 15, 2); // Harga (maks 15 digit)
            $table->integer('stock');        // Jumlah stok
            $table->text('description');     // Deskripsi teks polos
            
            // Status produk
            $table->boolean('is_featured')->default(false); // Unggulan?
            $table->boolean('is_active')->default(true);    // Tampil?
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
