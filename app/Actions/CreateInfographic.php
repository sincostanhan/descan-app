<?php

namespace App\Actions;

use App\Models\Infographic;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateInfographic
{
    public function handle(array $attributes)
    {
        if (isset($attributes['file'])) {
            // Menyimpan file ke folder storage/app/public/infographics
            $attributes['file_path'] = $attributes['file']->store('infographics', 'public');
        }

        // cover
        $coverPath = null;
        // 2. Simpan Cover Hasil Ekstrak (HANYA JIKA FILE ADALAH PDF)
        if (!empty($attributes['cover_base64'])) {
            $image_parts = explode(";base64,", $attributes['cover_base64']);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'infographics/covers/' . Str::uuid() . '.jpg';
                Storage::disk('public')->put($fileName, $image_base64);
                $coverPath = $fileName;
            }
        }

        return Infographic::create([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'file_path' => $attributes['file_path'] ?? null,
            'cover_path' => $coverPath, // Jika file gambar, ini akan null
        ]);
    }
}
