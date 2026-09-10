<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotensiWisataPhoto extends Model
{
    protected $fillable = ['potensi_wisata_id', 'foto_path'];

    public function potensiWisata()
    {
        return $this->belongsTo(PotensiWisata::class);
    }
}