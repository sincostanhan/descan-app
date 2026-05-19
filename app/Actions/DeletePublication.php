<?php

namespace App\Actions;

use App\Models\Publication;
use Illuminate\Support\Facades\Storage;

class DeletePublication
{
    public function handle(Publication $publication)
    {
        if ($publication->file_path && Storage::disk('public')->exists($publication->file_path)) {
            Storage::disk('public')->delete($publication->file_path);
        }

        $publication->delete();
    }
}
