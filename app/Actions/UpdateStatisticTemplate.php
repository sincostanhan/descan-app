<?php

namespace App\Actions;

use App\Models\StatisticTemplate;
use App\Models\StatisticTemplateHeader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UpdateStatisticTemplate
{
    public function __construct(
        private LogTemplateChange $logTemplateChange,
        private ValidateMappableStructure $validateMappable,
    ) {}

    public function handle(StatisticTemplate $template, array $attributes): StatisticTemplate
    {
        return DB::transaction(function () use ($template, $attributes) {
            if ($template->row_source !== $attributes['row_source'] && $template->entries()->exists()) {
                throw ValidationException::withMessages([
                    'row_source' => 'Mode sumber baris tidak bisa diubah karena template ini sudah dipakai Kelurahan.',
                ]);
            }

            $template->update([
                'title' => $attributes['title'],
                'description' => $attributes['description'] ?? null,
                'is_active' => $attributes['is_active'] ?? $template->is_active,
                'row_source' => $attributes['row_source'],
            ]);

            $keptHeaderIds = [];

            // $rowLeafIds = $this->syncHeaderTree(
            //     $template, 'row', json_decode($attributes['row_headers'], true) ?? [], null, $keptHeaderIds
            // );
            $rowLeafIds = $template->row_source === 'manual'
                ? $this->syncHeaderTree($template, 'row', json_decode($attributes['row_headers'] ?? '[]', true) ?? [], null, $keptHeaderIds)
                : [];
            $columnLeafIds = $this->syncHeaderTree(
                $template, 'column', json_decode($attributes['column_headers'], true) ?? [], null, $keptHeaderIds
            );

            // Soft-delete header lama yang sudah tidak ada lagi di payload (BPS hapus dari form builder).
            // Soft delete (bukan hard delete) memastikan cell & value historis milik Kelurahan tetap utuh.
            // $removedHeaders = $template->headers()->whereNotIn('id', $keptHeaderIds)->get();
            $removedHeadersQuery = $template->headers()->whereNotIn('id', $keptHeaderIds);
            if ($template->row_source === 'rt_rw') {
                // Baris rt_rw dikelola GenerateRtRowsForVillage per-Kelurahan, BUKAN lewat form BPS ini
                // (form BPS mode rt_rw memang tidak pernah mengirim row_headers). Jangan pernah anggap
                // baris-baris itu "dihapus" hanya karena tidak ada di payload — cukup proses axis 'column' saja.
                $removedHeadersQuery->where('axis', '!=', 'row');
            }

            $removedHeaders = $removedHeadersQuery->get();
            foreach ($removedHeaders as $header) {
                $header->delete();
                $this->logTemplateChange->handle(
                    $template,
                    $header->axis === 'row' ? 'row_removed' : 'column_removed',
                    "Menghapus header {$header->axis} \"{$header->label}\" dari template."
                );
            }

            // Generate cell BARU untuk kombinasi leaf yang belum pernah ada — INILAH auto-sync/cascade-nya.
            // Kombinasi leaf lama yang tidak berubah TIDAK disentuh sama sekali (value Kelurahan aman).
            $this->generateMissingCells($template, $rowLeafIds, $columnLeafIds);

            $wantsMapped = $attributes['is_mapped'] ?? false;
            if ($wantsMapped) {
                $this->validateMappable->handle($template); // lempar ValidationException jika struktur tidak layak
            }
            $template->update(['is_mapped' => $wantsMapped]);

            return $template->refresh();
        });
    }

    /**
     * Sinkronisasi tree header: node ber-'id' di-update di tempat, node baru dibuat,
     * lalu setiap ID (baru maupun lama) dicatat ke $keptHeaderIds agar tidak dianggap "dihapus".
     */
    private function syncHeaderTree(StatisticTemplate $template, string $axis, array $nodes, ?int $parentId, array &$keptHeaderIds): array
    {
        $leafIds = [];

        foreach ($nodes as $order => $node) {
            $children = $node['children'] ?? [];
            $isLeaf = empty($children);

            $payload = [
                'axis' => $axis,
                'parent_id' => $parentId,
                'label' => $node['label'],
                'data_type' => ($axis === 'column' && $isLeaf) ? ($node['data_type'] ?? 'text') : null,
                'rt_value' => ($axis === 'row') ? ($node['rt_value'] ?? null) : null,
                'is_leaf' => $isLeaf,
                'order' => $order,
            ];

            $existing = !empty($node['id']) ? $template->headers()->find($node['id']) : null;
            $isNewHeader = !$existing;

            if ($existing) {
                $existing->update($payload);
                $header = $existing;
            } else {
                $payload['key'] = $isLeaf ? Str::slug($node['label']) . '-' . Str::random(6) : null;
                $header = $template->headers()->create($payload);
            }

            $keptHeaderIds[] = $header->id;

            if ($isNewHeader) {
                $this->logTemplateChange->handle(
                    $template,
                    $axis === 'row' ? 'row_added' : 'column_added',
                    "Menambahkan header {$axis} baru \"{$header->label}\" pada template."
                );
            }

            if ($isLeaf) {
                $leafIds[] = $header->id;
            } else {
                $leafIds = array_merge($leafIds, $this->syncHeaderTree($template, $axis, $children, $header->id, $keptHeaderIds));
            }
        }

        return $leafIds;
    }

    private function generateMissingCells(StatisticTemplate $template, array $rowLeafIds, array $columnLeafIds): void
    {
        // Mode rt_rw: $rowLeafIds selalu kosong (baris dikelola per Kelurahan, bukan oleh BPS).
        // Supaya kolom baru langsung "nyambung" ke baris RT yang sudah ada milik SEMUA Kelurahan,
        // kita ambil manual semua baris rt_rw yang sudah pernah digenerate.
        if ($template->row_source === 'rt_rw') {
            $rowLeafIds = $template->headers()
                ->where('axis', 'row')
                ->where('is_leaf', true)
                ->pluck('id')
                ->all();
        }

        $existingPairs = $template->cells()
            ->whereIn('row_header_id', $rowLeafIds)
            ->whereIn('column_header_id', $columnLeafIds)
            ->get()
            ->map(fn ($c) => "{$c->row_header_id}-{$c->column_header_id}")
            ->flip();

        foreach ($rowLeafIds as $rowId) {
            foreach ($columnLeafIds as $columnId) {
                if (!isset($existingPairs["{$rowId}-{$columnId}"])) {
                    // PENTING: cell baru untuk baris rt_rw harus ikut ditandai village_id yang SAMA
                    // dengan baris pemiliknya, supaya tidak "bocor" ke Kelurahan lain.
                    $rowVillageId = StatisticTemplateHeader::find($rowId)?->village_id;

                    $template->cells()->create([
                        'row_header_id' => $rowId,
                        'column_header_id' => $columnId,
                        'village_id' => $rowVillageId,
                        'is_locked' => false,
                    ]);
                }
            }
        }
    }
}