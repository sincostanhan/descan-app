<?php

namespace App\Actions;

use Illuminate\Support\Collection;

class ComputeRtRwSubtotals
{
    /**
     * Hitung Total per RW & Total Kelurahan dari baris-baris RT yang sudah ada — HANYA untuk
     * kolom data_type='numeric' (kolom 'text'/'both' dilewati, ditampilkan '-' di baris Total).
     * Tidak pernah disimpan ke DB; selalu dihitung ulang dari nilai RT terkini supaya tidak
     * pernah nyasar dari isian Admin Kelurahan yang sebenarnya.
     *
     * @param  Collection<int, \App\Models\StatisticTemplateHeader>  $rowLeaves  Baris RT (leaf), urut per RW
     * @param  Collection<int, \App\Models\StatisticTemplateHeader>  $columnLeaves
     * @param  array<int, array<int, \App\Models\StatisticTemplateCell>>  $cellsByRowCol  [row_header_id][column_header_id] => cell
     * @param  array<int, mixed>  $values  [cell_id => value]
     * @return array{rw: array<string, array{label: string, sums: array<int, float|null>}>, kelurahan: array{label: string, sums: array<int, float|null>}|null}
     */
    public function handle(Collection $rowLeaves, Collection $columnLeaves, array $cellsByRowCol, array $values): array
    {
        $numericColumnIds = $columnLeaves->where('data_type', 'numeric')->pluck('id')->all();

        if (empty($numericColumnIds)) {
            return ['rw' => [], 'kelurahan' => null];
        }

        $rwSums = [];    // rw_value => [column_id => running sum]
        $rwHasAny = [];  // rw_value => [column_id => ada minimal 1 RT terisi?]
        $rwLabels = [];  // rw_value => "Total RW 001"
        $kelurahanSums = array_fill_keys($numericColumnIds, 0.0);
        $kelurahanHasAny = array_fill_keys($numericColumnIds, false);

        foreach ($rowLeaves as $leaf) {
            $rw = $leaf->rw_value;
            if ($rw === null) {
                continue; // baris bukan bagian struktur rt_rw (seharusnya tidak terjadi di mode ini)
            }

            $rwLabels[$rw] ??= 'Total RW ' . str_pad((string) (int) $rw, 3, '0', STR_PAD_LEFT);
            $rwSums[$rw] ??= array_fill_keys($numericColumnIds, 0.0);
            $rwHasAny[$rw] ??= array_fill_keys($numericColumnIds, false);

            foreach ($numericColumnIds as $columnId) {
                $cell = $cellsByRowCol[$leaf->id][$columnId] ?? null;
                $value = $cell ? ($values[$cell->id] ?? null) : null;

                if ($value === null || $value === '' || !is_numeric($value)) {
                    continue;
                }

                $rwSums[$rw][$columnId] += (float) $value;
                $rwHasAny[$rw][$columnId] = true;
                $kelurahanSums[$columnId] += (float) $value;
                $kelurahanHasAny[$columnId] = true;
            }
        }

        $rwResult = [];
        foreach ($rwSums as $rw => $sums) {
            $rwResult[$rw] = [
                'label' => $rwLabels[$rw],
                // null (bukan 0) kalau tidak ada satupun RT di RW ini yang punya nilai —
                // biar tampilan '-' bukan '0' yang menyesatkan.
                'sums' => collect($sums)->map(
                    fn ($sum, $colId) => $rwHasAny[$rw][$colId] ? $sum : null
                )->all(),
            ];
        }

        $kelurahanResult = [
            'label' => 'Total Kelurahan',
            'sums' => collect($kelurahanSums)->map(
                fn ($sum, $colId) => $kelurahanHasAny[$colId] ? $sum : null
            )->all(),
        ];

        return ['rw' => $rwResult, 'kelurahan' => $kelurahanResult];
    }
}