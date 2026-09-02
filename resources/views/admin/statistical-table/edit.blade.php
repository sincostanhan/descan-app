<x-layout-admin title="Edit Tabel Statistik">
    <x-hero
        title="Edit Tabel Statistik"
    />

    <div class="max-w-5xl mx-auto px-4 lg:px-0 mb-12">
        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Perbarui Data Tabel</h2>
            
                <form action="{{ route('admin.statistical-table.update', $table) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="columns" value="{{ json_encode($columns) }}">
                    {{-- <input type="hidden" name="content" value="{{ json_encode($content) }}"> --}}
                    <input type="hidden" name="content" id="rawContent" value="{{ json_encode($content) }}">

                    {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-2"> --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2">
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend 
                            text-base">Nama Publikasi</legend>
                            <input type="text" 
                                name="publication" 
                                value="{{ old('publication', $table->publication) }}" 
                                required 
                                placeholder="Masukkan judul publikasi ..." 
                                class="input w-full"/>
                            <x-forms.error name="publication" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend 
                            text-base">Bab Publikasi</legend>
                            <input type="number" 
                                name="chapter" 
                                value="{{ old('chapter', $table->chapter) }}" 
                                required 
                                placeholder="Masukkan bab publikasi ... " 
                                class="input w-full"/>
                            <x-forms.error name="chapter" />
                        </fieldset>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6
                    mt-4">
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend 
                            text-base">Judul Tabel</legend>
                            <input type="text" 
                                name="title" 
                                value="{{ old('title', $table->title) }}" 
                                required 
                                placeholder="Masukkan judul tabel ..." 
                                class="input w-full"/>
                            <x-forms.error name="title" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend 
                            text-base">Sumber Data</legend>
                            <input type="text" 
                                name="source" 
                                value="{{ old('source', $table->source) }}" 
                                placeholder="Masukkan sumber data ..." 
                                class="input w-full"/>
                            <x-forms.error name="source" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend 
                            text-base">Keterangan (Opsional)</legend>
                            <textarea 
                                name="description" 
                                placeholder="Masukkan keterangan tabel ..." 
                                class="textarea h-24 w-full text-base leading-relaxed">{{ old('description', $table->description) }}</textarea>
                            <x-forms.error name="description" />
                        </fieldset>
                    </div>

                    <div class="mt-4">
                        <div class="tabs tabs-border">
                            <input 
                                type="radio" 
                                name="edit_tabs" 
                                class="tab" 
                                aria-label="Data Tabel" 
                                checked="checked" />
                            <div class="tab-content border-base-300 
                            bg-base-50 p-6">
                                <div class="overflow-x-auto 
                                {{-- bg-white  --}}
                                rounded border border-base-200 
                                shadow-sm">
                                    <table class="table table-zebra 
                                    table-sm md:table-md
                                    w-full">
                                        <thead class="bg-base-200/50 text-base-content 
                                        text-sm">
                                            <tr>
                                                @foreach($columns as $col)
                                                    <th class="whitespace-nowrap">{{ $col }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($content as $row)
                                                <tr>
                                                    @foreach($columns as $col)
                                                        <td class="whitespace-nowrap">{{ $row[$col] ?? '-' }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <input 
                                type="radio" 
                                name="edit_tabs" 
                                class="tab" 
                                aria-label="Grafik" />
                            <div class="tab-content border-base-300 
                            bg-base-50 p-6">
                                {{-- <div class="w-full py-20 
                                border border-dashed border-base-300 bg-white rounded shadow-sm 
                                flex flex-col items-center justify-center">
                                    <x-lucide-chart-column class="w-16 h-16 text-base-300 mb-4" />
                                    <h3 class="text-lg text-base-content/70 font-semibold">Konfigurasi Grafik</h3>
                                    <p class="text-sm text-base-content/50 mt-1 max-w-sm text-center">Fitur visualisasi grafik interaktif belum tersedia dan akan diimplementasikan pada tahap selanjutnya.</p>
                                </div> --}}
                                <div class="space-y-4">
                                    <div class="alert alert-info shadow-sm mb-4">
                                        <x-lucide-info class="w-5 h-5" />
                                        <span><strong>Opsional</strong><br>Perbarui konfigurasi grafik untuk tabel ini.</span>
                                    </div>

                                    <fieldset class="fieldset w-full">
                                        <legend class="fieldset-legend text-base">Tipe Grafik</legend>
                                        <select name="chart_type" class="select w-full">
                                            <option value="">-- Kosongkan Jika Ingin Menghapus Grafik --</option>
                                            <option value="pie" {{ old('chart_type', $chart?->chart_type) == 'pie' ? 'selected' : '' }}>Pie Chart</option>
                                            <option value="doughnut" {{ old('chart_type', $chart?->chart_type) == 'doughnut' ? 'selected' : '' }}>Doughnut Chart</option>
                                            <option value="bar_clustered" {{ old('chart_type', $chart?->chart_type) == 'bar_clustered' ? 'selected' : '' }}>Bar Chart (Clustered)</option>
                                            <option value="bar_stacked" {{ old('chart_type', $chart?->chart_type) == 'bar_stacked' ? 'selected' : '' }}>Bar Chart (Stacked)</option>
                                            <option value="column_clustered" {{ old('chart_type', $chart?->chart_type) == 'column_clustered' ? 'selected' : '' }}>Column Chart (Clustered)</option>
                                            <option value="column_stacked" {{ old('chart_type', $chart?->chart_type) == 'column_stacked' ? 'selected' : '' }}>Column Chart (Stacked)</option>
                                            <option value="line_markers" {{ old('chart_type', $chart?->chart_type) == 'line_markers' ? 'selected' : '' }}>Line Chart with Markers</option>
                                        </select>
                                        <x-forms.error name="chart_type" />
                                    </fieldset>

                                    <fieldset class="fieldset w-full">
                                        <legend class="fieldset-legend text-base">Judul Grafik</legend>
                                        <input type="text" name="chart_title" value="{{ old('chart_title', $chart?->title) }}" placeholder="Default: Mengikuti Judul Tabel" class="input w-full"/>
                                        <x-forms.error name="chart_title" />
                                    </fieldset>

                                    <fieldset class="fieldset w-full">
                                        <legend class="fieldset-legend text-base">Sumbu X (Kategori Utama)</legend>
                                        <select name="x_axis_column" id="xAxisSelect" class="select w-full">
                                            <option value="">-- Pilih Kolom Sumbu X --</option>
                                            @foreach($columns as $col)
                                                <option value="{{ $col }}" {{ old('x_axis_column', $chart?->x_axis_column) == $col ? 'selected' : '' }}>{{ $col }}</option>
                                            @endforeach
                                        </select>
                                        <x-forms.error name="x_axis_column" />
                                    </fieldset>
                    
                                    <fieldset class="fieldset w-full mt-4">
                                        <legend class="fieldset-legend text-base">Sumbu Y (Kolom Nilai/Angka) & Warna</legend>
                                        <div class="
                                            {{-- grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  --}}
                                            grid grid-cols-1 
                                            gap-3 border p-4 rounded-lg bg-base-200/30"
                                        >
                                            @php
                                                $defaultColors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'];
                                                $savedYColumns = $chart?->y_axis_columns ?? [];
                                                $savedYColors = $chart?->y_axis_colors ?? [];
                                            @endphp
                                            
                                            @foreach($columns as $index => $col)
                                                <div class="flex items-center gap-2 p-1 y-axis-wrapper">
                                                    <label class="cursor-pointer label justify-start gap-2 flex-grow">
                                                        <input type="checkbox" name="y_axis_columns[]" value="{{ $col }}" class="checkbox checkbox-primary checkbox-sm y-axis-checkbox" 
                                                            {{ in_array($col, old('y_axis_columns', $savedYColumns)) ? 'checked' : '' }} />
                                                        <span class="label-text truncate">{{ $col }}</span>
                                                    </label>
                                                    <input type="color" name="y_axis_colors[{{ $col }}]" 
                                                        value="{{ old('y_axis_colors.'.$col, $savedYColors[$col] ?? $defaultColors[$index % count($defaultColors)]) }}" 
                                                        {{-- class="w-6 h-6 p-0 border-0 rounded cursor-pointer color-picker-input"  --}}
                                                        class="w-12 h-6 p-0 border-0 rounded cursor-pointer color-picker-input" 
                                                        title="Pilih Warna untuk {{ $col }}" />
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
                                            <input type="checkbox" name="is_chart_active" value="1" class="toggle toggle-primary" 
                                            {{ old('is_chart_active', $chart ? $chart->is_active : true) ? 'checked' : '' }} />
                                            <span class="label-text font-semibold">Tampilkan Grafik Secara Publik</span>
                                        </label>
                                    </fieldset>
                                </div>
                                
                                <div id="chartPreviewContainer" class="hidden mt-8 mb-4 border rounded-xl p-4 bg-white shadow-sm w-full">
                                    <h3 class="text-center font-bold text-gray-700 mb-4">Live Preview Grafik</h3>
                                    <div id="chartsGrid" class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full">
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end 
                    space-x-2 pt-6 mt-6 
                    border-t border-base-200">
                        <a 
                            href="{{ route('admin.statistical-table.index') }}" class="btn btn-ghost">Batal</a>
                        {{-- <button type="submit" class="btn btn-warning text-white"> --}}
                        <button type="submit" class="btn btn-secondary text-white">
                            {{-- <x-lucide-save class="w-5 h-5 mr-1" /> Perbarui Data --}}
                            <x-lucide-save class="w-5 h-5 mr-1" />Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hasTotalRowToggle = document.getElementById('hasTotalRowToggle');
            const rawContentInput = document.getElementById('rawContent');
            const chartTypeSelect = document.querySelector('select[name="chart_type"]');
            const xAxisSelect = document.querySelector('select[name="x_axis_column"]');
            const yAxisCheckboxes = document.querySelectorAll('.y-axis-checkbox');
            const chartPreviewContainer = document.getElementById('chartPreviewContainer');
            const chartsGrid = document.getElementById('chartsGrid');
            
            let chartInstances = [];
            const tableData = JSON.parse(rawContentInput.value);

            function updateYAxisCheckboxes() {
                const selectedX = xAxisSelect.value;
                yAxisCheckboxes.forEach(checkbox => {
                    if (checkbox.value === selectedX) {
                        checkbox.disabled = true;
                        checkbox.checked = false;
                        checkbox.parentElement.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        checkbox.disabled = false;
                        checkbox.parentElement.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                });
            }

            function destroyAllCharts() {
                chartInstances.forEach(chart => chart.destroy());
                chartInstances = [];
                chartsGrid.innerHTML = '';
            }

            function createCanvasContainer(titleText = null) {
                const wrapper = document.createElement('div');
                wrapper.className = 'w-full flex flex-col items-center';
                
                if(titleText) {
                    const title = document.createElement('h4');
                    title.className = 'text-sm font-semibold mb-2 text-center';
                    title.innerText = titleText;
                    wrapper.appendChild(title);
                }

                const canvasWrapper = document.createElement('div');
                canvasWrapper.style.position = 'relative';
                canvasWrapper.style.height = '300px';
                canvasWrapper.style.width = '100%';
                
                const canvas = document.createElement('canvas');
                canvasWrapper.appendChild(canvas);
                wrapper.appendChild(canvasWrapper);
                chartsGrid.appendChild(wrapper);

                return canvas.getContext('2d');
            }

            function hexToRgb(hex) {
                let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
                return result ? `${parseInt(result[1], 16)}, ${parseInt(result[2], 16)}, ${parseInt(result[3], 16)}` : "0, 0, 0";
            }

            function updatePreview() {
                const type = chartTypeSelect.value;
                const xAxis = xAxisSelect.value;
                const checkedYAxes = Array.from(yAxisCheckboxes).filter(cb => cb.checked).map(cb => cb.value);

                if (!type || !xAxis || checkedYAxes.length === 0) {
                    chartPreviewContainer.classList.add('hidden');
                    destroyAllCharts();
                    return;
                }

                chartPreviewContainer.classList.remove('hidden');
                destroyAllCharts();

                let dataToRender = tableData; 
                if (hasTotalRowToggle && hasTotalRowToggle.checked) {
                    dataToRender = tableData.slice(0, -1); 
                }

                const labels = dataToRender.map(row => row[xAxis] || '-');

                if (type === 'pie' || type === 'doughnut') {
                    chartsGrid.className = checkedYAxes.length > 1 ? 'grid grid-cols-1 md:grid-cols-2 gap-8 w-full' : 'grid grid-cols-1 gap-8 w-full max-w-lg mx-auto';

                    checkedYAxes.forEach((yCol) => {
                        const ctx = createCanvasContainer(yCol); 
                        
                        const checkbox = Array.from(yAxisCheckboxes).find(cb => cb.value === yCol);
                        const colorInput = checkbox.closest('.y-axis-wrapper').querySelector('.color-picker-input');
                        const rgbColor = hexToRgb(colorInput.value);

                        // ----------------------------------------------------
                        // MULAI LOGIKA PENGURUTAN & WARNA CERDAS UNTUK PIE
                        // ----------------------------------------------------

                        // 1. Gabungkan data asli menjadi array of objects agar tidak tertukar saat di-sort
                        let combinedData = labels.map((label, index) => {
                            return {
                                label: label,
                                value: parseFloat(dataToRender[index][yCol]) || 0
                            };
                        });

                        // 2. Urutkan berdasarkan value secara Descending (Besar ke Kecil)
                        combinedData.sort((a, b) => b.value - a.value);

                        // 3. Pisahkan kembali jadi array labels dan array data yang sudah terurut
                        const sortedLabels = combinedData.map(item => item.label);
                        const sortedData = combinedData.map(item => item.value);

                        // 4. Logika Warna Gradasi berdasarkan ukuran
                        let pieColors = [];
                        const minOpacity = 0.25; // Batas paling terang
                        let currentOpacity = 1.0; // Dimulai dari solid gelap
                        
                        // Hitung jumlah nilai unik untuk menentukan besaran "langkah" penurunan warna
                        const uniqueValuesCount = new Set(sortedData).size;
                        // Rumus penurunan warna tiap ganti ukuran
                        const opacityStep = uniqueValuesCount > 1 ? ((1.0 - minOpacity) / (uniqueValuesCount - 1)) : 0;

                        for (let i = 0; i < sortedData.length; i++) {
                            // Jika ini bukan elemen pertama, DAN nilainya LEBIH KECIL dari elemen sebelumnya
                            if (i > 0 && sortedData[i] < sortedData[i - 1]) {
                                currentOpacity -= opacityStep; // Kurangi warna (jadikan lebih terang)
                            }
                            // Jika nilainya SAMA dengan sebelumnya, currentOpacity TIDAK BERUBAH (warna sama)

                            pieColors.push(`rgba(${rgbColor}, ${currentOpacity.toFixed(2)})`);
                        }
                        // ----------------------------------------------------
                        // AKHIR LOGIKA PENGURUTAN & WARNA CERDAS
                        // ----------------------------------------------------
                        
                        // const pieColors = labels.map((_, i) => {
                        //     const minOpacity = 0.25;
                        //     const opacity = labels.length > 1 ? 1 - (i * ((1 - minOpacity) / (labels.length - 1))) : 1;
                        //     return `rgba(${rgbColor}, ${opacity.toFixed(2)})`;
                        // });

                        const newChart = new Chart(ctx, {
                            type: type,
                            data: {
                                // labels: labels,
                                labels: sortedLabels,
                                datasets: [{
                                    label: yCol,
                                    // data: dataToRender.map(row => parseFloat(row[yCol]) || 0),
                                    data: sortedData,
                                    backgroundColor: pieColors,
                                    borderColor: `#ffffff`,
                                    borderWidth: 1
                                }]
                            },
                            options: { 
                                responsive: true, 
                                maintainAspectRatio: false, 
                                plugins: {
                                    // Opsional: Matikan animasi muter-muter saat live preview biar nggak pusing
                                    animation: false 
                                }
                            }
                        });
                        chartInstances.push(newChart);
                    });

                } else {
                    chartsGrid.className = 'grid grid-cols-1 w-full';
                    const ctx = createCanvasContainer();
                    
                    const datasets = checkedYAxes.map((yCol) => {
                        const checkbox = Array.from(yAxisCheckboxes).find(cb => cb.value === yCol);
                        const colorInput = checkbox.closest('.y-axis-wrapper').querySelector('.color-picker-input');
                        const hexColor = colorInput.value;

                        return {
                            label: yCol,
                            data: dataToRender.map(row => parseFloat(row[yCol]) || 0),
                            backgroundColor: hexColor, 
                            borderColor: hexColor,
                            borderWidth: 1
                        };
                    });

                    let actualType = 'bar';
                    let indexAxis = 'x';
                    let isStacked = false;

                    switch(type) {
                        case 'bar_clustered': actualType = 'bar'; indexAxis = 'y'; break;
                        case 'bar_stacked': actualType = 'bar'; indexAxis = 'y'; isStacked = true; break;
                        case 'column_clustered': actualType = 'bar'; indexAxis = 'x'; break;
                        case 'column_stacked': actualType = 'bar'; indexAxis = 'x'; isStacked = true; break;
                        case 'line_markers': actualType = 'line'; break;
                    }

                    const newChart = new Chart(ctx, {
                        type: actualType,
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: indexAxis,
                            scales: {
                                x: { stacked: isStacked },
                                y: { stacked: isStacked }
                            }
                        }
                    });
                    chartInstances.push(newChart);
                }
            }

            chartTypeSelect.addEventListener('change', updatePreview);
            xAxisSelect.addEventListener('change', () => {
                updateYAxisCheckboxes();
                updatePreview();
            });
            yAxisCheckboxes.forEach(cb => cb.addEventListener('change', updatePreview));
            if (hasTotalRowToggle) hasTotalRowToggle.addEventListener('change', updatePreview);
            
            const colorPickers = document.querySelectorAll('.color-picker-input');
            colorPickers.forEach(picker => picker.addEventListener('input', updatePreview));

            updateYAxisCheckboxes();
            updatePreview();
        });
    </script>
</x-layout-admin>