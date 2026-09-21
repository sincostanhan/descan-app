@php
    // Saat create (belum ada grafik), $chart tidak pernah dikirim dari controller — partial ini
    // dipakai bersama create & edit (lihat include tanpa parameter eksplisit di kedua blade),
    // jadi harus toleran terhadap kondisi "belum ada chart sama sekali".
    $chart = $chart ?? null;

    // $headers di sini SUDAH terfilter (tanpa kolom teks) oleh controller — count > 1 berarti
    // ada minimal 1 kolom numerik/both beneran, di luar row label ('Wilayah (RT/RW)' dsb).
    $hasNumericColumns = $hasNumericColumns ?? (count($headers) > 1);
@endphp
<div class="space-y-6">
    <div class="alert alert-info shadow-sm">
        <x-lucide-info class="w-5 h-5" />
        <span><strong>Opsional</strong><br>Atur visualisasi grafik untuk tabel ini.</span>
    </div>

    {{-- ===== 1. INFORMASI UMUM — berlaku untuk grafik numerik maupun kategori ===== --}}
    <fieldset class="fieldset w-full">
        <legend class="fieldset-legend text-base">Judul Grafik (Bisa dikosongkan)</legend>
        <input type="text" name="title" value="{{ old('title', $chart?->title) }}" placeholder="Default: Mengikuti Judul Tabel" class="input w-full"/>
        <x-forms.error name="title" />
    </fieldset>

    {{-- ===== 2. GRAFIK NUMERIK — 1 kotak, pudar & nonaktif bareng-bareng kalau tabel ini ===== --}}
    {{-- tidak punya kolom numerik sama sekali. ===== --}}
    <div class="rounded-box border border-base-300 p-4 {{ !$hasNumericColumns ? 'opacity-50' : '' }}">
        <h4 class="font-semibold text-base-content/80 mb-3">Grafik Numerik (Sumbu X &amp; Y)</h4>

        @unless($hasNumericColumns)
            <div class="alert alert-warning shadow-sm mb-4">
                <x-lucide-info class="w-5 h-5" />
                <span>Tabel ini tidak punya kolom numerik — bagian ini dinonaktifkan. Gunakan <strong>Grafik Kategori</strong> di bawah untuk memvisualisasikan kolom teks di tabel ini.</span>
            </div>
        @endunless

        <fieldset class="fieldset w-full" {{ !$hasNumericColumns ? 'disabled' : '' }}>
            <legend class="fieldset-legend text-base">Tipe Grafik</legend>
            <select name="chart_type" id="chartTypeSelect" class="select w-full">
                <option value="">-- {{ $chart ? 'Kosongkan Jika Ingin Menghapus Grafik Numerik' : 'Pilih Tipe Grafik (Abaikan jika tidak ingin membuat)' }} --</option>
                @foreach($chartTypes as $value => $label)
                    <option value="{{ $value }}" {{ old('chart_type', $chart?->chart_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <x-forms.error name="chart_type" />
        </fieldset>

        <fieldset class="fieldset w-full mt-2" {{ !$hasNumericColumns ? 'disabled' : '' }}>
            <legend class="fieldset-legend text-base">Sumbu X (Kategori Utama)</legend>
            <select name="x_axis_column" id="xAxisSelect" class="select w-full">
                <option value="">-- Pilih Kolom Sumbu X --</option>
                @foreach($headers as $header)
                    <option value="{{ $header }}" {{ old('x_axis_column', $chart?->x_axis_column) == $header ? 'selected' : '' }}>{{ $header }}</option>
                @endforeach
            </select>
            <x-forms.error name="x_axis_column" />
        </fieldset>

        <fieldset class="fieldset w-full mt-2" {{ !$hasNumericColumns ? 'disabled' : '' }}>
            <legend class="fieldset-legend text-base">Sumbu Y (Kolom Nilai/Angka) & Warna</legend>
            <div class="grid grid-cols-1 gap-3 border p-4 rounded-lg bg-base-200/30">
                @php
                    $defaultColors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'];
                    $savedYColumns = $chart?->y_axis_columns ?? [];
                    $savedYColors = $chart?->y_axis_colors ?? [];
                @endphp

                @foreach($headers as $index => $header)
                    <div class="flex items-center gap-2 p-1 y-axis-wrapper">
                        <label class="cursor-pointer label justify-start gap-2 flex-grow">
                            <input type="checkbox" name="y_axis_columns[]" value="{{ $header }}" class="peer sr-only y-axis-checkbox"
                                {{ in_array($header, old('y_axis_columns', $savedYColumns)) ? 'checked' : '' }} />
                            <x-lucide-square class="w-5 h-5 text-base-content/40 peer-checked:hidden" />
                            <x-lucide-square-check class="w-5 h-5 text-primary hidden peer-checked:block" />
                            <span class="label-text truncate">{{ $header }}</span>
                        </label>
                        <input type="color" name="y_axis_colors[{{ $header }}]"
                            value="{{ old('y_axis_colors.'.$header, $savedYColors[$header] ?? $defaultColors[$index % count($defaultColors)]) }}"
                            class="w-12 h-6 p-0 border-0 rounded cursor-pointer color-picker-input"
                            title="Pilih Warna untuk {{ $header }}" />
                    </div>
                @endforeach
            </div>
            <x-forms.error name="y_axis_columns" />
        </fieldset>

        <fieldset class="fieldset w-full mt-2" {{ !$hasNumericColumns ? 'disabled' : '' }}>
            <legend class="fieldset-legend text-base">Pilih Baris yang Ditampilkan</legend>
            @php
                $rowLabelKey = $headers[0] ?? null;
                $savedIncludedRows = old('included_rows', $chart?->included_rows ?? range(0, count($statisticalTableEntry->content) - 1));
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 border p-4 rounded-lg bg-base-200/30 max-h-64 overflow-y-auto">
                @foreach($statisticalTableEntry->content as $index => $row)
                    <label class="cursor-pointer label justify-start gap-2">
                        <input type="checkbox" name="included_rows[]" value="{{ $index }}" class="peer sr-only row-checkbox"
                            {{ in_array($index, $savedIncludedRows) ? 'checked' : '' }} />
                        <x-lucide-square class="w-5 h-5 text-base-content/40 peer-checked:hidden shrink-0" />
                        <x-lucide-square-check class="w-5 h-5 text-primary hidden peer-checked:block shrink-0" />
                        <span class="label-text truncate">{{ $row[$rowLabelKey] ?? "Baris {$index}" }}</span>
                    </label>
                @endforeach
            </div>
            <x-forms.error name="included_rows" />
        </fieldset>
    </div>

    {{-- ===== 3. GRAFIK KATEGORI (TEKS) — kotak terpisah, SELALU aktif ===== --}}
    <div class="rounded-box border border-base-300 p-4">
        <h4 class="font-semibold text-base-content/80 mb-3">Grafik Kategori (Kolom Teks)</h4>

        <fieldset class="fieldset w-full">
            <p class="text-xs text-base-content/60 mb-2">
                Pilih kolom teks untuk dibuatkan grafik distribusi (jumlah tiap nilai unik, mis. "Ada": 3, "Tidak ada": 16).
                Bisa pilih lebih dari satu — tiap kolom jadi 1 grafik terpisah, tampil bersamaan dengan Grafik Numerik di atas (jika ada).
            </p>
            @if($textColumns->isEmpty())
                <p class="text-sm text-base-content/40 italic">Tabel ini tidak punya kolom bertipe teks.</p>
            @else
                @php $savedCategoryColumns = collect($chart?->category_columns ?? [])->keyBy('column'); @endphp
                <div class="grid grid-cols-1 gap-3 border p-4 rounded-lg bg-base-200/30">
                    @foreach($textColumns as $col)
                        @php $saved = $savedCategoryColumns->get($col); @endphp
                        <div class="flex items-center gap-3 p-1">
                            <label class="cursor-pointer label justify-start gap-2 flex-grow">
                                <input type="checkbox" name="category_columns[]" value="{{ $col }}" class="peer sr-only category-column-checkbox"
                                    {{ in_array($col, old('category_columns', $savedCategoryColumns->keys()->all())) ? 'checked' : '' }} />
                                <x-lucide-square class="w-5 h-5 text-base-content/40 peer-checked:hidden" />
                                <x-lucide-square-check class="w-5 h-5 text-primary hidden peer-checked:block" />
                                <span class="label-text truncate">{{ $col }}</span>
                            </label>
                            <select name="category_chart_types[{{ $col }}]" class="select select-sm w-28 category-chart-type-select">
                                <option value="pie" {{ old("category_chart_types.$col", $saved['chart_type'] ?? 'pie') === 'pie' ? 'selected' : '' }}>Pie</option>
                                <option value="bar" {{ old("category_chart_types.$col", $saved['chart_type'] ?? 'pie') === 'bar' ? 'selected' : '' }}>Bar</option>
                            </select>
                        </div>
                    @endforeach
                </div>
            @endif
            <x-forms.error name="category_columns" />
        </fieldset>
    </div>

    {{-- ===== 4. PUBLIKASI — berlaku untuk grafik apa pun yang aktif di atas ===== --}}
    <fieldset class="fieldset w-full">
        <label class="cursor-pointer label justify-start gap-3">
            <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary"
                {{ old('is_active', $chart ? $chart->is_active : true) ? 'checked' : '' }} />
            <span class="label-text font-semibold">Tampilkan Grafik Secara Publik</span>
        </label>
    </fieldset>
</div>

<div id="chartPreviewContainer" class="hidden mt-8 mb-4 border rounded-xl p-4 bg-base-100 shadow-sm w-full">
    <h3 class="text-center font-bold text-base-content/80 mb-4">Live Preview Grafik</h3>
    <div id="chartsGrid" class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full"></div>
</div>