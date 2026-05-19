<?php

namespace App\Actions;

use App\Models\Infographic;
use Illuminate\Support\Facades\Storage;

class DeleteInfographic
{
    public function handle(Infographic $infographic)
    {
        // Hapus file fisik dari storage
        if ($infographic->file_path && Storage::disk('public')->exists($infographic->file_path)) {
            Storage::disk('public')->delete($infographic->file_path);
        }

        // Hapus data dari database
        $infographic->delete();
    }
}
