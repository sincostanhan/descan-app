<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartTypeSelect = document.getElementById('chartTypeSelect');
        const xAxisSelect = document.getElementById('xAxisSelect');
        const yAxisCheckboxes = document.querySelectorAll('.y-axis-checkbox');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const categoryCheckboxes = document.querySelectorAll('.category-column-checkbox'); // BARU
        const categoryTypeSelects = document.querySelectorAll('.category-chart-type-select'); // BARU
        const chartPreviewContainer = document.getElementById('chartPreviewContainer');
        const chartsGrid = document.getElementById('chartsGrid');

        let chartInstances = [];
        // Sumber data live preview: accessor legacy-shape ($statisticalTableEntry->content), BUKAN Excel lagi.
        const tableData = @json($statisticalTableEntry->content);

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
            if (titleText) {
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

        // BARU: hitung frekuensi tiap nilai unik di 1 kolom, dari SEMUA baris tabel
        // (grafik kategori tidak terpengaruh checklist "Pilih Baris yang Ditampilkan").
        function computeFrequency(column) {
            const counts = {};
            tableData.forEach(row => {
                const val = (row[column] ?? '').toString().trim();
                if (val === '') return; // sel kosong tidak dihitung
                counts[val] = (counts[val] || 0) + 1;
            });
            return { labels: Object.keys(counts), data: Object.values(counts) };
        }

        // BARU: render 1 grafik kategori (pie atau bar) ke dalam chartsGrid
        function renderCategoryChart(column, catType) {
            const { labels, data } = computeFrequency(column);
            if (labels.length === 0) return; // tidak ada nilai terisi sama sekali, skip

            const palette = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16'];
            const colors = labels.map((_, i) => palette[i % palette.length]);

            const ctx = createCanvasContainer(column);
            const newChart = new Chart(ctx, {
                type: catType === 'pie' ? 'pie' : 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: column,
                        data: data,
                        backgroundColor: colors,
                        borderColor: catType === 'pie' ? '#ffffff' : colors,
                        borderWidth: catType === 'pie' ? 2 : 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: catType === 'pie' } },
                    scales: catType === 'pie' ? {} : { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
            chartInstances.push(newChart);
        }

        function updatePreview() {
            const type = chartTypeSelect.value;
            const xAxis = xAxisSelect.value;
            const checkedYAxes = Array.from(yAxisCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
            const checkedCategoryCols = Array.from(categoryCheckboxes).filter(cb => cb.checked).map(cb => cb.value); // BARU

            const hasNumeric = !!(type && xAxis && checkedYAxes.length > 0);
            const hasCategory = checkedCategoryCols.length > 0; // BARU

            if (!hasNumeric && !hasCategory) {
                chartPreviewContainer.classList.add('hidden');
                destroyAllCharts();
                return;
            }

            chartPreviewContainer.classList.remove('hidden');
            destroyAllCharts();

            // ===== Grafik numerik (Sumbu Y) — TIDAK berubah dari sebelumnya, cuma dibungkus if =====
            if (hasNumeric) {
                const checkedRowIndices = Array.from(rowCheckboxes).filter(cb => cb.checked).map(cb => parseInt(cb.value));
                const dataToRender = tableData.filter((_, i) => checkedRowIndices.includes(i));
                const labels = dataToRender.map(row => row[xAxis] || '-');

                if (type === 'pie' || type === 'doughnut') {
                    chartsGrid.className = checkedYAxes.length > 1 ? 'grid grid-cols-1 md:grid-cols-2 gap-8 w-full' : 'grid grid-cols-1 gap-8 w-full max-w-lg mx-auto';

                    checkedYAxes.forEach((yCol) => {
                        const ctx = createCanvasContainer(yCol);
                        const checkbox = Array.from(yAxisCheckboxes).find(cb => cb.value === yCol);
                        const colorInput = checkbox.closest('.y-axis-wrapper').querySelector('.color-picker-input');
                        const rgbColor = hexToRgb(colorInput.value);

                        let combinedData = labels.map((label, index) => ({
                            label: label,
                            value: parseFloat(dataToRender[index][yCol]) || 0
                        }));
                        combinedData.sort((a, b) => b.value - a.value);

                        const sortedLabels = combinedData.map(item => item.label);
                        const sortedData = combinedData.map(item => item.value);

                        let pieColors = [];
                        const minOpacity = 0.25;
                        let currentOpacity = 1.0;
                        const uniqueValuesCount = new Set(sortedData).size;
                        const opacityStep = uniqueValuesCount > 1 ? ((1.0 - minOpacity) / (uniqueValuesCount - 1)) : 0;

                        for (let i = 0; i < sortedData.length; i++) {
                            if (i > 0 && sortedData[i] < sortedData[i - 1]) currentOpacity -= opacityStep;
                            pieColors.push(`rgba(${rgbColor}, ${currentOpacity.toFixed(2)})`);
                        }

                        const newChart = new Chart(ctx, {
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
                        chartInstances.push(newChart);
                    });

                } else {
                    chartsGrid.className = 'grid grid-cols-1 w-full';
                    const ctx = createCanvasContainer();

                    let actualType = 'bar';
                    let indexAxis = 'x';
                    let isStacked = false;
                    let isPercent = false;
                    let isFilled = false;

                    switch (type) {
                        case 'bar_clustered': actualType = 'bar'; indexAxis = 'y'; break;
                        case 'bar_stacked': actualType = 'bar'; indexAxis = 'y'; isStacked = true; break;
                        case 'bar_stacked_100': actualType = 'bar'; indexAxis = 'y'; isStacked = true; isPercent = true; break;
                        case 'column_clustered': actualType = 'bar'; indexAxis = 'x'; break;
                        case 'column_stacked': actualType = 'bar'; indexAxis = 'x'; isStacked = true; break;
                        case 'column_stacked_100': actualType = 'bar'; indexAxis = 'x'; isStacked = true; isPercent = true; break;
                        case 'line_markers': actualType = 'line'; break;
                        case 'line_stacked': actualType = 'line'; isStacked = true; isFilled = true; break;
                        case 'line_stacked_100': actualType = 'line'; isStacked = true; isFilled = true; isPercent = true; break;
                    }

                    const rowTotals = isPercent
                        ? dataToRender.map(row => checkedYAxes.reduce((sum, col) => sum + (parseFloat(row[col]) || 0), 0))
                        : null;

                    const datasets = checkedYAxes.map((yCol) => {
                        const checkbox = Array.from(yAxisCheckboxes).find(cb => cb.value === yCol);
                        const colorInput = checkbox.closest('.y-axis-wrapper').querySelector('.color-picker-input');
                        const hexColor = colorInput.value;

                        const data = dataToRender.map((row, i) => {
                            const raw = parseFloat(row[yCol]) || 0;
                            if (!isPercent) return raw;
                            const total = rowTotals[i];
                            return total > 0 ? +(raw / total * 100).toFixed(2) : 0;
                        });

                        return {
                            label: yCol,
                            data: data,
                            backgroundColor: hexColor,
                            borderColor: hexColor,
                            borderWidth: 1,
                            fill: isFilled,
                        };
                    });

                    const newChart = new Chart(ctx, {
                        type: actualType,
                        data: { labels: labels, datasets: datasets },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: indexAxis,
                            scales: {
                                x: { stacked: isStacked },
                                y: {
                                    stacked: isStacked,
                                    max: isPercent ? 100 : undefined,
                                    ticks: { callback: v => isPercent ? v + '%' : v },
                                }
                            }
                        }
                    });
                    chartInstances.push(newChart);
                }
            } else if (hasCategory) {
                // Belum ada grafik numerik yang di-render, jadi chartsGrid belum punya className grid —
                // set default di sini supaya grafik kategori tetap rapi berjajar.
                // chartsGrid.className = 'grid grid-cols-1 md:grid-cols-2 gap-8 w-full';
                chartsGrid.className = checkedCategoryCols.length > 1
                    ? 'grid grid-cols-1 md:grid-cols-2 gap-8 w-full'
                    : 'grid grid-cols-1 gap-8 w-full max-w-lg mx-auto';
            }

            // ===== BARU: Grafik kategori — jalan independen, bisa bareng grafik numerik di atas =====
            checkedCategoryCols.forEach((col) => {
                const typeSelect = document.querySelector(`select[name="category_chart_types[${col}]"]`);
                const catType = typeSelect ? typeSelect.value : 'pie';
                renderCategoryChart(col, catType);
            });
        }

        chartTypeSelect.addEventListener('change', updatePreview);
        xAxisSelect.addEventListener('change', () => { updateYAxisCheckboxes(); updatePreview(); });
        yAxisCheckboxes.forEach(cb => cb.addEventListener('change', updatePreview));
        rowCheckboxes.forEach(cb => cb.addEventListener('change', updatePreview));
        document.querySelectorAll('.color-picker-input').forEach(picker => picker.addEventListener('input', updatePreview));
        categoryCheckboxes.forEach(cb => cb.addEventListener('change', updatePreview)); // BARU
        categoryTypeSelects.forEach(sel => sel.addEventListener('change', updatePreview)); // BARU

        updateYAxisCheckboxes();
        updatePreview();
    });
</script>