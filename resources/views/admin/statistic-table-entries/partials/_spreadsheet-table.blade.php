@php
    $existingValues = $existingValues ?? [];

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

    // Leaf kolom (urut), dipakai untuk iterasi tbody
    $columnLeaves = collect();
    $collectColumnLeaves = function ($nodes) use (&$collectColumnLeaves, &$columnLeaves) {
        foreach ($nodes as $node) {
            $node->is_leaf ? $columnLeaves->push($node) : $collectColumnLeaves($node->children);
        }
    };
    $collectColumnLeaves($template->columnHeaders);

    // Matriks header baris: rowMatrix[indexLeaf][depth] = node + rowspan/colspan
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

    // Lookup cepat: sel aktual (yang boleh diisi) berdasarkan pasangan row_header_id + column_header_id
    $cellsByRowCol = [];
    foreach ($template->cells as $cell) {
        $cellsByRowCol[$cell->row_header_id][$cell->column_header_id] = $cell;
    }

    // ===== Total per RW / Total Kelurahan — dihitung on-the-fly dari $existingValues yang =====
    // SUDAH TERSIMPAN (baru ter-update setelah submit & reload, TIDAK live saat mengetik).
    // Cukup untuk kebutuhan "lihat total sambil isi", tanpa kompleksitas JS recalculation.
    $rtRwTotals = null;
    if ($template->isRtRwMode() && ($template->show_rw_subtotal || $template->show_kelurahan_total)) {
        $rtRwTotals = app(\App\Actions\ComputeRtRwSubtotals::class)
            ->handle($rowLeaves, $columnLeaves, $cellsByRowCol, $existingValues);
    }

    $formatTotal = function ($value) {
        if ($value === null) return '-';
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
                        <th colspan="{{ $node->leaf_span }}"
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
                        @php $cell = $cellsByRowCol[$leaf->id][$colLeaf->id] ?? null; @endphp
                        <td class="p-1">
                            @if (!$cell)
                                <span class="text-base-content/30 text-xs">—</span>
                            @elseif ($cell->is_locked)
                                <input type="text" class="input input-sm w-full bg-base-200"
                                    value="{{ $existingValues[$cell->id] ?? '' }}" disabled>
                            @else
                                <input
                                    type="{{ $colLeaf->data_type === 'numeric' ? 'number' : 'text' }}"
                                    {{ $colLeaf->data_type === 'numeric' ? 'step=any' : '' }}
                                    name="values[{{ $cell->id }}]"
                                    value="{{ old("values.{$cell->id}", $existingValues[$cell->id] ?? '') }}"
                                    class="input input-sm w-full @error("values.{$cell->id}") input-error @enderror"
                                    placeholder="{{ $colLeaf->data_type === 'text' ? 'Teks' : ($colLeaf->data_type === 'numeric' ? 'Angka' : '') }}">
                                <x-forms.error name="values.{{ $cell->id }}" />
                            @endif
                        </td>
                    @endforeach
                </tr>

                {{-- Baris "Total RW xxx" — read-only (bukan <input>), cuma muncul tepat setelah
                     RT TERAKHIR di RW itu, supaya Admin Kelurahan tidak salah kira bisa diedit. --}}
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
                                <td class="whitespace-nowrap text-center">{{ $formatTotal($rwTotal['sums'][$colLeaf->id] ?? null) }}</td>
                            @endforeach
                        </tr>
                    @endif
                @endif
            @empty
                <tr><td colspan="{{ $maxRowDepth + $columnLeaves->count() }}" class="text-center italic text-base-content/50 py-6">
                    Tabel ini belum memiliki data RT/RW. Pastikan modul Organisasi Anda sudah diisi.
                </td></tr>
            @endforelse

            {{-- Baris "Total Kelurahan" — read-only, selalu di paling bawah --}}
            @if ($rtRwTotals && $template->show_kelurahan_total && $rtRwTotals['kelurahan'])
                <tr class="font-bold bg-base-300/70">
                    <th colspan="{{ $maxRowDepth }}" class="text-left whitespace-nowrap">{{ $rtRwTotals['kelurahan']['label'] }}</th>
                    @foreach ($columnLeaves as $colLeaf)
                        <td class="whitespace-nowrap text-center">{{ $formatTotal($rtRwTotals['kelurahan']['sums'][$colLeaf->id] ?? null) }}</td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>
</div>