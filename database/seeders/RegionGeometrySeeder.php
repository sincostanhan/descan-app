<?php

namespace Database\Seeders;

use App\Actions\ImportRegionGeometriesFromGeoJson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RegionGeometrySeeder extends Seeder
{
    /**
     * Seed poligon RT/RW (Bataraguru, Wale, Batulo) dari file GeoJSON gabungan.
     *
     * SENGAJA reuse Action yang sama dipakai form "Kelola Peta Wilayah" Admin BPS
     * (ImportRegionGeometriesFromGeoJson), bukan menulis ulang logic pencocokan
     * Kelurahan/RT/RW di sini — supaya cuma ada 1 sumber kebenaran untuk proses
     * import, dan seeder ini murni berperan sebagai "penyedia data".
     *
     * PENTING (baca App\Actions\ImportRegionGeometriesFromGeoJson): Action ini
     * melakukan SINKRONISASI PENUH — SELURUH isi tabel region_geometries (lintas
     * semua Kelurahan) dihapus lalu diganti isi file ini. Kalau nanti ada Kelurahan
     * lain (mis. Baadia) yang juga punya geometrinya sendiri, GABUNGKAN semua
     * feature-nya ke dalam 1 file GeoJSON sebelum di-seed, atau ubah Action-nya
     * jadi sinkronisasi per-Kelurahan — JANGAN panggil Action ini berkali-kali
     * dengan file terpisah per Kelurahan, karena panggilan terakhir akan menimpa
     * hasil panggilan sebelumnya.
     *
     * Butuh Village sudah ada (dicocokkan lewat NAMA_KELURAHAN), jadi seeder ini
     * WAJIB dijalankan setelah VillageSeeder.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/region_geometries.geojson');

        if (!File::exists($path)) {
            $this->command?->warn("RegionGeometrySeeder dilewati: file tidak ditemukan di {$path}");
            return;
        }

        $summary = app(ImportRegionGeometriesFromGeoJson::class)->handle(File::get($path));

        $this->command?->info(
            "RegionGeometrySeeder: {$summary['total']} poligon RT/RW tersimpan (sebelumnya {$summary['before']})."
        );
    }
}