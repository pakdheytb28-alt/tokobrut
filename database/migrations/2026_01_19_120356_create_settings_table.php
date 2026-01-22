<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // --- Identitas Dasar ---
            $table->string('shop_name');
            $table->string('shop_phone');
            $table->text('shop_address');
            
            // --- Identitas Tambahan (Baru) ---
            $table->string('slogan')->nullable();
            
            // --- Sosmed (Baru) ---
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('tiktok_link')->nullable();

            // --- Visual (Baru) ---
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('banner_path')->nullable(); 
            $table->string('theme_color')->default('#2563EB'); 

            // --- Sistem (Baru) ---
            $table->text('wa_greeting')->nullable(); 
            $table->text('meta_description')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};