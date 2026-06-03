{{-- <x-layout title="{{ $statistic->title }}"> --}}
<x-layout title="Tabel {{ $statistic->title }}">
    <x-hero title="Bab {{ $statistic->chapter }}" subtitle="Publikasi {{ $statistic->publication }}" />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        
        <a href="{{ route('public.statistic.index') }}" class="btn btn-ghost mb-2">
            <x-lucide-arrow-left class="w-5 h-5 mr-1" /> Kembali</a>

        <div class="card bg-base-100 border shadow-lg border-base-200">
            <div class="card-body p-6 md:p-8">
                
                <div class="tabs tabs-border">
                    <input 
                        type="radio" 
                        name="public_tabs" 
                        class="tab font-semibold" 
                        aria-label="Tabel Data" 
                        checked="checked" />
                    <div class="tab-content border-base-300 
                    bg-base-50 p-6">
                        <h2 class="text-xl md:text-2xl font-bold text-secondary mb-4 text-center">{{ $statistic->title }}</h2>

                        <div class="overflow-x-auto 
                        rounded border border-base-200 
                        shadow-sm">
                            <table class="table table-zebra 
                            table-sm md:table-md 
                            w-full">
                                <thead class="bg-base-200/60 text-base-content
                                text-sm">
                                    <tr>
                                        @foreach($statistic->columns as $col)
                                            <th class="whitespace-nowrap">{{ $col }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statistic->content as $row)
                                        <tr>
                                            @foreach($statistic->columns as $col)
                                                <td class="whitespace-nowrap">{{ $row[$col] ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($statistic->source)
                            <p class="mt-4 text-base-content text-sm">
                                <strong>Sumber Data:</strong><br>{{ $statistic->source }}
                            </p>
                        @endif
                        
                        @if($statistic->description)
                            <p class="mt-2 text-base-content text-sm leading-relaxed">
                                <strong>Keterangan:</strong><br>{{ $statistic->description }}
                            </p>
                        @endif
                    </div>

                    @if($statistic->chart && $statistic->chart->is_active)
                        <input 
                            type="radio" 
                            name="public_tabs" 
                            class="tab font-semibold" 
                            aria-label="Visualisasi Grafik" />
                        <div class="tab-content border-base-300 
                        bg-base-50 p-6">
                            
                            <h2 class="text-xl md:text-2xl font-bold text-secondary mb-4 text-center">
                                {{ $statistic->chart->title }}
                            </h2>

                            <div id="publicChartsGrid" class="w-full">
                                </div>
                            
                            @if($statistic->source)
                                <div class="mt-8 text-center text-sm text-gray-500">
                                    Sumber Data: {{ $statistic->source }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @if($statistic->chart && $statistic->chart->is_active)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ambil konfigurasi grafik dan data tabel dari backend ke JavaScript
                const chartConfig = @json($statistic->chart);
                const rawTableData = @json($statistic->content);
                const chartsGrid = document.getElementById('publicChartsGrid');

                // Potong data baris terakhir jika fitur "has_total_row" diaktifkan admin
                let dataToRender = rawTableData;
                if (chartConfig.has_total_row) {
                    dataToRender = rawTableData.slice(0, -1);
                }

                const xAxis = chartConfig.x_axis_column;
                const labels = dataToRender.map(row => row[xAxis] || '-');
                const checkedYAxes = chartConfig.y_axis_columns || [];
                const type = chartConfig.chart_type;

                // Fungsi pembuat canvas
                function createCanvasContainer(titleText = null) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'w-full flex flex-col items-center';
                    if(titleText) {
                        const title = document.createElement('h4');
                        title.className = 'text-md font-semibold mb-2 text-center text-gray-600';
                        title.innerText = titleText;
                        wrapper.appendChild(title);
                    }
                    const canvasWrapper = document.createElement('div');
                    canvasWrapper.style.position = 'relative';
                    canvasWrapper.style.height = '400px'; // Grafik publik sedikit lebih besar
                    canvasWrapper.style.width = '100%';
                    const canvas = document.createElement('canvas');
                    canvasWrapper.appendChild(canvas);
                    wrapper.appendChild(canvasWrapper);
                    chartsGrid.appendChild(wrapper);
                    return canvas.getContext('2d');
                }

                // Fungsi konversi HEX ke RGB
                function hexToRgb(hex) {
                    let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
                    return result ? `${parseInt(result[1], 16)}, ${parseInt(result[2], 16)}, ${parseInt(result[3], 16)}` : "0, 0, 0";
                }

                // RENDERING LOGIC (Sama dengan pratinjau admin, tapi otomatis berjalan)
                if (type === 'pie' || type === 'doughnut') {
                    chartsGrid.className = checkedYAxes.length > 1 ? 'grid grid-cols-1 md:grid-cols-2 gap-10 w-full' : 'grid grid-cols-1 gap-10 w-full max-w-lg mx-auto';

                    checkedYAxes.forEach((yCol) => {
                        const ctx = createCanvasContainer(yCol); 
                        const baseHexColor = chartConfig.y_axis_colors[yCol] || '#3b82f6';
                        const rgbColor = hexToRgb(baseHexColor);

                        // Logika Sorting & Gradasi Warna Cerdas
                        let combinedData = labels.map((label, index) => {
                            return { label: label, value: parseFloat(dataToRender[index][yCol]) || 0 };
                        });
                        combinedData.sort((a, b) => b.value - a.value);

                        const sortedLabels = combinedData.map(item => item.label);
                        const sortedData = combinedData.map(item => item.value);

                        let pieColors = [];
                        let currentOpacity = 1.0;
                        const uniqueValuesCount = new Set(sortedData).size;
                        const opacityStep = uniqueValuesCount > 1 ? ((1.0 - 0.25) / (uniqueValuesCount - 1)) : 0;

                        for (let i = 0; i < sortedData.length; i++) {
                            if (i > 0 && sortedData[i] < sortedData[i - 1]) currentOpacity -= opacityStep;
                            pieColors.push(`rgba(${rgbColor}, ${currentOpacity.toFixed(2)})`);
                        }

                        new Chart(ctx, {
                            type: type,
                            data: {
                                labels: sortedLabels,
                                datasets: [{
                                    label: yCol,
                                    data: sortedData,
                                    backgroundColor: pieColors,
                                    borderColor: `#ffffff`,
                                    borderWidth: 2
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: false }
                        });
                    });

                } else {
                    chartsGrid.className = 'grid grid-cols-1 w-full';
                    const ctx = createCanvasContainer();
                    
                    const datasets = checkedYAxes.map((yCol) => {
                        const hexColor = chartConfig.y_axis_colors[yCol] || '#3b82f6';
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

                    new Chart(ctx, {
                        type: actualType,
                        data: { labels: labels, datasets: datasets },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: indexAxis,
                            scales: { x: { stacked: isStacked }, y: { stacked: isStacked } }
                        }
                    });
                }
            });
        </script>
    @endif
</x-layout>