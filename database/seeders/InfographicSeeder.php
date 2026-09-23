<?php

namespace Database\Seeders;

use App\Models\Infographic;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InfographicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * File fisik ada di database/seeders/data/infographics/ (lihat README.txt di folder itu),
     * di-copy ke storage/app/public/infographics/ mengikuti pola App\Actions\CreateInfographic
     * ($file->store('infographics', 'public')).
     *
     * CATATAN: deskripsi Booklet Wale di sumber data ("00_Infografis.txt") salah ketik —
     * menyebut "Kelurahan Baadia" padahal ini booklet Wale (jelas copy-paste dari template
     * Baadia). Diperbaiki jadi "Kelurahan Wale" di bawah, bukan dikopi apa adanya.
     */
    public function run(): void
    {
        $sourceDir = database_path('seeders/data/infographics');

        foreach ($this->data() as $entry) {
            $village = Village::where('name', $entry['village'])->first();

            if (!$village) {
                $this->command?->warn("InfographicSeeder: Kelurahan \"{$entry['village']}\" tidak ditemukan (cek VillageSeeder), dilewati.");
                continue;
            }

            $matches = File::glob($sourceDir . '/' . $entry['file'] . '.*');

            if (empty($matches)) {
                $this->command?->warn("InfographicSeeder: file \"{$entry['file']}.*\" tidak ditemukan di {$sourceDir}, dilewati. Lihat README.txt di folder itu.");
                continue;
            }

            $sourcePath = $matches[0];
            $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);

            $destPath = 'infographics/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($destPath, File::get($sourcePath));

            Infographic::create([
                'village_id' => $village->id,
                'title' => $entry['title'],
                'description' => $entry['description'],
                'file_path' => $destPath,
                'cover_path' => null,
            ]);
        }
    }

    private function data(): array
    {
        return [
            // ================= BAADIA =================
            [
                'village' => 'Baadia',
                'file' => 'Booklet Infografis Kelurahan Cantik Baadia 2025',
                'title' => 'BOOKLET INFOGRAFIS KELURAHAN CANTIK BAADIA 2025',
                'description' => 'Booklet ini menyajikan rangkuman visual dari data penting Kelurahan Baadia melalui infografis yang ringkas dan informatif. Berisi sorotan utama dari Kompilasi Data 2025, Pokelcan 2025, dan Potensi Pembangunan 2024. Cocok untuk pembaca yang ingin memahami kondisi dan potensi Baadia secara cepat namun tetap akurat.',
            ],
            [
                'village' => 'Baadia',
                'file' => 'Leaflet Kelurahan Cantik Baadia 2025',
                'title' => 'LEAFLET INFOGRAFIS KELURAHAN CANTIK BAADIA 2025',
                'description' => 'Leaflet ini menampilkan informasi inti hasil Pendataan Potensi Kelurahan Baadia 2025 dalam format singkat dan mudah dibaca. Menyajikan gambaran potensi wilayah, kondisi sosial-ekonomi, dan fasilitas yang ada di Baadia. Didesain untuk memberikan pemahaman cepat bagi masyarakat, wisatawan, dan pemangku kepentingan.',
            ],

            // ================= BATARAGURU =================
            [
                'village' => 'Bataraguru',
                'file' => 'Leaflet Infografis Kelurahan Bataraguru',
                'title' => 'LEAFLET INFOGRAFIS KELURAHAN CANTIK BATARAGURU 2026',
                'description' => 'Leaflet ini menampilkan informasi hasil kegiatan Kelurahan Cantik di Kelurahan Bataraguru 2026 dalam format singkat dan mudah dibaca. Menyajikan gambaran potensi wilayah, kondisi sosial-ekonomi, dan fasilitas yang ada di Bataraguru. Didesain untuk memberikan pemahaman cepat bagi masyarakat, wisatawan, dan pemangku kepentingan.',
            ],

            // ================= BATULO =================
            [
                'village' => 'Batulo',
                'file' => 'Booklet Infografis Kelurahan Batulo',
                'title' => 'BOOKLET INFOGRAFIS KELURAHAN CANTIK BATULO 2026',
                'description' => 'Booklet ini menyajikan rangkuman visual dari data penting Kelurahan Batulo melalui infografis yang ringkas dan informatif. Berisi sorotan utama dari Kompilasi Data 2026, Pokelcan 2026, dan Potensi Pembangunan 2025. Cocok untuk pembaca yang ingin memahami kondisi dan potensi Batulo secara cepat namun tetap akurat.',
            ],
            [
                'village' => 'Batulo',
                'file' => 'Leaflet Infografis Kelurahan Batulo',
                'title' => 'LEAFLET INFOGRAFIS KELURAHAN CANTIK BATULO 2026',
                'description' => 'Leaflet ini menampilkan informasi hasil kegiatan Kelurahan Cantik di Kelurahan Batulo 2026 dalam format singkat dan mudah dibaca. Menyajikan gambaran potensi wilayah, kondisi sosial-ekonomi, dan fasilitas yang ada di Batulo. Didesain untuk memberikan pemahaman cepat bagi masyarakat, wisatawan, dan pemangku kepentingan.',
            ],

            // ================= WALE =================
            // [
            //     'village' => 'Wale',
            //     'file' => 'Booklet Infografis Kelurahan Wale',
            //     'title' => 'BOOKLET INFOGRAFIS KELURAHAN CANTIK WALE 2026',
            //     // FIX typo sumber: "Kelurahan Baadia" -> "Kelurahan Wale"
            //     'description' => 'Booklet ini menyajikan rangkuman visual dari data penting Kelurahan Wale melalui infografis yang ringkas dan informatif. Berisi sorotan utama dari Kompilasi Data 2026, Pokelcan 2026, dan Potensi Pembangunan 2025. Cocok untuk pembaca yang ingin memahami kondisi dan potensi Kelurahan Wale secara cepat namun tetap akurat.',
            // ],
            [
                'village' => 'Wale',
                'file' => 'Leaflet Infografis Kelurahan Wale',
                'title' => 'LEAFLET INFOGRAFIS KELURAHAN CANTIK WALE 2026',
                'description' => 'Leaflet ini menampilkan informasi hasil kegiatan Kelurahan Cantik di Kelurahan Wale 2026 dalam format singkat dan mudah dibaca. Menyajikan gambaran potensi wilayah, kondisi sosial-ekonomi, dan fasilitas yang ada di Wale. Didesain untuk memberikan pemahaman cepat bagi masyarakat, wisatawan, dan pemangku kepentingan.',
            ],
        ];
    }
}