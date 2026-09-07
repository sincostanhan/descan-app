<div class="space-y-4">
    <div class="alert alert-info shadow-sm mb-4">
        <x-lucide-info class="w-5 h-5" />
        <span><strong>Opsional</strong><br>Atur visualisasi grafik untuk tabel ini.</span>
    </div>

    <fieldset class="fieldset w-full">
        <legend class="fieldset-legend text-base">Tipe Grafik</legend>
        <select name="chart_type" id="chartTypeSelect" class="select w-full">
            <option value="">-- {{ $chart ? 'Kosongkan Jika Ingin Menghapus Grafik' : 'Pilih Tipe Grafik (Abaikan jika tidak ingin membuat)' }} --</option>
            @foreach($chartTypes as $value => $label)
                <option value="{{ $value }}" {{ old('chart_type', $chart?->chart_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <x-forms.error name="chart_type" />
    </fieldset>

    <fieldset class="fieldset w-full">
        <legend class="fieldset-legend text-base">Judul Grafik (Bisa dikosongkan)</legend>
        <input type="text" name="title" value="{{ old('title', $chart?->title) }}" placeholder="Default: Mengikuti Judul Tabel" class="input w-full"/>
        <x-forms.error name="title" />
    </fieldset>

    <fieldset class="fieldset w-full">
        <legend class="fieldset-legend text-base">Sumbu X (Kategori Utama)</legend>
        <select name="x_axis_column" id="xAxisSelect" class="select w-full">
            <option value="">-- Pilih Kolom Sumbu X --</option>
            @foreach($headers as $header)
                <option value="{{ $header }}" {{ old('x_axis_column', $chart?->x_axis_column) == $header ? 'selected' : '' }}>{{ $header }}</option>
            @endforeach
        </select>
        <x-forms.error name="x_axis_column" />
    </fieldset>

    <fieldset class="fieldset w-full mt-4">
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
                        <input type="checkbox" name="y_axis_columns[]" value="{{ $header }}" class="checkbox checkbox-primary checkbox-sm y-axis-checkbox"
                            {{ in_array($header, old('y_axis_columns', $savedYColumns)) ? 'checked' : '' }} />
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

    <fieldset class="fieldset w-full mt-2">
        <label class="cursor-pointer label justify-start gap-3">
            <input type="checkbox" name="has_total_row" id="hasTotalRowToggle" value="1" class="toggle toggle-warning"
                {{ old('has_total_row', $chart?->has_total_row) ? 'checked' : '' }} />
            <span class="label-text font-semibold">Kecualikan Baris Terakhir (Baris Total) dari Grafik</span>
        </label>
    </fieldset>

    <fieldset class="fieldset w-full mt-2">
        <label class="cursor-pointer label justify-start gap-3">
            <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary"
                {{ old('is_active', $chart ? $chart->is_active : true) ? 'checked' : '' }} />
            <span class="label-text font-semibold">Tampilkan Grafik Secara Publik</span>
        </label>
    </fieldset>
</div>

<div id="chartPreviewContainer" class="hidden mt-8 mb-4 border rounded-xl p-4 bg-white shadow-sm w-full">
    <h3 class="text-center font-bold text-gray-700 mb-4">Live Preview Grafik</h3>
    <div id="chartsGrid" class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full"></div>
</div>