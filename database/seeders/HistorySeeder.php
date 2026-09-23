<?php

namespace Database\Seeders;

use App\Models\History;
use App\Models\Village;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * CATATAN: Kelurahan Batulo SENGAJA tidak ada di sini — sumber data "00_Sejarah.txt"
     * cuma berisi Baadia, Bataraguru, dan Wale, tidak ada bagian untuk Batulo. Bukan
     * lupa/bug; begitu datanya tersedia, tinggal tambah 1 entri lagi di data() di bawah.
     * Selama belum diisi, halaman /batulo/sejarah akan 404 (perilaku HistoryController
     * yang sudah ada untuk Kelurahan tanpa data History, bukan perubahan baru).
     */
    public function run(): void
    {
        foreach ($this->data() as $villageName => $attributes) {
            $village = Village::where('name', $villageName)->first();

            if (!$village) {
                $this->command?->warn("HistorySeeder: Kelurahan \"{$villageName}\" tidak ditemukan (cek VillageSeeder), dilewati.");
                continue;
            }

            History::create([
                'village_id' => $village->id,
                ...$attributes,
            ]);
        }
    }

    private function data(): array
    {
        return [
            'Baadia' => [
                'penulis' => 'Andina Busrah',
                'konten' => "Nama Baadia berasal dari bahasa Arab yang berarti hutan, yang dalam bahasa Wolio disebut koo. Wilayah ini dibuka dan dirintis oleh Sultan Buton ke-29, Sultan Muhammad Idrus Kaimuddin (Oputa ko Baadiana), yang memerintah pada tahun 1824–1855 M. Kepemimpinan kemudian dilanjutkan oleh putranya, La Ode Muhammad Sai (Oputa I Tanga), sebagai Sultan Buton ke-30.\n\nSecara geografis, Kelurahan Baadia berada di dataran tinggi dan berbatasan langsung dengan kawasan Benteng Keraton Kesultanan Buton serta aliran Sungai Baubau. Di dalamnya terdapat Benteng Baadia, yang menjadi bagian tak terpisahkan dari Benteng Kesultanan Buton—kawasan yang diusulkan menjadi Warisan Dunia UNESCO.\n\nBaadia memiliki hubungan erat dengan sejarah peradaban Kesultanan Buton. Sejumlah situs bersejarah yang masih terjaga hingga kini menjadi bukti kejayaan masa lalu, seperti Benteng Baadia, Museum, Masjid Kuba, dan Istana Kesultanan Buton. Keberadaan situs-situs ini menjadikan Baadia layak disebut sebagai destinasi wisata budaya kedua setelah Kawasan Benteng Keraton Kesultanan Buton.\n\nKehidupan budaya yang kental serta potensi wisata sejarah yang kuat turut mendorong pertumbuhan pengrajin tradisional dan UMKM di Baadia. Perpaduan antara warisan sejarah, keindahan alam, dan aktivitas ekonomi masyarakat menjadikan Kelurahan Baadia salah satu pusat wisata budaya yang memikat di Kota Baubau.",
            ],

            'Bataraguru' => [
                'penulis' => 'Tim Kelurahan Cantik 2026 - Kelurahan Bataraguru',
                'konten' => "Kelurahan Bataraguru adalah salah satu wilayah administratif yang terletak di Kecamatan Wolio, Kota Baubau, Sulawesi Tenggara. Nama \"Bataraguru\" sangat erat kaitannya dengan sejarah masa lalu yang diambil dari gelar raja-raja dan pembesar kerajaan masa lalu di wilayah Buton.\n\n"
                    . "Berikut adalah catatan ringkas mengenai wilayah ini:\n\n"
                    . "Asal Usul Nama: Nama Baubau dan gelar Kelurahan Bataraguru berakar dari kata \"Bau\", yakni sebuah gelar kebangsawanan kerajaan yang sering disematkan pada tokoh penting, bangsawan, atau pembesar di Kesultanan Buton. Nama Bataraguru yang dipilih oleh tokoh pencetus dan pendiri kelurahan ini sangat cocok dan tepat dikarenakan di wilayah inilah terdapat situs sejarah makam raja Bataraguru, raja ke-III yang menurut sejarah berkuasa pada abad ke-XIV.\n\n"
                    . "Bagian dari Kerajaan Kesultanan Buton: Wilayah Bataraguru berada di area jantung pusat kebudayaan yang dulunya dikelola di bawah tatanan adat dan pemerintahan Kesultanan Buton.\n\n"
                    . "Perkembangan administrasi: Seiring berkembangnya status daerah, berdasarkan Undang-Undang Nomor 13 Tahun 2001, Kota Baubau resmi menjadi sebuah kota otonom, dan Kelurahan Bataraguru kemudian diresmikan menjadi salah satu dari puluhan kelurahan di Kecamatan Wolio.\n\n"
                    . "Kondisi saat ini: Kelurahan Bataraguru terus mengalami perkembangan wilayah dan tata kota seiring dengan kemajuan Kota Baubau secara keseluruhan.",
            ],

            'Wale' => [
                'penulis' => 'Tim Kelurahan Cantik 2026 - Kelurahan Wale',
                'konten' => "Kelurahan Wale adalah salah satu wilayah administratif yang terletak di Kecamatan Wolio, Kota Baubau, Sulawesi Tenggara. Nama \"Wale\" konon katanya berkaitan erat dengan istilah wale-wale, yang dalam bahasa Wolio artinya \"Pondok\". \"Wale-wale\" diceritakan sebagai tempat untuk menjemur ikan. Ini bisa dikaitkan dengan istilah \"Bale\" dalam bahasa Bugis yang berarti ikan.\n\n"
                    . "Beberapa cerita mengenai peristiwa sejarah di Kelurahan Wale yaitu:\n\n"
                    . "Tahun 1962, datang orang Bugis yang menimbun laut di daerah Wale, di mana awalnya daerah ini sebagian besar merupakan lautan.\n\n"
                    . "Tahun 1967, terjadi kebakaran besar di Kelurahan Wale, sehingga saat itu diceritakan masyarakat Bugis yang tinggal di kelurahan mengungsi ke Waara dan Wamengkoli. Sejak saat itu, sebagian masyarakat Bugis tersebut ada yang kembali ke Wale, ada juga yang menetap di Waara dan sekitarnya.",
            ],
        ];
    }
}