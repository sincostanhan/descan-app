<?php

namespace App\Actions;

use App\Models\MetadataStatistik;
use Illuminate\Support\Facades\Storage;

class DeleteMetadataStatistik
{
    public function handle(MetadataStatistik $metadataStatistik)
    {
        if ($metadataStatistik->file_path && Storage::disk('public')->exists($metadataStatistik->file_path)) {
            Storage::disk('public')->delete($metadataStatistik->file_path);
        }
        if ($metadataStatistik->cover_path && Storage::disk('public')->exists($metadataStatistik->cover_path)) {
            Storage::disk('public')->delete($metadataStatistik->cover_path);
        }

        $metadataStatistik->delete();
    }
}