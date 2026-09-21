<?php

namespace App\Actions;

use App\Models\MetadataStatistik;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateMetadataStatistik
{
    public function handle(MetadataStatistik $metadataStatistik, array $attributes)
    {
        // 1. Proses File Baru (PDF atau Gambar) jika ada
        if (isset($attributes['file'])) {
            if ($metadataStatistik->file_path && Storage::disk('public')->exists($metadataStatistik->file_path)) {
                Storage::disk('public')->delete($metadataStatistik->file_path);
            }

            $attributes['file_path'] = $attributes['file']->store('metadata-statistik/files', 'public');

            // Jika file baru diunggah tapi tidak ada cover_base64 -> file baru adalah FOTO/GAMBAR
            if (empty($attributes['cover_base64'])) {
                if ($metadataStatistik->cover_path && Storage::disk('public')->exists($metadataStatistik->cover_path)) {
                    Storage::disk('public')->delete($metadataStatistik->cover_path);
                }
                $attributes['cover_path'] = null;
            }
        }

        // 2. Proses Cover Baru dari Base64 (hanya jalan jika file barunya PDF)
        if (!empty($attributes['cover_base64'])) {
            if ($metadataStatistik->cover_path && Storage::disk('public')->exists($metadataStatistik->cover_path)) {
                Storage::disk('public')->delete($metadataStatistik->cover_path);
            }

            $image_parts = explode(";base64,", $attributes['cover_base64']);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'metadata-statistik/covers/' . Str::uuid() . '.jpg';
                Storage::disk('public')->put($fileName, $image_base64);
                $attributes['cover_path'] = $fileName;
            }
        }

        $updateData = ['title' => $attributes['title']];

        if (array_key_exists('file_path', $attributes)) {
            $updateData['file_path'] = $attributes['file_path'];
        }
        if (array_key_exists('cover_path', $attributes)) {
            $updateData['cover_path'] = $attributes['cover_path'];
        }

        $metadataStatistik->update($updateData);

        return $metadataStatistik;
    }
}