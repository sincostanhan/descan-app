<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari kelurahan Baadia
        $village = Village::where('name', 'Baadia')->first();

        About::create([
            'village_id'    => $village->id,
            'deskripsi'  => 'Baadia adalah Kelurahan yang berada di Kecamatan Murhum, Kota Baubau, Indonesia. Kelurahan Baadia berlokasi di luar Benteng Keraton Buton.',
            'batas_utara'   => 'Kelurahan Melai',
            'batas_barat'   => 'Kelurahan Lipu',
            'batas_selatan' => 'Kelurahan Waborobo',
            'batas_timur'   => 'Kelurahan Bukit Wolio Indah',
            'visi'          => 'Baubau Kota Budaya yang Ramah, Cerdas, Sejahtera, dan Bermartabat',
            'misi'          => "1. Meningkatkan kualitas sumber daya manusia untuk membentuk insan seutuhnya (cerdas, sehat, dan berakhlak)\n2. Meningkatkan pertumbuhan ekonomi kota yang inovatif, berkualitas, dan inklusif dan menumbuh-kembangkan perekonomian berbasis potensi daerah, perdagangan, dan jasa\n3. Mengembangkan kawasan-kawasan potensial dan infrastruktur kota yang merata dan berkualitas\n4. Meningkatkan kualitas tata kelola pemerintahan dan pelayanan yang didukung oleh teknologi informasi yang handal dan aparatur yang berintegritas, profesional, dan bersih\n5. Menata dan membentuk lingkungan kota yang nyaman, aman, dan berkelanjutan"
        ]);
    }
}
