<?php

namespace Database\Seeders;

use App\Actions\CreateStatisticTableEntry;
use App\Models\StatisticTableEntry;
use App\Models\StatisticTemplate;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatisticTablesSeeder extends Seeder
{
    /**
     * Seeder tabel statistik LINTAS KELURAHAN — arsitektur BENAR: 1 StatisticTemplate SHARED
     * dibuat sekali (mode row_source='rt_rw'), lalu tiap Kelurahan generate baris RT/RW dan
     * mengisi StatisticTableEntry-nya SENDIRI di template yang sama. Ini menggantikan
     * BatuloStatisticTablesSeeder sesi sebelumnya, yang keliru bikin 1 template per Kelurahan
     * (template TIDAK BOLEH per-Kelurahan — itu shared milik BPS).
     *
     * Cara menambah tabel baru: tinggal tambah 1 pemanggilan $this->seedSharedRtRwTemplate(...)
     * di run(), tidak perlu ubah apa pun di helper-nya.
     */
    public function run(): void
    {
        // ===== Tabel 2.1 — Luas Wilayah =====
        $this->seedSharedRtRwTemplate(
            'Tabel 2.1 Luas Wilayah',
            null,
            [['label' => 'LUAS WILAYAH (Ha)', 'data_type' => 'numeric']],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [1.65]],
                        ['rt' => 2, 'rw' => 1, 'values' => [0.88]],
                        ['rt' => 3, 'rw' => 1, 'values' => [1.17]],
                        ['rt' => 1, 'rw' => 2, 'values' => [1.39]],
                        ['rt' => 2, 'rw' => 2, 'values' => [1.05]],
                        ['rt' => 3, 'rw' => 2, 'values' => [1.07]],
                        ['rt' => 1, 'rw' => 3, 'values' => [1.19]],
                        ['rt' => 2, 'rw' => 3, 'values' => [2.16]],
                        ['rt' => 3, 'rw' => 3, 'values' => [1.64]],
                        ['rt' => 4, 'rw' => 3, 'values' => [2.28]],
                        ['rt' => 1, 'rw' => 4, 'values' => [0.52]],
                        ['rt' => 2, 'rw' => 4, 'values' => [0.45]],
                        ['rt' => 3, 'rw' => 4, 'values' => [0.26]],
                        ['rt' => 1, 'rw' => 5, 'values' => [0.36]],
                        ['rt' => 2, 'rw' => 5, 'values' => [0.57]],
                        ['rt' => 3, 'rw' => 5, 'values' => [0.59]],
                        ['rt' => 1, 'rw' => 6, 'values' => [1.33]],
                        ['rt' => 2, 'rw' => 6, 'values' => [0.58]],
                        ['rt' => 3, 'rw' => 6, 'values' => [0.95]],
                        ['rt' => 4, 'rw' => 6, 'values' => [0.82]],
                        ['rt' => 1, 'rw' => 7, 'values' => [0.75]],
                        ['rt' => 2, 'rw' => 7, 'values' => [2.89]],
                        ['rt' => 3, 'rw' => 7, 'values' => [1.70]],
                        ['rt' => 4, 'rw' => 7, 'values' => [2.94]],
                        ['rt' => 1, 'rw' => 8, 'values' => [1.00]],
                        ['rt' => 2, 'rw' => 8, 'values' => [2.46]],
                        ['rt' => 3, 'rw' => 8, 'values' => [3.81]],
                        ['rt' => 1, 'rw' => 9, 'values' => [2.29]],
                        ['rt' => 2, 'rw' => 9, 'values' => [4.75]],
                        ['rt' => 3, 'rw' => 9, 'values' => [4.83]],
                    ],
                ],
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [13.03]],
                        ['rt' => 2, 'rw' => 1, 'values' => [4.20]],
                        ['rt' => 3, 'rw' => 1, 'values' => [3.18]],
                        ['rt' => 1, 'rw' => 2, 'values' => [2.72]],
                        ['rt' => 2, 'rw' => 2, 'values' => [2.05]],
                        ['rt' => 3, 'rw' => 2, 'values' => [7.39]],
                    ],
                ],
                'Batulo' => [
                    // 3 nilai asli di file Excel ter-korupsi jadi tanggal (RT 01/RW 04, Total RW 02,
                    // Total RW 05) — nilai bersih diambil dari sheet "Grafik Luas Bataraguru" yang
                    // memuat semua 19 RT tanpa korupsi (sudah divalidasi sesi sebelumnya).
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [6.39]],
                        ['rt' => 2, 'rw' => 1, 'values' => [1.37]],
                        ['rt' => 3, 'rw' => 1, 'values' => [1.65]],
                        ['rt' => 4, 'rw' => 1, 'values' => [1.18]],
                        ['rt' => 1, 'rw' => 2, 'values' => [2.00]],
                        ['rt' => 2, 'rw' => 2, 'values' => [3.70]],
                        ['rt' => 3, 'rw' => 2, 'values' => [3.34]],
                        ['rt' => 1, 'rw' => 3, 'values' => [4.59]],
                        ['rt' => 2, 'rw' => 3, 'values' => [6.92]],
                        ['rt' => 3, 'rw' => 3, 'values' => [1.24]],
                        ['rt' => 1, 'rw' => 4, 'values' => [3.07]],
                        ['rt' => 2, 'rw' => 4, 'values' => [1.38]],
                        ['rt' => 3, 'rw' => 4, 'values' => [1.49]],
                        ['rt' => 1, 'rw' => 5, 'values' => [1.56]],
                        ['rt' => 2, 'rw' => 5, 'values' => [1.13]],
                        ['rt' => 3, 'rw' => 5, 'values' => [7.35]],
                        ['rt' => 1, 'rw' => 6, 'values' => [1.92]],
                        ['rt' => 2, 'rw' => 6, 'values' => [1.94]],
                        ['rt' => 3, 'rw' => 6, 'values' => [1.31]],
                    ],
                ],
            ]
        );
        // Tabel 2.2
        $this->seedSharedRtRwTemplate(
            'Tabel 2.2 Topografi Sebagian Besar Wilayah RT',
            null,
            [
                ['label' => 'Topografi Sebagian Besar Wilayah', 'data_type' => 'text'],
                ['label' => 'Keberadaan Permukiman Penduduk Jika Berisi Puncak/Tebing/Lereng', 'data_type' => 'text'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Dataran', null]],
                        ['rt' => 4, 'rw' => 3, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Dataran', null]],
                        ['rt' => 4, 'rw' => 6, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 7, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 7, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 7, 'values' => ['Dataran', null]],
                        ['rt' => 4, 'rw' => 7, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 8, 'values' => ['Puncak/Tebing', 'Ada']],
                        ['rt' => 2, 'rw' => 8, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 8, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 9, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 9, 'values' => ['Lereng', 'Ada']],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Lereng', 'Ada']],
                    ],
                ],
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Dataran', null]],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Dataran', null]],
                        ['rt' => 4, 'rw' => 1, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Dataran', null]],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Dataran', null]],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Dataran', null]],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Dataran', null]],
                    ],
                ],
            ]
        );

        $this->seedSharedRtRwTemplate(
            'Tabel 2.3 Lokasi Wilayah RT Terhadap Kawasan Hutan',
            null,
            [
                ['label' => 'Lokasi Wilayah Terhadap Kawasan Hutan/Hutan', 'data_type' => 'text'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 4, 'rw' => 3, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 4, 'rw' => 6, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 1, 'rw' => 7, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 7, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 7, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 4, 'rw' => 7, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 1, 'rw' => 8, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 8, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 8, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 1, 'rw' => 9, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 9, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Di tepi/sekitar Kawasan hutan']],
                    ],
                ],
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Di luar kawasan hutan']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Di luar kawasan hutan']],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 4, 'rw' => 1, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Diluar Kawasan Hutan']],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Diluar Kawasan Hutan']],
                    ],
                ],
            ]
        );

        // Tabel 2.4
        $this->seedSharedRtRwTemplate(
            'Tabel 2.4 Keberadaan Satwa/Tumbuhan Yang Dilindungi',
            null,
            [
                ['label' => 'Keberadaan Satwa/Tumbuhan Yang Dilindungi', 'data_type' => 'text'],
            ],
            [
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada']],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 4, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Tidak ada']],
                    ],
                ],
            ]
        );
        $this->seedSharedRtRwTemplate(
            'Tabel 2.4 Status Kawasan Hutan/Hutan',
            null,
            [
                ['label' => 'Status Kawasan Hutan/Hutan', 'data_type' => 'text'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [null]],
                        ['rt' => 2, 'rw' => 1, 'values' => [null]],
                        ['rt' => 3, 'rw' => 1, 'values' => [null]],
                        ['rt' => 1, 'rw' => 2, 'values' => [null]],
                        ['rt' => 2, 'rw' => 2, 'values' => [null]],
                        ['rt' => 3, 'rw' => 2, 'values' => [null]],
                        ['rt' => 1, 'rw' => 3, 'values' => [null]],
                        ['rt' => 2, 'rw' => 3, 'values' => [null]],
                        ['rt' => 3, 'rw' => 3, 'values' => [null]],
                        ['rt' => 4, 'rw' => 3, 'values' => [null]],
                        ['rt' => 1, 'rw' => 4, 'values' => [null]],
                        ['rt' => 2, 'rw' => 4, 'values' => [null]],
                        ['rt' => 3, 'rw' => 4, 'values' => [null]],
                        ['rt' => 1, 'rw' => 5, 'values' => [null]],
                        ['rt' => 2, 'rw' => 5, 'values' => [null]],
                        ['rt' => 3, 'rw' => 5, 'values' => [null]],
                        ['rt' => 1, 'rw' => 6, 'values' => [null]],
                        ['rt' => 2, 'rw' => 6, 'values' => [null]],
                        ['rt' => 3, 'rw' => 6, 'values' => [null]],
                        ['rt' => 4, 'rw' => 6, 'values' => [null]],
                        ['rt' => 1, 'rw' => 7, 'values' => [null]],
                        ['rt' => 2, 'rw' => 7, 'values' => [null]],
                        ['rt' => 3, 'rw' => 7, 'values' => [null]],
                        ['rt' => 4, 'rw' => 7, 'values' => [null]],
                        ['rt' => 1, 'rw' => 8, 'values' => [null]],
                        ['rt' => 2, 'rw' => 8, 'values' => [null]],
                        ['rt' => 3, 'rw' => 8, 'values' => [null]],
                        ['rt' => 1, 'rw' => 9, 'values' => [null]],
                        ['rt' => 2, 'rw' => 9, 'values' => [null]],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Hutan hak']],
                    ],
                ],
            ]
        );

        // Tabel 2.5
        $this->seedSharedRtRwTemplate(
            'Tabel 2.5 Lokasi Wilayah RT Yang Berbatasan Langsung Dengan Laut',
            null,
            [
                ['label' => 'Wilayah Berbatasan Dengan Laut', 'data_type' => 'text'],
                ['label' => 'Pemanfaatan Laut: Perikanan Tangkap', 'data_type' => 'text'],
                ['label' => 'Pemanfaatan Laut: Perikanan Budidaya', 'data_type' => 'text'],
                ['label' => 'Pemanfaatan Laut: Tambak Garam', 'data_type' => 'text'],
                ['label' => 'Pemanfaatan Laut: Wisata Bahari', 'data_type' => 'text'],
                ['label' => 'Pemanfaatan Laut: Transportasi Umum', 'data_type' => 'text'],
                ['label' => 'Keberadaan Tanaman Mangrove', 'data_type' => 'text'],
            ],
            [
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Ada', 'Tidak ada']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Tidak ada']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Ada', 'Ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Ada', 'Tidak ada']],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Ada', 'Tidak ada']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Ada', 'Tidak ada']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Ada', 'Ada', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Ada', 'Tidak ada']],
                        ['rt' => 4, 'rw' => 1, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Ada', 'Ada', 'Ada', 'Tidak ada', 'Tidak ada', 'Ada', 'Tidak ada']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Ada', 'Ada', 'Ada', 'Tidak ada', 'Tidak ada', 'Ada', 'Tidak ada']],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Ada', 'Ada', 'Ada', 'Tidak ada', 'Tidak ada', 'Ada', 'Tidak ada']],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                    ],
                ],
            ]
        );
        $this->seedSharedRtRwTemplate(
            'Tabel 2.5 Fungsi Kawasan Hutan/Hutan',
            null,
            [
                ['label' => 'Fungsi Kawasan Hutan/Hutan', 'data_type' => 'text'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [null]],
                        ['rt' => 2, 'rw' => 1, 'values' => [null]],
                        ['rt' => 3, 'rw' => 1, 'values' => [null]],
                        ['rt' => 1, 'rw' => 2, 'values' => [null]],
                        ['rt' => 2, 'rw' => 2, 'values' => [null]],
                        ['rt' => 3, 'rw' => 2, 'values' => [null]],
                        ['rt' => 1, 'rw' => 3, 'values' => [null]],
                        ['rt' => 2, 'rw' => 3, 'values' => [null]],
                        ['rt' => 3, 'rw' => 3, 'values' => [null]],
                        ['rt' => 4, 'rw' => 3, 'values' => [null]],
                        ['rt' => 1, 'rw' => 4, 'values' => [null]],
                        ['rt' => 2, 'rw' => 4, 'values' => [null]],
                        ['rt' => 3, 'rw' => 4, 'values' => [null]],
                        ['rt' => 1, 'rw' => 5, 'values' => [null]],
                        ['rt' => 2, 'rw' => 5, 'values' => [null]],
                        ['rt' => 3, 'rw' => 5, 'values' => [null]],
                        ['rt' => 1, 'rw' => 6, 'values' => [null]],
                        ['rt' => 2, 'rw' => 6, 'values' => [null]],
                        ['rt' => 3, 'rw' => 6, 'values' => [null]],
                        ['rt' => 4, 'rw' => 6, 'values' => [null]],
                        ['rt' => 1, 'rw' => 7, 'values' => [null]],
                        ['rt' => 2, 'rw' => 7, 'values' => [null]],
                        ['rt' => 3, 'rw' => 7, 'values' => [null]],
                        ['rt' => 4, 'rw' => 7, 'values' => [null]],
                        ['rt' => 1, 'rw' => 8, 'values' => [null]],
                        ['rt' => 2, 'rw' => 8, 'values' => [null]],
                        ['rt' => 3, 'rw' => 8, 'values' => [null]],
                        ['rt' => 1, 'rw' => 9, 'values' => [null]],
                        ['rt' => 2, 'rw' => 9, 'values' => [null]],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Lindung dan produksi']],
                    ],
                ],
            ]
        );

        // Tabel 2.6
        $this->seedSharedRtRwTemplate(
            'Tabel 2.6 Ketergantungan Penduduk Terhadap Kawasan Hutan/Hutan',
            null,
            [
                ['label' => 'Ketergantungan Penduduk Terhadap Kawasan Hutan/Hutan', 'data_type' => 'text'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [null]],
                        ['rt' => 2, 'rw' => 1, 'values' => [null]],
                        ['rt' => 3, 'rw' => 1, 'values' => [null]],
                        ['rt' => 1, 'rw' => 2, 'values' => [null]],
                        ['rt' => 2, 'rw' => 2, 'values' => [null]],
                        ['rt' => 3, 'rw' => 2, 'values' => [null]],
                        ['rt' => 1, 'rw' => 3, 'values' => [null]],
                        ['rt' => 2, 'rw' => 3, 'values' => [null]],
                        ['rt' => 3, 'rw' => 3, 'values' => [null]],
                        ['rt' => 4, 'rw' => 3, 'values' => [null]],
                        ['rt' => 1, 'rw' => 4, 'values' => [null]],
                        ['rt' => 2, 'rw' => 4, 'values' => [null]],
                        ['rt' => 3, 'rw' => 4, 'values' => [null]],
                        ['rt' => 1, 'rw' => 5, 'values' => [null]],
                        ['rt' => 2, 'rw' => 5, 'values' => [null]],
                        ['rt' => 3, 'rw' => 5, 'values' => [null]],
                        ['rt' => 1, 'rw' => 6, 'values' => [null]],
                        ['rt' => 2, 'rw' => 6, 'values' => [null]],
                        ['rt' => 3, 'rw' => 6, 'values' => [null]],
                        ['rt' => 4, 'rw' => 6, 'values' => [null]],
                        ['rt' => 1, 'rw' => 7, 'values' => [null]],
                        ['rt' => 2, 'rw' => 7, 'values' => [null]],
                        ['rt' => 3, 'rw' => 7, 'values' => [null]],
                        ['rt' => 4, 'rw' => 7, 'values' => [null]],
                        ['rt' => 1, 'rw' => 8, 'values' => [null]],
                        ['rt' => 2, 'rw' => 8, 'values' => [null]],
                        ['rt' => 3, 'rw' => 8, 'values' => [null]],
                        ['rt' => 1, 'rw' => 9, 'values' => [null]],
                        ['rt' => 2, 'rw' => 9, 'values' => [null]],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Sedang']],
                    ],
                ],
            ]
        );

        // Tabel 2.7
        $this->seedSharedRtRwTemplate(
            'Tabel 2.7 Program Perhutanan Sosial',
            null,
            [
                ['label' => 'Program Perhutanan Sosial', 'data_type' => 'text'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [null]],
                        ['rt' => 2, 'rw' => 1, 'values' => [null]],
                        ['rt' => 3, 'rw' => 1, 'values' => [null]],
                        ['rt' => 1, 'rw' => 2, 'values' => [null]],
                        ['rt' => 2, 'rw' => 2, 'values' => [null]],
                        ['rt' => 3, 'rw' => 2, 'values' => [null]],
                        ['rt' => 1, 'rw' => 3, 'values' => [null]],
                        ['rt' => 2, 'rw' => 3, 'values' => [null]],
                        ['rt' => 3, 'rw' => 3, 'values' => [null]],
                        ['rt' => 4, 'rw' => 3, 'values' => [null]],
                        ['rt' => 1, 'rw' => 4, 'values' => [null]],
                        ['rt' => 2, 'rw' => 4, 'values' => [null]],
                        ['rt' => 3, 'rw' => 4, 'values' => [null]],
                        ['rt' => 1, 'rw' => 5, 'values' => [null]],
                        ['rt' => 2, 'rw' => 5, 'values' => [null]],
                        ['rt' => 3, 'rw' => 5, 'values' => [null]],
                        ['rt' => 1, 'rw' => 6, 'values' => [null]],
                        ['rt' => 2, 'rw' => 6, 'values' => [null]],
                        ['rt' => 3, 'rw' => 6, 'values' => [null]],
                        ['rt' => 4, 'rw' => 6, 'values' => [null]],
                        ['rt' => 1, 'rw' => 7, 'values' => [null]],
                        ['rt' => 2, 'rw' => 7, 'values' => [null]],
                        ['rt' => 3, 'rw' => 7, 'values' => [null]],
                        ['rt' => 4, 'rw' => 7, 'values' => [null]],
                        ['rt' => 1, 'rw' => 8, 'values' => [null]],
                        ['rt' => 2, 'rw' => 8, 'values' => [null]],
                        ['rt' => 3, 'rw' => 8, 'values' => [null]],
                        ['rt' => 1, 'rw' => 9, 'values' => [null]],
                        ['rt' => 2, 'rw' => 9, 'values' => [null]],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Tidak Ada']],
                    ],
                ],
            ]
        );

        // Tabel 2.8
        $this->seedSharedRtRwTemplate(
            'Tabel 2.8 Keberadaan Satwa/Tumbuhan Yang Dilindungi',
            null,
            [
                ['label' => 'Keberadaan Satwa/Tumbuhan Yang Dilindungi', 'data_type' => 'text'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 4, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 4, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 7, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 7, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 7, 'values' => ['Tidak ada']],
                        ['rt' => 4, 'rw' => 7, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 8, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 8, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 8, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 9, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 9, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Tidak ada']],
                    ],
                ],
            ]
        );

        // Tabel 2.9
        $this->seedSharedRtRwTemplate(
            'Tabel 2.9 Lokasi Wilayah RT Yang Berbatasan Langsung Dengan Laut',
            null,
            [
                ['label' => 'Wilayah Berbatasan Dengan Laut', 'data_type' => 'text'],
                ['label' => 'Pemanfaatan Laut: Perikanan Tangkap', 'data_type' => 'text'],
                ['label' => 'Pemanfaatan Laut: Perikanan Budidaya', 'data_type' => 'text'],
                ['label' => 'Pemanfaatan Laut: Tambak Garam', 'data_type' => 'text'],
                ['label' => 'Pemanfaatan Laut: Wisata Bahari', 'data_type' => 'text'],
                ['label' => 'Pemanfaatan Laut: Transportasi Umum', 'data_type' => 'text'],
                ['label' => 'Keberadaan Tanaman Mangrove', 'data_type' => 'text'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 4, 'rw' => 3, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 4, 'rw' => 6, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 7, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 7, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 7, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 4, 'rw' => 7, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 8, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 8, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 8, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 1, 'rw' => 9, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 2, 'rw' => 9, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Tidak ada', null, null, null, null, null, null]],
                    ],
                ],
            ]
        );

        // Tabel 3.1
        $this->seedSharedRtRwTemplate(
            'Tabel 3.1 Jumlah Penduduk pada 1 Juni 2026',
            null,
            [
                ['label' => 'Jumlah Penduduk: Laki-Laki', 'data_type' => 'numeric', 'unit' => 'Jiwa'],
                ['label' => 'Jumlah Penduduk: Perempuan', 'data_type' => 'numeric', 'unit' => 'Jiwa'],
                ['label' => 'Jumlah Penduduk: Laki-Laki + Perempuan', 'data_type' => 'numeric', 'unit' => 'Jiwa'],
                ['label' => 'Persentase Penduduk', 'data_type' => 'numeric', 'unit' => '%'],
                ['label' => 'Luas Wilayah', 'data_type' => 'numeric', 'unit' => 'Ha'],
                ['label' => 'Kepadatan Penduduk', 'data_type' => 'numeric', 'unit' => 'Jiwa/Ha'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [92, 90, 182, 2.9, 1.65, 110.3]],
                        ['rt' => 2, 'rw' => 1, 'values' => [56, 70, 126, 2.01, 0.88, 143.18]],
                        ['rt' => 3, 'rw' => 1, 'values' => [101, 127, 228, 3.63, 1.17, 194.87]],
                        ['rt' => 1, 'rw' => 2, 'values' => [190, 185, 375, 5.97, 1.39, 269.78]],
                        ['rt' => 2, 'rw' => 2, 'values' => [50, 47, 97, 1.54, 1.05, 92.38]],
                        ['rt' => 3, 'rw' => 2, 'values' => [57, 64, 121, 1.93, 1.07, 113.08]],
                        ['rt' => 1, 'rw' => 3, 'values' => [121, 109, 230, 3.66, 1.19, 193.28]],
                        ['rt' => 2, 'rw' => 3, 'values' => [122, 88, 210, 3.34, 2.16, 97.22]],
                        ['rt' => 3, 'rw' => 3, 'values' => [75, 86, 161, 2.56, 1.64, 98.17]],
                        ['rt' => 4, 'rw' => 3, 'values' => [52, 57, 109, 1.73, 2.28, 47.81]],
                        ['rt' => 1, 'rw' => 4, 'values' => [56, 59, 115, 1.83, 0.52, 221.15]],
                        ['rt' => 2, 'rw' => 4, 'values' => [93, 102, 195, 3.1, 0.45, 433.33]],
                        ['rt' => 3, 'rw' => 4, 'values' => [61, 53, 114, 1.81, 0.26, 438.46]],
                        ['rt' => 1, 'rw' => 5, 'values' => [109, 110, 219, 3.49, 0.36, 608.33]],
                        ['rt' => 2, 'rw' => 5, 'values' => [96, 62, 158, 2.51, 0.57, 277.19]],
                        ['rt' => 3, 'rw' => 5, 'values' => [69, 69, 138, 2.2, 0.59, 233.9]],
                        ['rt' => 1, 'rw' => 6, 'values' => [88, 105, 193, 3.07, 1.33, 145.11]],
                        ['rt' => 2, 'rw' => 6, 'values' => [197, 102, 299, 4.76, 0.58, 515.52]],
                        ['rt' => 3, 'rw' => 6, 'values' => [100, 105, 205, 3.26, 0.95, 215.79]],
                        ['rt' => 4, 'rw' => 6, 'values' => [114, 100, 214, 3.41, 0.82, 260.98]],
                        ['rt' => 1, 'rw' => 7, 'values' => [127, 113, 240, 3.82, 0.75, 320.0]],
                        ['rt' => 2, 'rw' => 7, 'values' => [34, 30, 64, 1.02, 2.89, 22.15]],
                        ['rt' => 3, 'rw' => 7, 'values' => [91, 39, 130, 2.07, 1.7, 76.47]],
                        ['rt' => 4, 'rw' => 7, 'values' => [195, 184, 379, 6.03, 2.94, 128.91]],
                        ['rt' => 1, 'rw' => 8, 'values' => [115, 128, 243, 3.87, 1.0, 243.0]],
                        ['rt' => 2, 'rw' => 8, 'values' => [200, 184, 384, 6.11, 2.46, 156.1]],
                        ['rt' => 3, 'rw' => 8, 'values' => [170, 188, 358, 5.7, 3.81, 93.96]],
                        ['rt' => 1, 'rw' => 9, 'values' => [107, 92, 199, 3.17, 2.29, 86.9]],
                        ['rt' => 2, 'rw' => 9, 'values' => [116, 150, 266, 4.23, 4.75, 56.0]],
                        ['rt' => 3, 'rw' => 9, 'values' => [162, 169, 331, 5.27, 4.83, 68.53]],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [70, 58, 128, 3.6, 6.39, 20.03]],
                        ['rt' => 2, 'rw' => 1, 'values' => [84, 97, 181, 5.1, 1.37, 132.12]],
                        ['rt' => 3, 'rw' => 1, 'values' => [89, 95, 184, 5.18, 1.65, 111.52]],
                        ['rt' => 4, 'rw' => 1, 'values' => [90, 104, 194, 5.46, 1.18, 164.41]],
                        ['rt' => 1, 'rw' => 2, 'values' => [124, 131, 255, 7.18, 2.0, 127.5]],
                        ['rt' => 2, 'rw' => 2, 'values' => [112, 112, 224, 6.31, 3.7, 60.54]],
                        ['rt' => 3, 'rw' => 2, 'values' => [119, 136, 255, 7.18, 3.34, 76.35]],
                        ['rt' => 1, 'rw' => 3, 'values' => [111, 88, 199, 5.6, 4.59, 43.36]],
                        ['rt' => 2, 'rw' => 3, 'values' => [102, 109, 211, 5.94, 6.92, 30.49]],
                        ['rt' => 3, 'rw' => 3, 'values' => [73, 77, 150, 4.22, 1.24, 120.97]],
                        ['rt' => 1, 'rw' => 4, 'values' => [39, 53, 92, 2.59, 3.07, 29.97]],
                        ['rt' => 2, 'rw' => 4, 'values' => [84, 125, 209, 5.89, 1.38, 151.45]],
                        ['rt' => 3, 'rw' => 4, 'values' => [51, 54, 105, 2.96, 1.49, 70.47]],
                        ['rt' => 1, 'rw' => 5, 'values' => [103, 89, 192, 5.41, 1.56, 123.08]],
                        ['rt' => 2, 'rw' => 5, 'values' => [80, 95, 175, 4.93, 1.13, 154.87]],
                        ['rt' => 3, 'rw' => 5, 'values' => [81, 75, 156, 4.39, 7.35, 21.22]],
                        ['rt' => 1, 'rw' => 6, 'values' => [80, 89, 169, 4.76, 1.92, 88.02]],
                        ['rt' => 2, 'rw' => 6, 'values' => [127, 152, 279, 7.86, 1.94, 143.81]],
                        ['rt' => 3, 'rw' => 6, 'values' => [89, 104, 193, 5.44, 1.31, 147.33]],
                    ],
                ],
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [66, 71, 137, 9.26, 13.03, 10.51]],
                        ['rt' => 2, 'rw' => 1, 'values' => [130, 137, 267, 18.05, 4.2, 63.57]],
                        ['rt' => 3, 'rw' => 1, 'values' => [160, 176, 336, 22.72, 3.18, 105.66]],
                        ['rt' => 1, 'rw' => 2, 'values' => [101, 100, 201, 13.59, 2.72, 73.9]],
                        ['rt' => 2, 'rw' => 2, 'values' => [130, 141, 271, 18.32, 2.05, 132.2]],
                        ['rt' => 3, 'rw' => 2, 'values' => [125, 142, 267, 18.05, 7.39, 36.13]],
                    ],
                ],
            ]
        );

        // Tabel 3.2
        $this->seedSharedRtRwTemplate(
            'Tabel 3.2 Jumlah Keluarga dan Jumlah Keluarga Pertanian pada 1 Juni 2026',
            null,
            [
                ['label' => 'Jumlah Keluarga', 'data_type' => 'numeric', 'unit' => 'KK'],
                ['label' => 'Jumlah Keluarga Pertanian', 'data_type' => 'numeric', 'unit' => 'KK'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [50, 0]],
                        ['rt' => 2, 'rw' => 1, 'values' => [34, 0]],
                        ['rt' => 3, 'rw' => 1, 'values' => [70, 0]],
                        ['rt' => 1, 'rw' => 2, 'values' => [107, 0]],
                        ['rt' => 2, 'rw' => 2, 'values' => [26, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => [32, 0]],
                        ['rt' => 1, 'rw' => 3, 'values' => [64, 0]],
                        ['rt' => 2, 'rw' => 3, 'values' => [48, 1]],
                        ['rt' => 3, 'rw' => 3, 'values' => [38, 0]],
                        ['rt' => 4, 'rw' => 3, 'values' => [30, 2]],
                        ['rt' => 1, 'rw' => 4, 'values' => [34, 0]],
                        ['rt' => 2, 'rw' => 4, 'values' => [52, 0]],
                        ['rt' => 3, 'rw' => 4, 'values' => [27, 0]],
                        ['rt' => 1, 'rw' => 5, 'values' => [58, 0]],
                        ['rt' => 2, 'rw' => 5, 'values' => [36, 0]],
                        ['rt' => 3, 'rw' => 5, 'values' => [41, 0]],
                        ['rt' => 1, 'rw' => 6, 'values' => [54, 1]],
                        ['rt' => 2, 'rw' => 6, 'values' => [57, 2]],
                        ['rt' => 3, 'rw' => 6, 'values' => [51, 0]],
                        ['rt' => 4, 'rw' => 6, 'values' => [59, 0]],
                        ['rt' => 1, 'rw' => 7, 'values' => [73, 0]],
                        ['rt' => 2, 'rw' => 7, 'values' => [25, 0]],
                        ['rt' => 3, 'rw' => 7, 'values' => [75, 0]],
                        ['rt' => 4, 'rw' => 7, 'values' => [108, 0]],
                        ['rt' => 1, 'rw' => 8, 'values' => [61, 3]],
                        ['rt' => 2, 'rw' => 8, 'values' => [101, 4]],
                        ['rt' => 3, 'rw' => 8, 'values' => [91, 4]],
                        ['rt' => 1, 'rw' => 9, 'values' => [44, 0]],
                        ['rt' => 2, 'rw' => 9, 'values' => [68, 0]],
                        ['rt' => 3, 'rw' => 9, 'values' => [78, 10]],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [38, 0]],
                        ['rt' => 2, 'rw' => 1, 'values' => [56, 4]],
                        ['rt' => 3, 'rw' => 1, 'values' => [57, 2]],
                        ['rt' => 4, 'rw' => 1, 'values' => [64, 0]],
                        ['rt' => 1, 'rw' => 2, 'values' => [77, 0]],
                        ['rt' => 2, 'rw' => 2, 'values' => [73, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => [80, 0]],
                        ['rt' => 1, 'rw' => 3, 'values' => [62, 0]],
                        ['rt' => 2, 'rw' => 3, 'values' => [59, 5]],
                        ['rt' => 3, 'rw' => 3, 'values' => [49, 1]],
                        ['rt' => 1, 'rw' => 4, 'values' => [32, 0]],
                        ['rt' => 2, 'rw' => 4, 'values' => [62, 0]],
                        ['rt' => 3, 'rw' => 4, 'values' => [37, 0]],
                        ['rt' => 1, 'rw' => 5, 'values' => [62, 1]],
                        ['rt' => 2, 'rw' => 5, 'values' => [52, 0]],
                        ['rt' => 3, 'rw' => 5, 'values' => [47, 0]],
                        ['rt' => 1, 'rw' => 6, 'values' => [53, 0]],
                        ['rt' => 2, 'rw' => 6, 'values' => [74, 0]],
                        ['rt' => 3, 'rw' => 6, 'values' => [55, 0]],
                    ],
                ],
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [53, 0]],
                        ['rt' => 2, 'rw' => 1, 'values' => [79, 0]],
                        ['rt' => 3, 'rw' => 1, 'values' => [96, 0]],
                        ['rt' => 1, 'rw' => 2, 'values' => [58, 0]],
                        ['rt' => 2, 'rw' => 2, 'values' => [78, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => [68, 3]],
                    ],
                ],
            ]
        );

        // Tabel 3.3
        $this->seedSharedRtRwTemplate(
            'Tabel 3.3 Jumlah Penduduk Sebatang Kara dan Keluarga Menganggur pada 1 Juni 2026',
            null,
            [
                ['label' => 'Jumlah Penduduk yang Tinggal Sebatang Kara', 'data_type' => 'numeric', 'unit' => 'Jiwa'],
                ['label' => 'Jumlah Keluarga yang Seluruh Anggotanya Tidak Bekerja / Menganggur', 'data_type' => 'numeric', 'unit' => 'KK'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 1, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 1, 'values' => [0, 0]],
                        ['rt' => 1, 'rw' => 2, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 2, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => [0, 0]],
                        ['rt' => 1, 'rw' => 3, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 3, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 3, 'values' => [0, 0]],
                        ['rt' => 4, 'rw' => 3, 'values' => [0, 0]],
                        ['rt' => 1, 'rw' => 4, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 4, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 4, 'values' => [0, 0]],
                        ['rt' => 1, 'rw' => 5, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 5, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 5, 'values' => [0, 0]],
                        ['rt' => 1, 'rw' => 6, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 6, 'values' => [1, 0]],
                        ['rt' => 3, 'rw' => 6, 'values' => [0, 1]],
                        ['rt' => 4, 'rw' => 6, 'values' => [0, 0]],
                        ['rt' => 1, 'rw' => 7, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 7, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 7, 'values' => [0, 0]],
                        ['rt' => 4, 'rw' => 7, 'values' => [0, 0]],
                        ['rt' => 1, 'rw' => 8, 'values' => [1, 1]],
                        ['rt' => 2, 'rw' => 8, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 8, 'values' => [1, 0]],
                        ['rt' => 1, 'rw' => 9, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 9, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 9, 'values' => [0, 0]],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [1, 1]],
                        ['rt' => 2, 'rw' => 1, 'values' => [1, 1]],
                        ['rt' => 3, 'rw' => 1, 'values' => [1, 1]],
                        ['rt' => 4, 'rw' => 1, 'values' => [0, 0]],
                        ['rt' => 1, 'rw' => 2, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 2, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => [0, 0]],
                        ['rt' => 1, 'rw' => 3, 'values' => [10, 0]],
                        ['rt' => 2, 'rw' => 3, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 3, 'values' => [1, 1]],
                        ['rt' => 1, 'rw' => 4, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 4, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 4, 'values' => [1, 0]],
                        ['rt' => 1, 'rw' => 5, 'values' => [1, 0]],
                        ['rt' => 2, 'rw' => 5, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 5, 'values' => [1, 0]],
                        ['rt' => 1, 'rw' => 6, 'values' => [0, 0]],
                        ['rt' => 2, 'rw' => 6, 'values' => [0, 0]],
                        ['rt' => 3, 'rw' => 6, 'values' => [0, 0]],
                    ],
                ],
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [8, 10]],
                        ['rt' => 2, 'rw' => 1, 'values' => [2, 2]],
                        ['rt' => 3, 'rw' => 1, 'values' => [2, 2]],
                        ['rt' => 1, 'rw' => 2, 'values' => [0, 4]],
                        ['rt' => 2, 'rw' => 2, 'values' => [2, 4]],
                        ['rt' => 3, 'rw' => 2, 'values' => [4, 2]],
                    ],
                ],
            ]
        );

        // Tabel 3.4
        $this->seedSharedRtRwTemplate(
            'Tabel 3.4 Kependudukan Terkait DTSEN pada 1 Juni 2026',
            null,
            [
                ['label' => 'Jumlah keluarga yang mengalami perubahan desil sehingga tidak menerima lagi bantuan sosial', 'data_type' => 'numeric'],
                ['label' => 'Jumlah keluarga yang seharusnya menerima bantuan sosial namun tidak menerima / exclusion error', 'data_type' => 'numeric'],
                ['label' => 'Jumlah keluarga yang seharusnya tidak menerima bantuan sosial namun menerima / inclusion error', 'data_type' => 'numeric'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [4, 3, 0]],
                        ['rt' => 2, 'rw' => 1, 'values' => [4, 4, 0]],
                        ['rt' => 3, 'rw' => 1, 'values' => [2, 4, 0]],
                        ['rt' => 1, 'rw' => 2, 'values' => [6, 5, 0]],
                        ['rt' => 2, 'rw' => 2, 'values' => [3, 5, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => [0, 0, 0]],
                        ['rt' => 1, 'rw' => 3, 'values' => [1, 2, 1]],
                        ['rt' => 2, 'rw' => 3, 'values' => [5, 3, 0]],
                        ['rt' => 3, 'rw' => 3, 'values' => [2, 2, 0]],
                        ['rt' => 4, 'rw' => 3, 'values' => [1, 1, 0]],
                        ['rt' => 1, 'rw' => 4, 'values' => [3, 2, 0]],
                        ['rt' => 2, 'rw' => 4, 'values' => [4, 8, 0]],
                        ['rt' => 3, 'rw' => 4, 'values' => [0, 0, 0]],
                        ['rt' => 1, 'rw' => 5, 'values' => [2, 15, 0]],
                        ['rt' => 2, 'rw' => 5, 'values' => [3, 1, 0]],
                        ['rt' => 3, 'rw' => 5, 'values' => [4, 5, 1]],
                        ['rt' => 1, 'rw' => 6, 'values' => [6, 7, 0]],
                        ['rt' => 2, 'rw' => 6, 'values' => [2, 10, 0]],
                        ['rt' => 3, 'rw' => 6, 'values' => [3, 0, 0]],
                        ['rt' => 4, 'rw' => 6, 'values' => [1, 15, 2]],
                        ['rt' => 1, 'rw' => 7, 'values' => [3, 8, 0]],
                        ['rt' => 2, 'rw' => 7, 'values' => [2, 9, 0]],
                        ['rt' => 3, 'rw' => 7, 'values' => [0, 10, 0]],
                        ['rt' => 4, 'rw' => 7, 'values' => [4, 10, 0]],
                        ['rt' => 1, 'rw' => 8, 'values' => [3, 3, 1]],
                        ['rt' => 2, 'rw' => 8, 'values' => [5, 6, 0]],
                        ['rt' => 3, 'rw' => 8, 'values' => [5, 4, 0]],
                        ['rt' => 1, 'rw' => 9, 'values' => [3, 2, 0]],
                        ['rt' => 2, 'rw' => 9, 'values' => [5, 25, 0]],
                        ['rt' => 3, 'rw' => 9, 'values' => [2, 15, 4]],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [1, 1, 0]],
                        ['rt' => 2, 'rw' => 1, 'values' => [2, 2, 0]],
                        ['rt' => 3, 'rw' => 1, 'values' => [5, 3, 0]],
                        ['rt' => 4, 'rw' => 1, 'values' => [5, 5, 5]],
                        ['rt' => 1, 'rw' => 2, 'values' => [0, 8, 0]],
                        ['rt' => 2, 'rw' => 2, 'values' => [0, 5, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => [8, 17, 0]],
                        ['rt' => 1, 'rw' => 3, 'values' => [5, 5, 0]],
                        ['rt' => 2, 'rw' => 3, 'values' => [6, 1, 1]],
                        ['rt' => 3, 'rw' => 3, 'values' => [2, 1, 0]],
                        ['rt' => 1, 'rw' => 4, 'values' => [0, 3, 0]],
                        ['rt' => 2, 'rw' => 4, 'values' => [2, 8, 0]],
                        ['rt' => 3, 'rw' => 4, 'values' => [0, 2, 0]],
                        ['rt' => 1, 'rw' => 5, 'values' => [0, 0, 0]],
                        ['rt' => 2, 'rw' => 5, 'values' => [0, 0, 2]],
                        ['rt' => 3, 'rw' => 5, 'values' => [1, 3, 1]],
                        ['rt' => 1, 'rw' => 6, 'values' => [0, 2, 0]],
                        ['rt' => 2, 'rw' => 6, 'values' => [2, 5, 0]],
                        ['rt' => 3, 'rw' => 6, 'values' => [2, 3, 1]],
                    ],
                ],
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => [0, 0, 0]],
                        ['rt' => 2, 'rw' => 1, 'values' => [0, 0, 0]],
                        ['rt' => 3, 'rw' => 1, 'values' => [0, 0, 0]],
                        ['rt' => 1, 'rw' => 2, 'values' => [0, 4, 4]],
                        ['rt' => 2, 'rw' => 2, 'values' => [0, 1, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => [2, 12, 0]],
                    ],
                ],
            ]
        );

        // Tabel 3.5
        $this->seedSharedRtRwTemplate(
            'Tabel 3.5 Keberadaan warga RT yang sedang bekerja sebagai Pekerja Migran Indonesia/TKI di luar negeri pada 1 Juni 2026',
            null,
            [
                ['label' => 'Keberadaan TKI', 'data_type' => 'text'],
                ['label' => 'Jumlah Laki-Laki TKI', 'data_type' => 'numeric'],
                ['label' => 'Jumlah Perempuan TKI', 'data_type' => 'numeric'],
            ],
            [
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 4, 'rw' => 3, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 4, 'rw' => 6, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 7, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 7, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 7, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 4, 'rw' => 7, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 8, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 8, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 8, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 9, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 9, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Tidak ada', 0, 0]],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 4, 'rw' => 1, 'values' => ['Ada', 1, 1]],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Tidak ada', 0, 0]],
                    ],
                ],
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Ada', 1, 0]],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Ada', 0, 1]],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada', 0, 0]],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada', 0, 0]],
                    ],
                ],
            ]
        );

        // Tabel 3.6
        $this->seedSharedRtRwTemplate(
            title: 'Tabel 3.6 Keberadaan Warga Negara Asing (WNA) di RT pada 1 Juni 2026',
            description: null,
            columns: [
                ['label' => 'Keberadaan WNA', 'data_type' => 'text'],
            ],
            entriesByVillageName: [
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada']],
                    ],
                ],
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 4, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 4, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 7, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 7, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 7, 'values' => ['Tidak ada']],
                        ['rt' => 4, 'rw' => 7, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 8, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 8, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 8, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 9, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 9, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Tidak ada']],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 4, 'rw' => 1, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Tidak ada']],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Tidak ada']],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Tidak ada']],
                    ],
                ],
            ]
        );

        // Tabel 3.7
        $this->seedSharedRtRwTemplate(
            title: 'Tabel 3.7 Lapangan Usaha dari Sumber Penghasilan Utama Sebagian Besar Penduduk RT',
            description: null,
            columns: [
                ['label' => 'Lapangan Usaha Utama', 'data_type' => 'text'],
            ],
            entriesByVillageName: [
                'Wale' => [
                    'source' => 'Pokelcan 2026 – Wale',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Jasa']],
                    ],
                ],
                'Bataraguru' => [
                    'source' => 'Pokelcan 2026 – Bataraguru',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Jasa']],
                        ['rt' => 4, 'rw' => 3, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Jasa']],
                        ['rt' => 4, 'rw' => 6, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 7, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 7, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 7, 'values' => ['Jasa']],
                        ['rt' => 4, 'rw' => 7, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 8, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 8, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 8, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 9, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 9, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 9, 'values' => ['Jasa']],
                    ],
                ],
                'Batulo' => [
                    'source' => 'Pokelcan 2026 – Batulo',
                    'rows' => [
                        ['rt' => 1, 'rw' => 1, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 1, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 1, 'values' => ['Jasa']],
                        ['rt' => 4, 'rw' => 1, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 2, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 2, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 2, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 3, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 3, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 3, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 4, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 4, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 4, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 5, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 5, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 5, 'values' => ['Jasa']],
                        ['rt' => 1, 'rw' => 6, 'values' => ['Jasa']],
                        ['rt' => 2, 'rw' => 6, 'values' => ['Jasa']],
                        ['rt' => 3, 'rw' => 6, 'values' => ['Jasa']],
                    ],
                ],
            ]
        );

        // Tabel 4.
        // Tabel 4.
        // Tabel 4.
        // Tabel 4.
        // Tabel 4.
        // Tabel 4.
        // Tabel 4.
        // Tabel 4.
        // Tabel 4.
        // Tabel 4.
    }

    /**
     * Bikin (atau pakai ulang) 1 StatisticTemplate SHARED mode row_source='rt_rw', lalu untuk
     * tiap Kelurahan di $entriesByVillageName: generate baris RT/RW + cell KHUSUS Kelurahan itu
     * (struktur identik dengan yang dibuat GenerateRtRowsForVillage), lalu isi nilainya lewat
     * Action resmi CreateStatisticTableEntry.
     *
     * Idempotent di 2 level:
     * - Template: kalau judul sudah ada, dipakai ulang (header kolom TIDAK dibuat dobel).
     * - Per Kelurahan: kalau Kelurahan itu sudah pernah punya entry di template ini, dilewati.
     *
     * @param  array<int, array{label: string, data_type: string}>  $columns
     * @param  array<string, array{source: ?string, rows: array<int, array{rt: int, rw: int, values: array}>}>  $entriesByVillageName
     */
    private function seedSharedRtRwTemplate(string $title, ?string $description, array $columns, array $entriesByVillageName): void
    {
        $template = StatisticTemplate::where('title', $title)->first();
        $isNewTemplate = !$template;

        if ($isNewTemplate) {
            $template = StatisticTemplate::create([
                'title' => $title,
                'description' => $description,
                'is_active' => true,
                'is_mapped' => false, // BPS aktifkan manual nanti via halaman Template
                'row_source' => 'rt_rw',
                'created_by' => null, // dibuat dari CLI, bukan oleh user BPS yang login
            ]);

            $columnHeaderIds = [];
            foreach ($columns as $order => $col) {
                $header = $template->headers()->create([
                    'axis' => 'column',
                    'parent_id' => null,
                    'label' => $col['label'],
                    'key' => Str::slug($col['label']) . '-' . Str::random(6),
                    'data_type' => $col['data_type'],
                    'is_leaf' => true,
                    'order' => $order,
                ]);
                $columnHeaderIds[] = $header->id;
            }
        } else {
            $this->command?->info("Template \"{$title}\" sudah ada, dipakai ulang (bukan dibuat baru).");
            $columnHeaderIds = $template->headers()
                ->where('axis', 'column')->where('is_leaf', true)
                ->orderBy('order')->pluck('id')->all();
        }

        foreach ($entriesByVillageName as $villageName => $entryData) {
            $village = Village::where('name', $villageName)->firstOrFail();

            // StatisticTableEntry pakai trait BelongsToVillage — mengisi village_id otomatis
            // dari binding ini, meniru scope subdomain kelurahan saat request web asli.
            app()->instance('current_village_id', $village->id);

            if (StatisticTableEntry::where('statistic_template_id', $template->id)->exists()) {
                $this->command?->warn("Lewati {$villageName} untuk \"{$title}\" — sudah pernah diisi.");
                continue;
            }

            // Baris RT/RW + cell cross-product KHUSUS $village — strukturnya sama persis dengan
            // yang dibuat GenerateRtRowsForVillage, supaya tetap kompatibel dengan halaman
            // Isi Tabel Admin Kelurahan & Dashboard Peta Publik.
            $cellIdsByRow = [];
            foreach ($entryData['rows'] as $order => $row) {
                $rowHeader = $template->headers()->create([
                    'axis' => 'row',
                    'parent_id' => null,
                    'label' => sprintf('RT %03d RW %03d', $row['rt'], $row['rw']),
                    'key' => "rt-{$row['rt']}-rw-{$row['rw']}-village-{$village->id}",
                    'is_leaf' => true,
                    'rt_value' => (string) $row['rt'],
                    'rw_value' => (string) $row['rw'],
                    'village_id' => $village->id,
                    'order' => $order,
                ]);

                foreach ($columnHeaderIds as $colIndex => $columnHeaderId) {
                    $cell = $template->cells()->create([
                        'row_header_id' => $rowHeader->id,
                        'column_header_id' => $columnHeaderId,
                        'village_id' => $village->id,
                        'is_locked' => false,
                    ]);
                    $cellIdsByRow[$order][$colIndex] = $cell->id;
                }
            }

            $values = [];
            foreach ($entryData['rows'] as $order => $row) {
                foreach ($row['values'] as $colIndex => $value) {
                    if ($value === null) {
                        continue;
                    }
                    $values[$cellIdsByRow[$order][$colIndex]] = $value;
                }
            }

            app(CreateStatisticTableEntry::class)->handle($template, [
                'source' => $entryData['source'] ?? null,
                'description' => null,
                'values' => $values,
            ]);

            $this->command?->info("  \xe2\x9c\x93 {$villageName}: " . count($entryData['rows']) . ' baris RT/RW terisi.');
        }
    }
}