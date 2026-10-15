<?php

namespace App\Http\Controllers\Admin;

use App\Actions\BuildContentChecklist;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function edit(BuildContentChecklist $checklistAction)
    {
        // Toleran terhadap Kelurahan yang belum pernah punya baris Setting sama sekali
        // (termasuk Kelurahan lama dari seeder yang belum ke-cover SettingSeeder) — instance
        // kosong dikirim ke view, baru benar-benar disimpan saat form di-submit.
        $setting = Setting::first() ?? new Setting();
        $checklist = $checklistAction->handle();
        $regions = config('kelurahan_kecamatan');

        // return view('admin.setting.edit', compact('setting', 'checklist'));
        return view('admin.setting.edit', compact('setting', 'checklist', 'regions'));
    }

    public function update(Request $request)
    {
        // $request->validate([
        //     'village_name' => 'required|string|max:255',
        //     'kecamatan'    => 'nullable|string|max:255',
        //     'village_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        //     'theme_name'   => 'required|string',
        // ]);
        $regions = config('kelurahan_kecamatan');

        $request->validate([
            'kecamatan'    => ['required', 'string', Rule::in(array_keys($regions))],
            'village_name' => [
                'required', 'string', 'max:255',
                function ($attribute, $value, $fail) use ($request, $regions) {
                    $daftarKelurahan = $regions[$request->kecamatan] ?? [];
                    if (!in_array($value, $daftarKelurahan, true)) {
                        $fail('Kelurahan yang dipilih tidak sesuai dengan Kecamatan yang dipilih.');
                    }
                },
            ],
            'village_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'theme_name'   => 'required|string',
        ]);

        $setting = Setting::first();

        $data = [
            'village_name' => $request->village_name,
            'kecamatan'    => $request->kecamatan,
            'theme_name'   => $request->theme_name,
            'is_published' => $request->boolean('is_published'),
        ];

        if ($request->hasFile('village_logo')) {
            // Hapus logo lama jika ada
            if ($setting?->village_logo) {
                Storage::disk('public')->delete($setting->village_logo);
            }
            // Simpan logo baru
            $data['village_logo'] = $request->file('village_logo')->store('logos', 'public');
        }

        if ($setting) {
            $setting->update($data);
        } else {
            // village_id otomatis terisi oleh BelongsToVillage trait (current_village_id
            // SUDAH ter-set di sini, karena SettingController hanya diakses lewat subdomain).
            Setting::create($data);
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
