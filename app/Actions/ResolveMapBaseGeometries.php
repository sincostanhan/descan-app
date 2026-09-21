<?php

namespace App\Actions;

use App\Models\RegionGeometry;
use App\Models\Setting;
use App\Models\Village;

class ResolveMapBaseGeometries
{
    /**
     * Rangkai SELURUH RT/RW yang punya poligon jadi 1 FeatureCollection TANPA data statistik —
     * dipakai sebagai lapisan dasar (warna netral) di Dashboard Peta Publik, tampil sejak halaman
     * dibuka, SEBELUM Tabel/Kolom dipilih. Begitu Tabel+Kolom dipilih, lapisan ini digantikan
     * oleh hasil ResolveMapChoroplethData (yang berwarna sesuai nilai).
     */
    public function handle(): array
    {
        $geometries = RegionGeometry::all();

        $village = app()->bound('current_village_id') ? Village::find(app('current_village_id')) : null;
        $setting = Setting::first();

        $features = $geometries->map(function (RegionGeometry $g) use ($village, $setting) {
            return [
                'type' => 'Feature',
                'geometry' => $g->geojson,
                'properties' => [
                    'kelurahan' => $village?->name,
                    'kecamatan' => $setting?->kecamatan,
                    'rt' => $g->rt,
                    'rw' => $g->rw,
                    'rt_label' => 'RT ' . str_pad((string) (int) $g->rt, 3, '0', STR_PAD_LEFT),
                    'rw_label' => 'RW ' . str_pad((string) (int) $g->rw, 3, '0', STR_PAD_LEFT),
                ],
            ];
        })->values()->all();

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];
    }
}