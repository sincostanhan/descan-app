<?php

namespace App\Support;

use Illuminate\Support\Str;

class FilenameSanitizer
{
    /**
     * Bersihkan judul jadi nama file yang aman diunduh, TANPA mengubah
     * kapitalisasi/spasi/titik aslinya (beda dengan Str::slug()).
     * Hanya membuang karakter yang ilegal di nama file Windows/Unix.
     */
    public static function fromTitle(string $title, string $fallback = 'file'): string
    {
        $clean = Str::of($title)
            ->replaceMatches('/[\\\\\/:*?"<>|]/', '')
            ->trim()
            ->trim('.')
            ->value();

        return $clean !== '' ? $clean : $fallback;
    }
}