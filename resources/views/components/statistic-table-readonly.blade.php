@php
    // Logika matrix (rowspan/colspan header bertingkat) SAMA PERSIS dengan
    // admin/statistic-table-entries/partials/_spreadsheet-table.blade.php,
    // supaya struktur tabel yang tampil ke publik identik dengan yang dilihat
    // Admin Kelurahan — bedanya di sini read-only, tanpa <input>.
    $values = $values ?? [];

    // Opsional: sembunyikan kolom yang 100% kosong untuk kelurahan ini (mis. kolom "Keberadaan
    // Permukiman..." yang cuma relevan kalau topografinya Puncak/Tebing/Lereng). Default false
    // supaya pemakaian lain (mis. preview data tabel di halaman Grafik Admin BPS) tidak berubah —
    // di sana Admin justru perlu lihat semua kolom apa adanya.
    $hideEmptyColumns = $hideEmptyColumns ?? false;

    $treeHeight = function ($nodes) use (&$treeHeight) {
        if ($nodes->isEmpty()) return 0;
        return $nodes->max(fn ($node) => $node->is_leaf ? 1 : 1 + $treeHeight($node->children));
    };
    $maxColDepth = max($treeHeight($template->columnHeaders), 1);
    $maxRowDepth = max($treeHeight($template->rowHeaders), 1);

    $columnLevels = [];
    $collectColumnLevels = function ($nodes, $depth) use (&$collectColumnLevels, &$columnLevels) {
        foreach ($nodes as $node) {
            $columnLevels[$depth][] = $node;
            if (!$node->is_leaf) $collectColumnLevels($node->children, $depth + 1);
        }
    };
    $collectColumnLevels($template->columnHeaders, 0);
    for ($d = 0; $d < $maxColDepth; $d++) { $columnLevels[$d] = $columnLevels[$d] ?? []; }

    $allColumnLeaves = collect();
    $collectColumnLeaves = function ($nodes) use (&$collectColumnLeaves, &$allColumnLeaves) {
        foreach ($nodes as $node) {
            $node->is_leaf ? $allColumnLeaves->push($node) : $collectColumnLeaves($node->children);
        }
    };
    $collectColumnLeaves($template->columnHeaders);

    $rowLeaves = collect();
    $rowMatrix = [];
    $walkRow = function ($node, $depth) use (&$walkRow, &$rowLeaves, &$rowMatrix, $maxRowDepth) {
        if ($node->is_leaf) {
            $idx = $rowLeaves->count();
            $rowLeaves->push($node);
            $rowMatrix[$idx][$depth] = ['node' => $node, 'rowspan' => 1, 'colspan' => $maxRowDepth - $depth];
            return;
        }
        $startIdx = $rowLeaves->count();
        foreach ($node->children as $child) $walkRow($child, $depth + 1);
        $rowMatrix[$startIdx][$depth] = ['node' => $node, 'rowspan' => $node->leaf_span, 'colspan' => 1];
    };
    foreach ($template->rowHeaders as $rootRow) $walkRow($rootRow, 0);

    $cellsByRowCol = [];
    foreach ($template->cells as $cell) {
        $cellsByRowCol[$cell->row_header_id][$cell->column_header_id] = $cell;
    }

    // ===== Kolom yang 100% kosong (tidak ada satupun baris RT terisi) — dikumpulkan di sini, =====
    // dipakai untuk MENYARING $columnLeaves (tbody) DAN menghitung ulang colspan header (thead).
    $visibleLeafIds = $allColumnLeaves->filter(function ($colLeaf) use ($rowLeaves, $cellsByRowCol, $values) {
        foreach ($rowLeaves as $rowLeaf) {
            $cell = $cellsByRowCol[$rowLeaf->id][$colLeaf->id] ?? null;
            if ($cell && isset($values[$cell->id]) && $values[$cell->id] !== '' && $values[$cell->id] !== null) {
                return true;
            }
        }
        return false;
    })->pluck('id')->all();

    $columnLeaves = $hideEmptyColumns
        ? $allColumnLeaves->whereIn('id', $visibleLeafIds)->values()
        : $allColumnLeaves;

    // Colspan header parent dihitung ulang dari leaf yang VISIBLE saja (bukan $node->leaf_span
    // bawaan, yang selalu menghitung SEMUA leaf) — supaya header kolom bertingkat tetap presisi
    // walau sebagian leaf di bawahnya disembunyikan.
    $visibleLeafSpan = function ($node) use (&$visibleLeafSpan, $visibleLeafIds) {
        if ($node->is_leaf) {
            return in_array($node->id, $visibleLeafIds, true) ? 1 : 0;
        }
        return $node->children->sum(fn ($child) => $visibleLeafSpan($child));
    };

    // ===== Total per RW / Total Kelurahan — dihitung on-the-fly, TIDAK PERNAH disimpan. =====
    // Pakai $columnLeaves yang SUDAH disaring, supaya kolom yang disembunyikan otomatis
    // tidak ikut nongol di baris Total juga.
    $rtRwTotals = null;
    if ($template->isRtRwMode() && ($template->show_rw_subtotal || $template->show_kelurahan_total)) {
        $rtRwTotals = app(\App\Actions\ComputeRtRwSubtotals::class)
            ->handle($rowLeaves, $columnLeaves, $cellsByRowCol, $values);
    }

    $formatTotal = function ($value) {
        if ($value === null) return '-';
        // Bulat tampil tanpa desimal ("128"), pecahan tampil rapi ("7,35") — bukan "128.00"
        return floor($value) == $value
            ? number_format($value, 0, ',', '.')
            : rtrim(rtrim(number_format($value, 2, ',', '.'), '0'), ',');
    };
