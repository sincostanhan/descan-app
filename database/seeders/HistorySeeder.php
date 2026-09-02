<?php

namespace Database\Seeders;

use App\Models\History;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari kelurahan Baadia
        $village = Village::where('name', 'Baadia')->first();

        History::create([
            'village_id' => $village->id,
            'penulis' => 'Andina Busrah',
            'konten' => "Nama Baadia berasal dari bahasa Arab yang berarti hutan, yang dalam bahasa Wolio disebut koo. Wilayah ini dibuka dan dirintis oleh Sultan Buton ke-29, Sultan Muhammad Idrus Kaimuddin (Oputa ko Baadiana), yang memerintah pada tahun 1824–1855 M. Kepemimpinan kemudian dilanjutkan oleh putranya, La Ode Muhammad Sai (Oputa I Tanga), sebagai Sultan Buton ke-30.\n\nSecara geografis, Kelurahan Baadia berada di dataran tinggi dan berbatasan langsung dengan kawasan Benteng Keraton Kesultanan Buton serta aliran Sungai Baubau. Di dalamnya terdapat Benteng Baadia, yang menjadi bagian tak terpisahkan dari Benteng Kesultanan Buton—kawasan yang diusulkan menjadi Warisan Dunia UNESCO.\n\nBaadia memiliki hubungan erat dengan sejarah peradaban Kesultanan Buton. Sejumlah situs bersejarah yang masih terjaga hingga kini menjadi bukti kejayaan masa lalu, seperti Benteng Baadia, Museum, Masjid Kuba, dan Istana Kesultanan Buton. Keberadaan situs-situs ini menjadikan Baadia layak disebut sebagai destinasi wisata budaya kedua setelah Kawasan Benteng Keraton Kesultanan Buton.\n\nKehidupan budaya yang kental serta potensi wisata sejarah yang kuat turut mendorong pertumbuhan pengrajin tradisional dan UMKM di Baadia. Perpaduan antara warisan sejarah, keindahan alam, dan aktivitas ekonomi masyarakat menjadikan Kelurahan Baadia salah satu pusat wisata budaya yang memikat di Kota Baubau."
        ]);
    }
}
