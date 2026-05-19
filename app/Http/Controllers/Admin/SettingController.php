<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        // Mengambil baris pertama, atau membuat data default jika tabel kosong
        $setting = Setting::firstOrCreate(
            ['id' => 1],
            ['village_name' => 'Kelurahan Baadia']
        );

        return view('admin.setting.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'village_name' => 'required|string|max:255',
            'village_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'theme_name'   => 'required|string',
        ]);

        $setting = Setting::first();

        $data = [
            'village_name' => $request->village_name,
            'theme_name'   => $request->theme_name,
        ];

        if ($request->hasFile('village_logo')) {
            // Hapus logo lama jika ada
            if ($setting->village_logo) {
                Storage::disk('public')->delete($setting->village_logo);
            }
            // Simpan logo baru
            $data['village_logo'] = $request->file('village_logo')->store('logos', 'public');
        }

        $setting->update($data);

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
