<?php

namespace App\Actions;

use App\Models\MetadataStatistik;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateMetadataStatistik
{
    public function handle(array $attributes)
    {
        if (isset($attributes['file'])) {
            $attributes['file_path'] = $attributes['file']->store('metadata-statistik/files', 'public');
        }

        $coverPath = null;

        if (!empty($attributes['cover_base64'])) {
            $image_parts = explode(";base64,", $attributes['cover_base64']);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'metadata-statistik/covers/' . Str::uuid() . '.jpg';
                Storage::disk('public')->put($fileName, $image_base64);
                $coverPath = $fileName;
            }
        }

        return MetadataStatistik::create([
            'title' => $attributes['title'],
            'file_path' => $attributes['file_path'] ?? null,
            'cover_path' => $coverPath,
        ]);
    }
}