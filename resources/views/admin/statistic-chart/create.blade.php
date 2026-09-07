<x-layout-admin title="Tambah Grafik: {{ $statisticalTableEntry->template->title }}">
    <x-hero title="Tambah Visualisasi Grafik" :subtitle="$statisticalTableEntry->template->title" />

    <div class="max-w-3xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <form action="{{ route('admin.statistic-chart.store', $statisticalTableEntry) }}" method="POST">
            @csrf

            <div class="card bg-base-100 card-border shadow-lg mb-6">
                <div class="card-body">
                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Judul Grafik</legend>
                        <input type="text" name="title" value="{{ old('title') }}" class="input w-full" required>
                        <x-forms.error name="title" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Jenis Grafik</legend>
                        <select name="chart_type" class="select w-full" required>
                            <option value="" disabled selected>-- Pilih Jenis Grafik --</option>
                            @foreach($chartTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('chart_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-forms.error name="chart_type" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Kolom Sumbu X (Kategori/Label)</legend>
                        <select name="x_axis_column" class="select w-full" required>
                            <option value="" disabled selected>-- Pilih Kolom --</option>
                            @foreach($headers as $header)
                                <option value="{{ $header }}" {{ old('x_axis_column') === $header ? 'selected' : '' }}>{{ $header }}</option>
                            @endforeach
                        </select>
                        <x-forms.error name="x_axis_column" />
                    </fieldset>

                    <fieldset class="fieldset w-full">
                        <legend class="fieldset-legend text-base">Kolom Sumbu Y (Data Angka)</legend>
                        <div class="flex flex-col gap-2">
                            {{-- array_slice($headers, 1): kolom pertama sengaja dilewati, karena itu label
                                 kategori/baris (mis. "Wilayah (RT/RW)" atau "Uraian"), bukan data angka. --}}
                            @foreach(array_slice($headers, 1) as $header)
                                <label class="label cursor-pointer justify-start gap-3 w-fit">
                                    <input type="checkbox" name="y_axis_columns[]" value="{{ $header }}"
                                        class="checkbox checkbox-sm"
                                        {{ collect(old('y_axis_columns'))->contains($header) ? 'checked' : '' }}>
                                    <span class="label-text">{{ $header }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-forms.error name="y_axis_columns" />
                    </fieldset>

                    <label class="label cursor-pointer justify-start gap-3 w-fit mt-4">
                        <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-sm" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="label-text">Tampilkan grafik ini di halaman publik</span>
                    </label>
                </div>
            </div>

            <div class="card-actions justify-end">
                <a href="{{ route('admin.statistic-table-entries.index') }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-secondary">Simpan Grafik</button>
            </div>
        </form>
    </div>
</x-layout-admin>