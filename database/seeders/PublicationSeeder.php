<?php

namespace Database\Seeders;

use App\Models\Publication;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * File fisik ada di database/seeders/data/publications/ (lihat README.txt di folder itu),
     * di-copy ke storage/app/public/publications/ mengikuti persis pola yang dipakai
     * App\Actions\CreatePublication ($file->store('publications', 'public')).
     */
    public function run(): void
    {
        $sourceDir = database_path('seeders/data/publications');

        foreach ($this->data() as $entry) {
            $village = Village::where('name', $entry['village'])->first();

            if (!$village) {
                $this->command?->warn("PublicationSeeder: Kelurahan \"{$entry['village']}\" tidak ditemukan (cek VillageSeeder), dilewati.");
                continue;
            }

            $matches = File::glob($sourceDir . '/' . $entry['file'] . '.*');

            if (empty($matches)) {
                $this->command?->warn("PublicationSeeder: file \"{$entry['file']}.*\" tidak ditemukan di {$sourceDir}, dilewati. Lihat README.txt di folder itu.");
                continue;
            }

            $sourcePath = $matches[0];
            $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);

            $destPath = 'publications/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($destPath, File::get($sourcePath));

            Publication::create([
                'village_id' => $village->id,
                'title' => $entry['title'],
                'description' => $entry['description'],
                'file_path' => $destPath,
                'cover_path' => null, // Cover PDF opsional, di-generate manual lewat form edit kalau perlu.
            ]);
        }
    }

    private function data(): array
    {
        return [
            // ================= BAADIA =================
            [
                'village' => 'Baadia',
                'file' => 'Pokelcan Baadia 2025',
                'title' => 'HASIL PENDATAAN POKELCAN 2025 KELURAHAN BAADIA',
                'description' => 'Publikasi ini memotret potensi wilayah, infrastruktur, serta kondisi sosial-ekonomi di setiap RT di Kelurahan Baadia secara detail. Informasi meliputi kependudukan, perumahan, pendidikan, kesehatan, sosial budaya, ekonomi, hingga keamanan. Publikasi ini mendukung perencanaan pembangunan yang tepat sasaran berbasis data resmi dan terkini.',
            ],
            [
                'village' => 'Baadia',
                'file' => 'Kompilasi Data Baadia 2025',
                'title' => 'KOMPILASI DATA KELURAHAN BAADIA 2025 (PROFIL KELURAHAN)',
                'description' => 'Publikasi ini menyajikan gambaran lengkap kondisi sosial, ekonomi, kependudukan, serta sarana dan prasarana di Kelurahan Baadia hingga tahun 2025. Data disusun secara sistematis dalam 11 bab mencakup penduduk, pendidikan, kesehatan, hingga pemerintahan kelurahan. Kompilasi ini menjadi rujukan penting bagi pengambil kebijakan, peneliti, dan masyarakat yang membutuhkan informasi akurat tentang Baadia.',
            ],
            [
                'village' => 'Baadia',
                'file' => 'Podes 2024 - Baadia',
                'title' => 'POTENSI PEMBANGUNAN KELURAHAN BAADIA 2024 (HASIL PENDATAAN PODES 2024)',
                'description' => 'Publikasi ini menampilkan data strategis tentang kondisi geografis, infrastruktur, sosial, ekonomi, dan potensi wilayah Kelurahan Baadia. Data dikumpulkan melalui pendataan sensus kewilayahan PODES 2024 oleh BPS, sehingga mencerminkan potret terkini kelurahan. Publikasi ini bermanfaat bagi pemerintah, peneliti, dan pihak lain dalam merumuskan kebijakan pembangunan yang berkelanjutan.',
            ],

            // ================= BATARAGURU =================
            [
                'village' => 'Bataraguru',
                'file' => 'Hasil Pendataan Pokelcan 2026 Kelurahan Bataraguru',
                'title' => 'HASIL PENDATAAN POKELCAN 2026 KELURAHAN BATARAGURU',
                'description' => 'Publikasi ini memotret potensi wilayah, infrastruktur, serta kondisi sosial-ekonomi di setiap RT di Kelurahan Bataraguru secara detail. Informasi meliputi kependudukan, perumahan, pendidikan, kesehatan, sosial budaya, ekonomi, hingga keamanan. Publikasi ini mendukung perencanaan pembangunan yang tepat sasaran berbasis data resmi dan terkini.',
            ],
            [
                'village' => 'Bataraguru',
                'file' => 'Kompilasi Data Kelurahan Bataraguru 2026',
                'title' => 'KOMPILASI DATA KELURAHAN BATARAGURU 2026 (PROFIL KELURAHAN)',
                'description' => 'Publikasi ini menyajikan gambaran lengkap kondisi sosial, ekonomi, kependudukan, serta sarana dan prasarana di Kelurahan Bataraguru hingga tahun 2026. Data disusun secara sistematis dalam 11 bab mencakup penduduk, pendidikan, kesehatan, hingga pemerintahan kelurahan. Selain itu, penyusunan publikasi ini juga merujuk pada sistematika ragam data yang terdapat pada Permendagri 12/2007 tentang Pedoman Penyusunan dan Pendayagunaan data Profil Desa dan Kelurahan. Kompilasi ini diharapkan dapat menjadi rujukan penting bagi pengambil kebijakan, peneliti, dan masyarakat yang membutuhkan informasi akurat tentang Bataraguru.',
            ],
            [
                'village' => 'Bataraguru',
                'file' => 'Potensi Pembangunan Kelurahan Bataraguru 2025',
                'title' => 'POTENSI PEMBANGUNAN KELURAHAN BATARAGURU 2025 (HASIL PENDATAAN PODES 2024-2025)',
                'description' => 'Publikasi ini menampilkan data strategis tentang kondisi geografis, infrastruktur, sosial, ekonomi, dan potensi wilayah Kelurahan Bataraguru. Data dikumpulkan melalui pendataan sensus kewilayahan PODES 2024 dan 2025 oleh BPS, sehingga mencerminkan potret terkini kelurahan. Publikasi ini bermanfaat bagi pemerintah, peneliti, dan pihak lain dalam merumuskan kebijakan pembangunan yang berkelanjutan.',
            ],
            [
                'village' => 'Bataraguru',
                'file' => 'Analisis Isu Strategis dan Rekomendasi Kebijakan - Kelurahan Bataraguru',
                'title' => 'ANALISIS ISU STRATEGIS DAN REKOMENDASI KEBIJAKAN BERBASIS DATA HASIL KEGIATAN KELURAHAN CANTIK 2026 - KELURAHAN BATARAGURU',
                'description' => 'Analisis Isu Strategis dan Rekomendasi Kebijakan Berbasis Data Hasil Kegiatan Kelurahan Cantik 2026 - Kelurahan Bataraguru merupakan bagian dari output kegiatan Kelurahan Cinta Statistik (Kelurahan Cantik) Kota Baubau yang disusun berdasarkan hasil pengolahan dan analisis data Pendataan Potensi Kelurahan Cinta Statistik (Pokelcan) Tahun 2026 serta data pendukung terkait. Analisis ini mengidentifikasi sejumlah isu strategis yang ditemukan pada tingkat kelurahan hingga RT, dengan mempertimbangkan kondisi sosial, ekonomi, kesehatan, lingkungan, pelayanan dasar, perlindungan sosial, maupun aspek kewilayahan lainnya. Setiap isu dianalisis berdasarkan data dan kondisi wilayah yang tersedia, kemudian dilengkapi dengan gambaran dampak serta rekomendasi kebijakan yang dapat menjadi bahan pertimbangan dalam penentuan prioritas pembangunan dan intervensi di tingkat kelurahan. Dokumen ini diharapkan dapat membantu memperkuat pemanfaatan data sebagai dasar perencanaan pembangunan yang lebih tepat sasaran, berbasis kebutuhan wilayah, dan responsif terhadap kondisi masyarakat.',
            ],

            // ================= BATULO =================
            [
                'village' => 'Batulo',
                'file' => 'Hasil Pendataan Pokelcan 2026 Kelurahan Batulo',
                'title' => 'HASIL PENDATAAN POKELCAN 2026 KELURAHAN BATULO',
                'description' => 'Publikasi ini memotret potensi wilayah, infrastruktur, serta kondisi sosial-ekonomi di setiap RT di Kelurahan Batulo secara detail. Informasi meliputi kependudukan, perumahan, pendidikan, kesehatan, sosial budaya, ekonomi, hingga keamanan. Publikasi ini mendukung perencanaan pembangunan yang tepat sasaran berbasis data resmi dan terkini.',
            ],
            [
                'village' => 'Batulo',
                'file' => 'Kompilasi Data Kelurahan Batulo 2026',
                'title' => 'KOMPILASI DATA KELURAHAN BATULO 2026 (PROFIL KELURAHAN)',
                'description' => 'Publikasi ini menyajikan gambaran lengkap kondisi sosial, ekonomi, kependudukan, serta sarana dan prasarana di Kelurahan Batulo hingga tahun 2026. Data disusun secara sistematis dalam 11 bab mencakup penduduk, pendidikan, kesehatan, hingga pemerintahan kelurahan. Kompilasi ini menjadi rujukan penting bagi pengambil kebijakan, peneliti, dan masyarakat yang membutuhkan informasi akurat tentang Batulo.',
            ],
            [
                'village' => 'Batulo',
                'file' => 'Potensi Pembangunan Kelurahan Batulo 2025',
                'title' => 'POTENSI PEMBANGUNAN KELURAHAN BATULO 2025 (HASIL PENDATAAN PODES 2024-2025)',
                'description' => 'Publikasi ini menampilkan data strategis tentang kondisi geografis, infrastruktur, sosial, ekonomi, dan potensi wilayah Kelurahan Batulo. Data dikumpulkan melalui pendataan sensus kewilayahan PODES 2024-2025 oleh BPS, sehingga mencerminkan potret terkini kelurahan. Publikasi ini bermanfaat bagi pemerintah, peneliti, dan pihak lain dalam merumuskan kebijakan pembangunan yang berkelanjutan.',
            ],
            [
                'village' => 'Batulo',
                'file' => 'Analisis Isu Strategis dan Rekomendasi Kebijakan - Kelurahan Batulo',
                'title' => 'ANALISIS ISU STRATEGIS DAN REKOMENDASI KEBIJAKAN BERBASIS DATA HASIL KEGIATAN KELURAHAN CANTIK 2026 - KELURAHAN BATULO',
                'description' => 'Analisis Isu Strategis dan Rekomendasi Kebijakan Berbasis Data Hasil Kegiatan Kelurahan Cantik 2026 - Kelurahan Batulo merupakan bagian dari output kegiatan Kelurahan Cinta Statistik (Kelurahan Cantik) Kota Baubau yang disusun berdasarkan hasil pengolahan dan analisis data Pendataan Potensi Kelurahan Cinta Statistik (Pokelcan) Tahun 2026 serta data pendukung terkait. Analisis ini mengidentifikasi sejumlah isu strategis yang ditemukan pada tingkat kelurahan hingga RT, dengan mempertimbangkan kondisi sosial, ekonomi, kesehatan, lingkungan, pelayanan dasar, perlindungan sosial, maupun aspek kewilayahan lainnya. Setiap isu dianalisis berdasarkan data dan kondisi wilayah yang tersedia, kemudian dilengkapi dengan gambaran dampak serta rekomendasi kebijakan yang dapat menjadi bahan pertimbangan dalam penentuan prioritas pembangunan dan intervensi di tingkat kelurahan. Dokumen ini diharapkan dapat membantu memperkuat pemanfaatan data sebagai dasar perencanaan pembangunan yang lebih tepat sasaran, berbasis kebutuhan wilayah, dan responsif terhadap kondisi masyarakat.',
            ],

            // ================= WALE =================
            [
                'village' => 'Wale',
                'file' => 'Hasil Pendataan Pokelcan 2026 Kelurahan Wale',
                'title' => 'HASIL PENDATAAN POKELCAN 2026 KELURAHAN WALE',
                'description' => 'Publikasi ini memotret potensi wilayah, infrastruktur, serta kondisi sosial-ekonomi di setiap RT di Kelurahan Wale secara detail. Informasi meliputi kependudukan, perumahan, pendidikan, kesehatan, sosial budaya, ekonomi, hingga keamanan. Publikasi ini mendukung perencanaan pembangunan yang tepat sasaran berbasis data resmi dan terkini.',
            ],
            [
                'village' => 'Wale',
                'file' => 'Kompilasi Data Kelurahan Wale 2026',
                'title' => 'KOMPILASI DATA KELURAHAN WALE 2026 (PROFIL KELURAHAN)',
                'description' => 'Publikasi ini menyajikan gambaran lengkap kondisi sosial, ekonomi, kependudukan, serta sarana dan prasarana di Kelurahan Wale hingga tahun 2026. Data disusun secara sistematis dalam 11 bab mencakup penduduk, pendidikan, kesehatan, hingga pemerintahan kelurahan. Selain itu, penyusunan publikasi ini juga merujuk pada sistematika ragam data yang terdapat pada Permendagri 12/2007 tentang Pedoman Penyusunan dan Pendayagunaan data Profil Desa dan Kelurahan. Kompilasi ini diharapkan dapat menjadi rujukan penting bagi pengambil kebijakan, peneliti, dan masyarakat yang membutuhkan informasi akurat tentang Wale.',
            ],
            [
                'village' => 'Wale',
                'file' => 'Potensi Pembangunan Kelurahan Wale 2025',
                'title' => 'POTENSI PEMBANGUNAN KELURAHAN WALE 2025 (HASIL PENDATAAN PODES 2024-2025)',
                'description' => 'Publikasi ini menampilkan data strategis tentang kondisi geografis, infrastruktur, sosial, ekonomi, dan potensi wilayah Kelurahan Wale. Data dikumpulkan melalui pendataan sensus kewilayahan PODES 2024 dan 2025 oleh BPS, sehingga mencerminkan potret terkini kelurahan. Publikasi ini bermanfaat bagi pemerintah, peneliti, dan pihak lain dalam merumuskan kebijakan pembangunan yang berkelanjutan.',
            ],
            [
                'village' => 'Wale',
                'file' => 'Analisis Isu Strategis dan Rekomendasi Kebijakan - Kelurahan Wale',
                'title' => 'ANALISIS ISU STRATEGIS DAN REKOMENDASI KEBIJAKAN BERBASIS DATA HASIL KEGIATAN KELURAHAN CANTIK 2026 - KELURAHAN WALE',
                'description' => 'Analisis Isu Strategis dan Rekomendasi Kebijakan Berbasis Data Hasil Kegiatan Kelurahan Cantik 2026 - Kelurahan Wale merupakan bagian dari output kegiatan Kelurahan Cinta Statistik (Kelurahan Cantik) Kota Baubau yang disusun berdasarkan hasil pengolahan dan analisis data Pendataan Potensi Kelurahan Cinta Statistik (Pokelcan) Tahun 2026 serta data pendukung terkait. Analisis ini mengidentifikasi sejumlah isu strategis yang ditemukan pada tingkat kelurahan hingga RT, dengan mempertimbangkan kondisi sosial, ekonomi, kesehatan, lingkungan, pelayanan dasar, perlindungan sosial, maupun aspek kewilayahan lainnya. Setiap isu dianalisis berdasarkan data dan kondisi wilayah yang tersedia, kemudian dilengkapi dengan gambaran dampak serta rekomendasi kebijakan yang dapat menjadi bahan pertimbangan dalam penentuan prioritas pembangunan dan intervensi di tingkat kelurahan. Dokumen ini diharapkan dapat membantu memperkuat pemanfaatan data sebagai dasar perencanaan pembangunan yang lebih tepat sasaran, berbasis kebutuhan wilayah, dan responsif terhadap kondisi masyarakat.',
            ],
        ];
    }
}