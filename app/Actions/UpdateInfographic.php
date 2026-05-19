<?php

namespace App\Actions;

use App\Models\Infographic;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateInfographic
{
    public function handle(Infographic $infographic, array $attributes)
    {
        // 1. Proses File Baru (PDF atau Gambar) jika ada
        if (isset($attributes['file'])) {
            // Hapus file lama jika ada
            if ($infographic->file_path && Storage::disk('public')->exists($infographic->file_path)) {
                Storage::disk('public')->delete($infographic->file_path);
            }
            
            // Simpan file baru
            $attributes['file_path'] = $attributes['file']->store('infographics/files', 'public');

            // --- LOGIKA PENGAMANAN COVER ---
            // Jika file baru diunggah tapi tidak ada cover_base64,
            // berarti file baru ini adalah FOTO/GAMBAR.
            if (empty($attributes['cover_base64'])) {
                // Hapus cover lama (bekas PDF) dari storage jika ada
                if ($infographic->cover_path && Storage::disk('public')->exists($infographic->cover_path)) {
                    Storage::disk('public')->delete($infographic->cover_path);
                }
                // Paksa cover_path menjadi null agar sistem menggunakan gambar aslinya
                $attributes['cover_path'] = null; 
            }
        }

        // 2. Proses Cover Baru dari Base64 jika ada (Hanya jalan jika file barunya PDF)
        if (!empty($attributes['cover_base64'])) {
            // Hapus cover lama jika ada
            if ($infographic->cover_path && Storage::disk('public')->exists($infographic->cover_path)) {
                Storage::disk('public')->delete($infographic->cover_path);
            }

            $image_parts = explode(";base64,", $attributes['cover_base64']);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                // PERBAIKAN: Gunakan folder infographics
                $fileName = 'infographics/covers/' . Str::uuid() . '.jpg';
                Storage::disk('public')->put($fileName, $image_base64);
                $attributes['cover_path'] = $fileName;
            }
        }

        // 3. Susun data yang akan diupdate
        $updateData = [
            'title' => $attributes['title'],
            'description' => $attributes['description'],
        ];

        // Hanya timpa file_path jika ada file baru
        if (array_key_exists('file_path', $attributes)) {
            $updateData['file_path'] = $attributes['file_path'];
        }

        // Hanya timpa cover_path jika ada cover baru ATAU jika kita sengaja men-setnya ke null (saat ganti PDF ke Foto)
        if (array_key_exists('cover_path', $attributes)) {
            $updateData['cover_path'] = $attributes['cover_path'];
        }

        // Update database
        $infographic->update($updateData);

        return $infographic;
    }
}
