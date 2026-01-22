<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first() ?? new Setting();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'banner'    => 'nullable|image|max:2048',
            'logo'      => 'nullable|image|max:1024',
            'favicon'   => 'nullable|image|max:512',
        ]);

        $setting = Setting::first() ?? new Setting();

        // 2. Simpan Data Teks
        $setting->shop_name  = $request->shop_name;
        $setting->slogan     = $request->slogan;
        $setting->shop_phone = $request->shop_phone;
        $setting->address    = $request->address;
        $setting->theme_color = $request->theme_color ?? '#3b82f6';

        // Simpan Sosmed
        $setting->facebook   = $request->facebook;
        $setting->instagram  = $request->instagram;
        $setting->tiktok     = $request->tiktok;
        $setting->youtube    = $request->youtube;
        $setting->shopee     = $request->shopee;
        $setting->tokopedia  = $request->tokopedia;

        // --- FITUR BARU: HAPUS GAMBAR (RESET) ---
        
        // A. Hapus Logo
        if ($request->has('delete_logo')) {
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $setting->logo_path = null;
        }

        // B. Hapus Favicon
        if ($request->has('delete_favicon')) {
            if ($setting->favicon_path && Storage::disk('public')->exists($setting->favicon_path)) {
                Storage::disk('public')->delete($setting->favicon_path);
            }
            $setting->favicon_path = null;
        }

        // C. Hapus Banner
        if ($request->has('delete_banner')) {
            if ($setting->banner_path && Storage::disk('public')->exists($setting->banner_path)) {
                Storage::disk('public')->delete($setting->banner_path);
            }
            $setting->banner_path = null;
        }


        // --- LOGIKA UPLOAD BARU (Akan menimpa aksi hapus jika user juga upload file) ---
        
        if ($request->hasFile('banner')) {
            // Hapus lama dulu (jika belum dihapus di atas)
            if ($setting->banner_path && Storage::disk('public')->exists($setting->banner_path)) {
                Storage::disk('public')->delete($setting->banner_path);
            }
            $setting->banner_path = $request->file('banner')->store('settings', 'public');
        }

        if ($request->hasFile('logo')) {
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $setting->logo_path = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($setting->favicon_path && Storage::disk('public')->exists($setting->favicon_path)) {
                Storage::disk('public')->delete($setting->favicon_path);
            }
            $setting->favicon_path = $request->file('favicon')->store('settings', 'public');
        }

        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }
}