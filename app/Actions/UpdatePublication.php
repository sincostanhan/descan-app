<?php

namespace App\Actions;

use App\Models\Publication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdatePublication
{
    public function handle(Publication $publication, array $attributes)
    {
        if (isset($attributes['file'])) {
            // Hapus file lama jika ada file baru yang diunggah
            if ($publication->file_path && Storage::disk('public')->exists($publication->file_path)) {
                Storage::disk('public')->delete($publication->file_path);
            }
            $attributes['file_path'] = $attributes['file']->store('publications', 'public');
        }
        
        // 2. Proses Cover Baru dari Base64 jika ada
        if (!empty($attributes['cover_base64'])) {
            // Hapus cover lama jika ada
            if ($publication->cover_path && Storage::disk('public')->exists($publication->cover_path)) {
                Storage::disk('public')->delete($publication->cover_path);
            }

            $image_parts = explode(";base64,", $attributes['cover_base64']);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'publications/covers/' . Str::uuid() . '.jpg';
                Storage::disk('public')->put($fileName, $image_base64);
                $attributes['cover_path'] = $fileName;
            }
        }

        $publication->update([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'file_path' => $attributes['file_path'] ?? $publication->file_path,
            'cover_path' => $attributes['cover_path'] ?? $publication->cover_path,
        ]);

        return $publication;
    }
}
