<x-layout title="Dashboard Peta Statistik">
    <x-hero
        title="Dashboard Peta Statistik"
        subtitle="Pilih tabel dan kolom untuk melihat data statistik pada peta"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">

        <x-section-card title="Filter Data" title-size="text-xl">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <fieldset class="fieldset w-full">
                    <legend class="fieldset-legend">Judul Tabel</legend>
                    <select id="mapTemplateSelect" class="select w-full">
                        <option value="">-- Pilih Judul Tabel --</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->title }}</option>
                        @endforeach
                    </select>
                </fieldset>

                <fieldset class="fieldset w-full">
                    <legend class="fieldset-legend">Kolom</legend>
                    <select id="mapColumnSelect" class="select w-full" disabled>
                        <option value="">-- Pilih Judul Tabel Dahulu --</option>
                    </select>
                </fieldset>

                <fieldset class="fieldset w-full">
                    <legend class="fieldset-legend">Wilayah (RT/RW) — opsional, untuk zoom</legend>
                    <select id="mapWilayahSelect" class="select w-full" disabled>
                        <option value="">-- Memuat daftar wilayah... --</option>
                    </select>
                </fieldset>
            </div>

            <div id="mapAlert" class="alert alert-warning shadow-sm mb-4 hidden">
                <x-lucide-info class="w-5 h-5" />
                <span id="mapAlertText"></span>
            </div>

            <div id="map" class="w-full rounded-box border border-base-200" style="height: 500px; position: relative; overflow: hidden; z-index: 0;"></div>
            <div id="mapLegend" class="mt-3"></div>
            <style>
                /* Sama seperti di halaman import Admin BPS: cegah browser menggambar bounding-box
                   persegi sebagai focus outline bawaan saat polygon diklik. */
                .leaflet-interactive:focus {
                    outline: none;
                }
                /* Paksa panel Leaflet tetap terkurung di dalam #map — jangan andalkan
                   .leaflet-container dari leaflet.css saja, karena bisa keserobot timing Tailwind CDN. */
                #map .leaflet-pane,
                #map .leaflet-control-container {
                    z-index: 1;
                }
                /* <hr> bawaan Leaflet popup polos, dipertegas dikit biar kelihatan sebagai pemisah */
                .leaflet-popup-content hr {
                    border: none;
                    border-top: 1px solid rgba(0, 0, 0, 0.12);
                    margin: 6px 0;
                }
            </style>

        </x-section-card>
    </div>

    {{-- Leaflet dimuat via CDN, konsisten dengan pola Chart.js (statistic/show.blade.php) & pdf.js
         (admin/infographic/create.blade.php) — proyek ini tidak memakai bundel Vite di runtime. --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const templateSelect = document.getElementById('mapTemplateSelect');
            const columnSelect = document.getElementById('mapColumnSelect');
            const wilayahSelect = document.getElementById('mapWilayahSelect');
            const alertBox = document.getElementById('mapAlert');
            const alertText = document.getElementById('mapAlertText');

            // ===== Helper UI dasar =====
            function showAlert(message) {
                alertText.textContent = message;
                alertBox.classList.remove('hidden');
            }

            function hideAlert() {
                alertBox.classList.add('hidden');
            }

            function resetSelect(select, placeholder) {
                select.innerHTML = `<option value="">${placeholder}</option>`;
                select.disabled = true;
            }

            // Koordinat default: pusatkan ke wilayah kelurahan aktif secara kasar.
            // Peta akan otomatis fitBounds() ke poligon begitu data pertama tampil.
            const map = L.map('map').setView([-5.481, 122.617], 14);

            // Basemap: OSM (default, jalan/kota) & Esri World Imagery (satelit) — keduanya gratis tanpa API key.
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            });
            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri',
                maxZoom: 19,
            });
            osmLayer.addTo(map);
            L.control.layers({ 'Peta Jalan': osmLayer, 'Satelit': satelliteLayer }, null, { position: 'topright' }).addTo(map);

            // ===== Daftar wilayah (RT/RW) — dipakai untuk "zoom to" setelah choropleth tampil =====
            fetch('{{ route('public.map.rt-rw-options') }}')
                .then(res => res.json())
                .then(options => {
                    if (options.length === 0) {
                        resetSelect(wilayahSelect, '-- Belum ada wilayah dengan peta --');
                        return;
                    }
                    wilayahSelect.innerHTML = '<option value="">-- Pilih Wilayah --</option>';
                    options.forEach(opt => {
                        const el = document.createElement('option');
                        el.value = `${opt.rt}|${opt.rw}`;
                        el.textContent = `RT ${opt.rt} / RW ${opt.rw}`;
                        wilayahSelect.appendChild(el);
                    });
                    wilayahSelect.disabled = false;
                })
                .catch(() => showAlert('Gagal memuat daftar wilayah. Silakan muat ulang halaman.'));

            // ===== Template berubah -> isi ulang dropdown Kolom =====
            templateSelect.addEventListener('change', function () {
                hideAlert();
                resetSelect(columnSelect, '-- Memuat kolom... --');

                if (!this.value) {
                    resetSelect(columnSelect, '-- Pilih Judul Tabel Dahulu --');
                    renderMapIfReady(); // balik ke base layer netral
                    return;
                }

                const url = '{{ route('public.map.columns', ['statistic_template' => '__ID__']) }}'
                    .replace('__ID__', this.value);

                fetch(url)
                    .then(res => res.json())
                    .then(columns => {
                        if (columns.length === 0) {
                            resetSelect(columnSelect, '-- Tabel ini belum punya kolom --');
                            return;
                        }
                        columnSelect.innerHTML = '<option value="">-- Pilih Kolom --</option>';
                        columns.forEach(col => {
                            const el = document.createElement('option');
                            el.value = col.id;
                            el.textContent = col.label;
                            columnSelect.appendChild(el);
                        });
                        columnSelect.disabled = false;
                    })
                    .catch(() => showAlert('Gagal memuat daftar kolom. Silakan coba lagi.'));
            });

            // ===== Peta: 1 lapisan aktif (mapLayer), bisa base netral ATAU choropleth berwarna =====
            let mapLayer = null;
            let baseCollection = null; // cache hasil base-geometries, supaya tidak fetch ulang tiap kali

            const CATEGORICAL_PALETTE = ['#2563eb', '#dc2626', '#059669', '#d97706', '#7c3aed', '#db2777', '#0891b2', '#65a30d'];
            const NEUTRAL_FILL = '#d1d5db';
            const NEUTRAL_STROKE = '#6b7280';

            function isNumericValue(v) {
                if (v === null || v === undefined || v === '') return false;
                return !isNaN(parseFloat(v)) && isFinite(v);
            }

            function numericColor(value, min, max) {
                if (!isNumericValue(value)) return NEUTRAL_FILL; // abu-abu = belum diisi
                const v = parseFloat(value);
                const t = min === max ? 1 : (v - min) / (max - min); // 0=rendah, 1=tinggi
                const lightness = 85 - (t * 60); // terang (85%) -> gelap (25%)
                return `hsl(158, 64%, ${lightness}%)`;
            }

            function buildCategoryColorMap(values) {
                const unique = [...new Set(values.filter(v => v !== null && v !== undefined && v !== ''))].sort();
                const map = {};
                unique.forEach((v, i) => { map[v] = CATEGORICAL_PALETTE[i % CATEGORICAL_PALETTE.length]; });
                return map;
            }

            function categoricalColor(value, map) {
                if (value === null || value === undefined || value === '') return NEUTRAL_FILL;
                return map[value] || '#9ca3af';
            }

            function clearLegend() {
                document.getElementById('mapLegend').innerHTML = '';
            }

            function renderLegend(mode, min, max, categoryColorMap) {
                const el = document.getElementById('mapLegend');
                if (mode === 'numeric') {
                    el.innerHTML = `<div class="flex items-center gap-2 text-sm">
                        <span>${min}</span>
                        <div class="h-3 w-40 rounded" style="background: linear-gradient(to right, hsl(158,64%,85%), hsl(158,64%,25%));"></div>
                        <span>${max}</span>
                        <span class="text-base-content/60 ml-2">(terang = rendah, gelap = tinggi)</span>
                    </div>`;
                } else {
                    const items = Object.entries(categoryColorMap).map(([label, color]) =>
                        `<span class="inline-flex items-center gap-1 mr-3 text-sm">
                            <span class="inline-block w-3 h-3 rounded-sm" style="background:${color}"></span>${label}
                        </span>`
                    ).join('');
                    el.innerHTML = `<div class="flex flex-wrap items-center">${items}</div>`;
                }
            }

            // Popup: nama kelurahan -> kecamatan -> RW/RT -> <hr> -> kolom & nilai (atau pesan netral)
            function buildPopupHtml(p, hasColumnData) {
                const dataSection = hasColumnData
                    ? `${p.column_label}: <strong>${p.value ?? 'Belum diisi'}</strong>`
                    : `<span class="text-base-content/50 italic text-xs">Pilih Tabel & Kolom untuk melihat data statistik</span>`;

                return `
                    <div class="text-sm">
                        <strong>${p.kelurahan ?? '-'}</strong><br>
                        ${p.kecamatan ? p.kecamatan + '<br>' : ''}
                        ${p.rw_label ?? ''} / ${p.rt_label ?? ''}
                        <hr>
                        ${dataSection}
                    </div>
                `;
            }

            function attachHoverHighlight(layer) {
                layer.on('mouseover', function () { layer.setStyle({ weight: 3 }); layer.bringToFront(); });
                layer.on('mouseout', function () { mapLayer.resetStyle(layer); });
            }

            // ===== Base layer: SEMUA RT/RW, warna netral, tampil sejak halaman dibuka =====
            function renderBaseLayer(collection) {
                if (mapLayer) { map.removeLayer(mapLayer); mapLayer = null; }
                clearLegend();

                const features = collection.features || [];
                if (features.length === 0) return;

                mapLayer = L.geoJSON(collection, {
                    style: { color: NEUTRAL_STROKE, weight: 1, fillColor: NEUTRAL_FILL, fillOpacity: 0.5 },
                    onEachFeature: function (feature, layer) {
                        layer.bindPopup(buildPopupHtml(feature.properties, false));
                        attachHoverHighlight(layer);
                    },
                }).addTo(map);

                if (mapLayer.getBounds().isValid()) {
                    map.fitBounds(mapLayer.getBounds());
                }
            }

            // ===== Choropleth: SEMUA RT/RW, warna sesuai nilai kolom terpilih =====
            function renderChoropleth(collection) {
                if (mapLayer) { map.removeLayer(mapLayer); mapLayer = null; }
                clearLegend();

                const features = collection.features || [];
                if (features.length === 0) {
                    showAlert('Belum ada RT/RW dengan poligon untuk kombinasi Tabel/Kolom ini.');
                    if (baseCollection) renderBaseLayer(baseCollection); // tetap tampilkan base, jangan blank
                    return;
                }
                hideAlert();

                const values = features.map(f => f.properties.value);
                const declaredType = collection.meta && collection.meta.data_type;
                const allNumeric = values.filter(v => v !== null && v !== '').every(isNumericValue);
                const mode = declaredType === 'numeric' ? 'numeric'
                    : declaredType === 'text' ? 'categorical'
                    : (allNumeric ? 'numeric' : 'categorical'); // fallback utk data_type null/'both'

                let min = 0, max = 0, categoryColorMap = {};
                if (mode === 'numeric') {
                    const nums = values.filter(isNumericValue).map(parseFloat);
                    min = nums.length ? Math.min(...nums) : 0;
                    max = nums.length ? Math.max(...nums) : 0;
                } else {
                    categoryColorMap = buildCategoryColorMap(values);
                }

                mapLayer = L.geoJSON(collection, {
                    style: function (feature) {
                        const v = feature.properties.value;
                        const fillColor = mode === 'numeric' ? numericColor(v, min, max) : categoricalColor(v, categoryColorMap);
                        return { color: '#1f2937', weight: 1, fillColor, fillOpacity: 0.75 };
                    },
                    onEachFeature: function (feature, layer) {
                        layer.bindPopup(buildPopupHtml(feature.properties, true));
                        attachHoverHighlight(layer);
                    },
                }).addTo(map);

                if (mapLayer.getBounds().isValid()) {
                    map.fitBounds(mapLayer.getBounds());
                }
                renderLegend(mode, min, max, categoryColorMap);
            }

            // Muat base layer SEKALI saat halaman dibuka — ini yang membuat peta tidak kosong
            // sebelum Tabel/Kolom dipilih.
            fetch('{{ route('public.map.base-geometries') }}')
                .then(res => res.json())
                .then(collection => {
                    baseCollection = collection;
                    renderBaseLayer(collection);
                })
                .catch(() => {
                    // Base layer sifatnya pelengkap tampilan awal — kalau gagal, tidak perlu
                    // showAlert mengganggu; peta tetap bisa dipakai begitu Tabel/Kolom dipilih.
                });

            // Template/Kolom berubah -> render ulang peta (Wilayah TIDAK jadi syarat)
            [templateSelect, columnSelect].forEach(select => select.addEventListener('change', renderMapIfReady));

            function renderMapIfReady() {
                const templateId = templateSelect.value;
                const columnId = columnSelect.value;

                // Belum lengkap (atau baru direset) -> tampilkan base layer netral, bukan kosong.
                if (!templateId || !columnId) {
                    hideAlert();
                    if (baseCollection) renderBaseLayer(baseCollection);
                    return;
                }

                hideAlert();
                const params = new URLSearchParams({ template_id: templateId, column_id: columnId });

                fetch(`{{ route('public.map.data-all') }}?${params.toString()}`)
                    .then(res => { if (!res.ok) throw new Error('failed'); return res.json(); })
                    .then(renderChoropleth)
                    .catch(() => {
                        if (baseCollection) renderBaseLayer(baseCollection); // fallback, jangan blank
                        showAlert('Gagal memuat data peta. Silakan coba lagi.');
                    });
            }

            // Wilayah cuma "zoom to" — poligon lain tetap tampil, tidak trigger fetch baru
            wilayahSelect.addEventListener('change', function () {
                if (!this.value || !mapLayer) return;
                const [rt, rw] = this.value.split('|');
                let target = null;
                mapLayer.eachLayer(function (layer) {
                    if (String(layer.feature.properties.rt) === rt && String(layer.feature.properties.rw) === rw) {
                        target = layer;
                    }
                });
                if (target) { map.fitBounds(target.getBounds()); target.openPopup(); }
            });
        });
    </script>
</x-layout>