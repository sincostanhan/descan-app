<?php

namespace App\Actions;

use App\Models\RegionGeometry;
use App\Models\Setting;
use App\Models\StatisticTableEntry;
use App\Models\StatisticTableValue;
use App\Models\StatisticTemplate;
use App\Models\StatisticTemplateCell;
use App\Models\StatisticTemplateHeader;
use App\Models\Village;

class ResolveMapChoroplethData
{
    /**
     * Rangkai SELURUH RT/RW jadi 1 FeatureCollection untuk 1 kombinasi Template + Kolom —
     * dipakai choropleth Dashboard Peta Publik (semua RT/RW tampil sekaligus, beda warna
     * sesuai nilai). Mirror ResolveMapFeatureData, tapi tanpa filter rt/rw tunggal.
     *
     * RT/RW tanpa poligon tetap tidak ditampilkan (sama seperti versi single-feature).
     * RT/RW yang punya poligon tapi belum diisi nilai TETAP ikut tampil (warna netral),
     * supaya publik tahu wilayahnya ada tapi datanya kosong.
     */
    public function handle(StatisticTemplate $template, StatisticTemplateHeader $columnHeader): array
    {
        $rowHeaders = $template->headers()
            ->where('axis', 'row')
            ->where('is_leaf', true)
            ->whereNotNull('rt_value')
            ->whereNotNull('rw_value')
            ->get(['id', 'rt_value', 'rw_value']);

        // Normalisasi "001" -> "1" supaya format apa pun dari BPS (import GeoJSON) maupun
        // dari GenerateRtRowsForVillage (zero-padded) tetap match sebagai wilayah yang sama.
        $normalize = fn (string $rt, string $rw) => ((int) $rt) . '|' . ((int) $rw);

        // RegionGeometry & StatisticTableEntry pakai BelongsToVillage → otomatis ter-scope
        // ke kelurahan aktif dari subdomain, tidak perlu filter village_id manual.
        $geometries = RegionGeometry::all()->keyBy(fn ($g) => $normalize($g->rt, $g->rw));
        $entry = StatisticTableEntry::where('statistic_template_id', $template->id)->first();

        $cellsByRowHeader = StatisticTemplateCell::where('statistic_template_id', $template->id)
            ->where('column_header_id', $columnHeader->id)
            ->get()
            ->keyBy('row_header_id');

        $valuesByCellId = $entry
            ? StatisticTableValue::where('statistic_table_entry_id', $entry->id)
                ->whereIn('statistic_template_cell_id', $cellsByRowHeader->pluck('id'))
                ->pluck('value', 'statistic_template_cell_id')
            : collect();

        $village = app()->bound('current_village_id') ? Village::find(app('current_village_id')) : null;
        $setting = Setting::first();

        $features = [];

        foreach ($rowHeaders as $rowHeader) {
            $geometry = $geometries->get($normalize($rowHeader->rt_value, $rowHeader->rw_value));
            if (!$geometry) {
                continue;
            }

            $cell = $cellsByRowHeader->get($rowHeader->id);
            $value = $cell ? $valuesByCellId->get($cell->id) : null;

            $features[] = [
                'type' => 'Feature',
                'geometry' => $geometry->geojson,
                'properties' => [
                    'kelurahan' => $village?->name,
                    'kecamatan' => $setting?->kecamatan,
                    'rt' => $rowHeader->rt_value,
                    'rw' => $rowHeader->rw_value,
                    'rt_label' => 'RT ' . str_pad((string) (int) $rowHeader->rt_value, 3, '0', STR_PAD_LEFT),
                    'rw_label' => 'RW ' . str_pad((string) (int) $rowHeader->rw_value, 3, '0', STR_PAD_LEFT),
                    'column_label' => $columnHeader->label,
                    'value' => $value,
                ],
            ];
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
            // data_type dipakai frontend menentukan mode warna: numeric = gradasi, text = kategorikal,
            // null/both = auto-deteksi dari isi value yang ada.
            'meta' => [
                'data_type' => $columnHeader->data_type,
                'column_label' => $columnHeader->label,
            ],
        ];
    }
}