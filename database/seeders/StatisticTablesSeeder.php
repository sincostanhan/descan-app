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