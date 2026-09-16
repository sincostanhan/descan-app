<?php

namespace App\Actions;

use App\Models\RegionGeometry;
use App\Models\Village;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ImportRegionGeometriesFromGeoJson
{
    /**
     * Import massal poligon RT/RW dari 1 FeatureCollection GeoJSON gabungan (lintas Kelurahan),
     * dicocokkan otomatis lewat properties.NAMA_KELURAHAN / NAMA_RW / NAMA_RT tiap feature.
     *
     * SEMUA-ATAU-TIDAK-SAMA-SEKALI: kalau ada 1 saja feature yang gagal dicocokkan/divalidasi,
     * SELURUH import ditolak (tidak ada perubahan sebagian tersimpan) — supaya region_geometries
     * tidak pernah berakhir dalam kondisi "separuh ter-update, separuh nyangkut error".
     *
     * @return array{created:int,updated:int,total:int}
     */
    public function handle(string $rawGeoJson): array
    {
        $data = json_decode($rawGeoJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                'geojson' => 'GeoJSON tidak valid: ' . json_last_error_msg(),
            ]);
        }

        if (($data['type'] ?? null) !== 'FeatureCollection' || !is_array($data['features'] ?? null)) {
            throw ValidationException::withMessages([
                'geojson' => 'Struktur GeoJSON harus berupa FeatureCollection dengan array "features".',
            ]);
        }

        $villagesByName = Village::all()->keyBy(fn (Village $v) => strtolower(trim($v->name)));

        // TAHAP 1: validasi SEMUA feature dulu TANPA menulis apapun ke DB — supaya kalau ada
        // yang gagal, belum ada perubahan yang kepalang tersimpan (prinsip all-or-nothing).
        $resolved = [];
        $errors = [];

        foreach ($data['features'] as $index => $feature) {
            $props = $feature['properties'] ?? [];
            $namaKelurahan = trim($props['NAMA_KELURAHAN'] ?? '');
            $namaRw = trim($props['NAMA_RW'] ?? '');
            $namaRt = trim($props['NAMA_RT'] ?? '');
            $geometry = $feature['geometry'] ?? null;

            if ($namaKelurahan === '' || $namaRw === '' || $namaRt === '') {
                $errors[] = "Feature #{$index}: properties NAMA_KELURAHAN/NAMA_RW/NAMA_RT tidak lengkap.";
                continue;
            }

            $village = $villagesByName->get(strtolower($namaKelurahan));
            if (!$village) {
                $errors[] = "Feature #{$index}: Kelurahan \"{$namaKelurahan}\" tidak ditemukan di database.";
                continue;
            }

            if (!preg_match('/(\d+)/', $namaRw, $rwMatch) || !preg_match('/(\d+)/', $namaRt, $rtMatch)) {
                $errors[] = "Feature #{$index}: format NAMA_RW/NAMA_RT tidak mengandung angka (\"{$namaRw}\" / \"{$namaRt}\").";
                continue;
            }

            if (!in_array($geometry['type'] ?? null, ['Polygon', 'MultiPolygon'], true)) {
                $errors[] = "Feature #{$index}: geometry.type harus Polygon atau MultiPolygon.";
                continue;
            }

            $resolved[] = [
                'village_id' => $village->id,
                // Buang leading zero ("001" -> "1") supaya cocok format organizations.daftar_rt
                // yang sudah ada (angka polos, bukan zero-padded — lihat hasil backfill rw_value).
                'rt' => (string) (int) $rtMatch[1],
                'rw' => (string) (int) $rwMatch[1],
                'geojson' => $geometry,
            ];
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages(['geojson' => $errors]);
        }

        // TAHAP 2: semua feature valid — baru simpan, dibungkus transaction.
        $created = 0;
        $updated = 0;

        DB::transaction(function () use ($resolved, &$created, &$updated) {
            foreach ($resolved as $row) {
                $geometry = RegionGeometry::updateOrCreate(
                    ['village_id' => $row['village_id'], 'rt' => $row['rt'], 'rw' => $row['rw']],
                    ['geojson' => $row['geojson']]
                );

                $geometry->wasRecentlyCreated ? $created++ : $updated++;
            }
        });

        return ['created' => $created, 'updated' => $updated, 'total' => count($resolved)];
    }
}