@endphp

<div class="overflow-x-auto rounded-box border-base-200 border">
    <table class="table table-pin-rows w-full">
        <thead>
            @for ($d = 0; $d < $maxColDepth; $d++)
                <tr>
                    @if ($d === 0)
                        <th rowspan="{{ $maxColDepth }}" colspan="{{ $maxRowDepth }}" class="bg-base-200"></th>
                    @endif
                    @foreach ($columnLevels[$d] as $node)
                        @php $span = $hideEmptyColumns ? $visibleLeafSpan($node) : $node->leaf_span; @endphp
                        @continue($hideEmptyColumns && $span === 0)
                        <th colspan="{{ $span }}"
                            rowspan="{{ $node->is_leaf ? ($maxColDepth - $d) : 1 }}"
                            class="bg-base-200 text-center whitespace-nowrap">
                            {{ $node->label }}
                        </th>
                    @endforeach
                </tr>
            @endfor
        </thead>
        <tbody>
            @forelse ($rowLeaves as $i => $leaf)
                <tr>
                    @for ($d = 0; $d < $maxRowDepth; $d++)
                        @if (isset($rowMatrix[$i][$d]))
                            <th rowspan="{{ $rowMatrix[$i][$d]['rowspan'] }}"
                                colspan="{{ $rowMatrix[$i][$d]['colspan'] }}"
                                class="bg-base-100 text-left whitespace-nowrap">
                                {{ $rowMatrix[$i][$d]['node']->label }}
                            </th>
                        @endif
                    @endfor

                    @foreach ($columnLeaves as $colLeaf)
                        @php
                            $cell = $cellsByRowCol[$leaf->id][$colLeaf->id] ?? null;
                            $value = $cell ? ($values[$cell->id] ?? null) : null;
                        @endphp
                        <td class="whitespace-nowrap">
                            @if (!$cell)
                                <span class="text-base-content/30 text-xs">—</span>
                            @elseif (is_null($value) || $value === '')
                                <span class="text-base-content/40 text-xs italic">Belum diisi</span>
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    @endforeach
                </tr>

                {{-- Baris "Total RW xxx" — cuma muncul tepat setelah RT TERAKHIR di RW itu --}}
                @if ($rtRwTotals && $template->show_rw_subtotal)
                    @php
                        $nextLeaf = $rowLeaves[$i + 1] ?? null;
                        $isLastOfRw = !$nextLeaf || $nextLeaf->rw_value !== $leaf->rw_value;
                        $rwTotal = $rtRwTotals['rw'][$leaf->rw_value] ?? null;
                    @endphp
                    @if ($isLastOfRw && $rwTotal)
                        <tr class="font-semibold bg-base-200/70">
                            <th colspan="{{ $maxRowDepth }}" class="text-left whitespace-nowrap">{{ $rwTotal['label'] }}</th>
                            @foreach ($columnLeaves as $colLeaf)
                                <td class="whitespace-nowrap">{{ $formatTotal($rwTotal['sums'][$colLeaf->id] ?? null) }}</td>
                            @endforeach
                        </tr>
                    @endif
                @endif
            @empty
                <tr>
                    <td colspan="{{ $maxRowDepth + $columnLeaves->count() }}" class="text-center italic text-base-content/50 py-6">
                        Data tabel masih kosong.
                    </td>
                </tr>
            @endforelse

            {{-- Baris "Total Kelurahan" — selalu di paling bawah --}}
            @if ($rtRwTotals && $template->show_kelurahan_total && $rtRwTotals['kelurahan'])
                <tr class="font-bold bg-base-300/70">
                    <th colspan="{{ $maxRowDepth }}" class="text-left whitespace-nowrap">{{ $rtRwTotals['kelurahan']['label'] }}</th>
                    @foreach ($columnLeaves as $colLeaf)
                        <td class="whitespace-nowrap">{{ $formatTotal($rtRwTotals['kelurahan']['sums'][$colLeaf->id] ?? null) }}</td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>
</div>