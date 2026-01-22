<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        // Identitas
        'shop_name', 'shop_phone', 'shop_address', 'slogan', 
        
        // Visual
        'logo_path', 'favicon_path', 'banner_path', 'theme_color', 
        
        // Sistem
        'wa_greeting', 'meta_description',
        
        // SOSMED LENGKAP
        'facebook_link', 
        'instagram_link',
        'tiktok_link',      // Baru
        'youtube_link',     // Baru
        'twitter_link',     // Baru
        'shopee_link',      // Baru
        'tokopedia_link',   // Baru
    ];
}