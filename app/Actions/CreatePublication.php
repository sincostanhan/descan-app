<?php

namespace App\Actions;

use App\Models\Publication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreatePublication
{
    public function handle(array $attributes)
    {
        if (isset($attributes['file'])) {
            $attributes['file_path'] = $attributes['file']->store('publications', 'public');
        }
        
        // cover
        $coverPath = null;
        // Proses penyimpanan Cover dari Base64 rahasia
        if (!empty($attributes['cover_base64'])) {
            $image_parts = explode(";base64,", $attributes['cover_base64']);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'publications/covers/' . Str::uuid() . '.jpg';
                Storage::disk('public')->put($fileName, $image_base64);
                $coverPath = $fileName;
            }
        }

        return Publication::create([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'file_path' => $attributes['file_path'] ?? null,
            'cover_path' => $coverPath,
        ]);
    }
}
