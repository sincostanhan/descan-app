<?php

namespace App\Http\Controllers\AdminBps;

use App\Actions\ImportRegionGeometriesFromGeoJson;
use App\Http\Controllers\Controller;
use App\Models\RegionGeometry;
use App\Models\Village;
use Illuminate\Http\Request;

class RegionGeometryController extends Controller
{
    /**
     * Tampilkan editor GeoJSON gabungan: textarea di-prefill dari data region_geometries
     * yang sudah ada saat ini (lintas semua Kelurahan), supaya BPS bisa lihat kondisi
     * terkini sebelum menimpa/menambah, lalu preview di peta sebelah kiri.
     */
    public function index()
    {
        $existing = RegionGeometry::with('village')->get();
        $villages = Village::orderBy('name')->get(['id', 'name']);

        $featureCollection = [
            'type' => 'FeatureCollection',
            'features' => $existing->map(fn (RegionGeometry $g) => [
                'type' => 'Feature',
                'properties' => [
                    'join_key' => strtoupper($g->village->name) . "-RW{$g->rw}-RT{$g->rt}",
                    'NAMA_KELURAHAN' => strtoupper($g->village->name),
                    'NAMA_RW' => 'RW ' . str_pad($g->rw, 3, '0', STR_PAD_LEFT),
                    'NAMA_RT' => 'RT ' . str_pad($g->rt, 3, '0', STR_PAD_LEFT),
                ],
                'geometry' => $g->geojson,
            ])->values(),
        ];

        return view('admin-bps.region-geometries.index', [
            'geojsonText' => json_encode($featureCollection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            'existingCount' => $existing->count(),
            'villages' => $villages,
        ]);
    }

    /**
     * Import/replace poligon dari FeatureCollection yang ditempel BPS di textarea.
     * Validasi mendalam (pencocokan Kelurahan, parsing RT/RW) didelegasikan ke Action Class.
     */
    public function store(Request $request, ImportRegionGeometriesFromGeoJson $action)
    {
        $validated = $request->validate([
            'geojson' => ['required', 'string'],
        ]);

        $summary = $action->handle($validated['geojson']);

        return back()->with(
            'success',
            "Import berhasil: {$summary['created']} poligon baru dibuat, {$summary['updated']} diperbarui (total {$summary['total']} feature diproses)."
        );
    }
}