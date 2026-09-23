<?php

namespace Database\Seeders;

use App\Models\MetadataStatistik;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MetadataStatistikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * File fisik TIDAK ditaruh langsung di storage/app/public — itu tujuan akhir yang
     * dikelola Storage disk (sama seperti file yang diupload lewat form admin). Sumbernya
     * ada di database/seeders/data/metadata-statistik/, dengan nama file PERSIS sama
     * seperti 'title' di data() + ekstensi asli (pdf/jpg/png) — lihat README.txt di
     * folder itu. Seeder ini yang meng-copy-nya ke storage/app/public, mengikuti persis
     * pola yang dipakai App\Actions\CreateMetadataStatistik untuk upload lewat form admin.
     */
    public function run(): void
    {
        $sourceDir = database_path('seeders/data/metadata-statistik');

        foreach ($this->data() as $entry) {
            $village = Village::where('name', $entry['village'])->first();

            if (!$village) {
                $this->command?->warn("MetadataStatistikSeeder: Kelurahan \"{$entry['village']}\" tidak ditemukan (cek VillageSeeder), dilewati.");
                continue;
            }

            // Cari file source TANPA peduli ekstensi (pdf/jpg/png), match by nama persis.
            $matches = File::glob($sourceDir . '/' . $entry['title'] . '.*');

            if (empty($matches)) {
                $this->command?->warn("MetadataStatistikSeeder: file \"{$entry['title']}.*\" tidak ditemukan di {$sourceDir}, dilewati. Lihat README.txt di folder itu.");
                continue;
            }

            $sourcePath = $matches[0];
            $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);

            // Sama seperti $file->store('metadata-statistik/files', 'public') di Action asli,
            // cuma sumbernya bukan UploadedFile dari request, jadi di-copy manual.
            $destPath = 'metadata-statistik/files/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($destPath, File::get($sourcePath));

            MetadataStatistik::create([
                'village_id' => $village->id,
                'title' => $entry['title'],
                'file_path' => $destPath,
                'cover_path' => null, // Cover PDF opsional, di-generate manual lewat form edit kalau perlu.
            ]);
        }
    }

    private function data(): array
    {
        return [
            ['village' => 'Baadia', 'title' => 'MS-Kegiatan Pokelcan Baadia 2025'],
            ['village' => 'Baadia', 'title' => 'MS-Variabel Pokelcan Baadia 2025'],
            ['village' => 'Baadia', 'title' => 'MS-Indikator Pokelcan Baadia 2025'],

            ['village' => 'Bataraguru', 'title' => 'Bataraguru MS-Kegiatan'],
            ['village' => 'Bataraguru', 'title' => 'Bataraguru MS-Variabel'],
            ['village' => 'Bataraguru', 'title' => 'Bataraguru MS-Indikator'],

            ['village' => 'Batulo', 'title' => 'Batulo MS-Kegiatan'],
            ['village' => 'Batulo', 'title' => 'Batulo MS-Variabel'],
            ['village' => 'Batulo', 'title' => 'Batulo MS-Indikator'],

            ['village' => 'Wale', 'title' => 'Wale MS-Kegiatan'],
            ['village' => 'Wale', 'title' => 'Wale MS-Variabel'],
            ['village' => 'Wale', 'title' => 'Wale MS-Indikator'],
        ];
    }
}