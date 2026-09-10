<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotensiWisataPhoto extends Model
{
    // protected $fillable = ['potensi_wisata_id', 'foto_path'];
    protected $fillable = ['potensi_wisata_id', 'foto_path', 'tampil_beranda'];

    protected function casts(): array
    {
        return ['tampil_beranda' => 'boolean'];
    }

    public function potensiWisata()
    {
        return $this->belongsTo(PotensiWisata::class);
    }
}