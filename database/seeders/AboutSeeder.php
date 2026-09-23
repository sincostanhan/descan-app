<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Village;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Visi & Misi Kota Baubau sama untuk semua Kelurahan (bukan duplikasi data,
        // memang satu kota yang sama) — ditaruh sekali di sini, dipakai ulang di loop.
        $visi = 'Baubau Kota Budaya yang Ramah, Cerdas, Sejahtera, dan Bermartabat';
        $misi = "1. Meningkatkan kualitas sumber daya manusia untuk membentuk insan seutuhnya (cerdas, sehat, dan berakhlak)\n"
            . "2. Meningkatkan pertumbuhan ekonomi kota yang inovatif, berkualitas, dan inklusif dan menumbuh-kembangkan perekonomian berbasis potensi daerah, perdagangan, dan jasa\n"
            . "3. Mengembangkan kawasan-kawasan potensial dan infrastruktur kota yang merata dan berkualitas\n"
            . "4. Meningkatkan kualitas tata kelola pemerintahan dan pelayanan yang didukung oleh teknologi informasi yang handal dan aparatur yang berintegritas, profesional, dan bersih\n"
            . "5. Menata dan membentuk lingkungan kota yang nyaman, aman, dan berkelanjutan";

        foreach ($this->data($visi, $misi) as $villageName => $attributes) {
            $village = Village::where('name', $villageName)->first();

            if (!$village) {
                $this->command?->warn("AboutSeeder: Kelurahan \"{$villageName}\" tidak ditemukan (cek VillageSeeder), dilewati.");
                continue;
            }

            About::create([
                'village_id' => $village->id,
                ...$attributes,
            ]);
        }
    }

    private function data(string $visi, string $misi): array
    {
        return [
            'Baadia' => [
                'deskripsi' => 'Baadia adalah Kelurahan yang berada di Kecamatan Murhum, Kota Baubau, Indonesia. Kelurahan Baadia berlokasi di luar Benteng Keraton Buton.',
                'batas_utara' => 'Kelurahan Melai',
                'batas_barat' => 'Kelurahan Lipu',
                'batas_selatan' => 'Kelurahan Waborobo',
                'batas_timur' => 'Kelurahan Bukit Wolio Indah',
                'visi' => $visi,
                'misi' => $misi,
            ],

            'Bataraguru' => [
                'deskripsi' => 'Bataraguru adalah Kelurahan yang berada di Kecamatan Wolio, Kota Baubau, Indonesia.',
                'batas_utara' => 'Kelurahan Tomba',
                'batas_barat' => 'Sungai/Kali Ambon',
                'batas_selatan' => 'Kelurahan Bukit Wolio Indah',
                'batas_timur' => 'Kelurahan Wangkanapi',
                'visi' => $visi,
                'misi' => $misi,
            ],

            'Batulo' => [
                'deskripsi' => 'Batulo adalah Kelurahan yang berada di Kecamatan Wolio, Kota Baubau, Indonesia.',
                'batas_utara' => 'Laut Lepas',
                'batas_barat' => 'Kelurahan Wale dan Kelurahan Wangkanapi',
                'batas_selatan' => 'Kelurahan Bukit Wolio Indah',
                'batas_timur' => 'Kelurahan Kadolomoko dan Kelurahan Kadolo',
                'visi' => $visi,
                'misi' => $misi,
            ],

            'Wale' => [
                'deskripsi' => 'Wale adalah Kelurahan yang berada di Kecamatan Wolio, Kota Baubau, Indonesia.',
                'batas_utara' => 'Laut Lepas',
                'batas_barat' => 'Sungai/Kali Ambon',
                'batas_selatan' => 'Kelurahan Tomba - Wangkanapi',
                'batas_timur' => 'Kelurahan Batulo',
                'visi' => $visi,
                'misi' => $misi,
            ],
        ];
    }
}