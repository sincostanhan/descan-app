<x-layout-admin-bps title="Kelola Peta Wilayah">
    <x-hero
        title="Kelola Peta Wilayah"
        subtitle="Tempel/edit GeoJSON gabungan, atau isi per baris RT lewat tab Tabel. Sistem otomatis mencocokkan tiap feature ke Kelurahan lewat properti NAMA_KELURAHAN."
    />

    <div class="max-w-7xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        @if($errors->has('geojson'))
            <div class="alert alert-error shadow-sm mb-4">
                <x-lucide-alert-triangle class="w-5 h-5 shrink-0" />
                <div class="text-sm">
                    <p class="font-semibold mb-1">Import ditolak — perbaiki dulu semua error berikut:</p>
                    <ul class="list-disc list-inside space-y-0.5 max-h-48 overflow-y-auto">
                        @foreach($errors->get('geojson') as $messages)
                            @foreach((array) $messages as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="alert alert-warning shadow-sm mb-4">
            <x-lucide-alert-triangle class="w-5 h-5" />
            <span>Saat ini ada <strong>{{ $existingCount }}</strong> poligon RT/RW tersimpan. <strong>Import di sini adalah SINKRONISASI PENUH</strong> — data yang kamu submit akan MENGGANTI SELURUH data lama (baris yang kamu hapus dari sini akan ikut terhapus di database). Import juga tetap <strong>semua-atau-tidak-sama-sekali</strong>: kalau ada 1 Kelurahan yang tidak cocok, seluruh proses dibatalkan (data lama tidak tersentuh).</span>
        </div>

        <div class="mb-4">
            <label class="label" for="filterKelurahan">
                <span class="label-text font-medium">Filter Kelurahan</span>
            </label>
            <select id="filterKelurahan" class="select select-sm w-full max-w-xs">
                <option value="">-- Semua Kelurahan --</option>
                @foreach($villages as $v)
                    <option value="{{ $v->name }}">{{ $v->name }}</option>
                @endforeach
            </select>
            <span id="filterCount" class="text-xs text-base-content/50 ml-2"></span>
        </div>

        <form id="importForm" action="{{ route('admin-bps.region-geometries.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                <x-section-card title="Preview Peta" title-size="text-lg">
                    <div class="relative">
                        <div id="geoPreviewMap" class="w-full rounded-box border border-base-200" style="height: 600px; position: relative; overflow: hidden; z-index: 0;"></div>
                        <div id="drawControls" class="hidden" style="position: absolute; top: 10px; right: 10px; z-index: 10; background: white; padding: 10px; border-radius: 0.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.25);">
                            <div class="text-xs mb-2">Titik: <span id="drawPointCount">0</span></div>
                            <button type="button" id="btnDrawFinish" class="btn btn-success btn-xs w-full mb-1">Selesai</button>
                            <button type="button" id="btnDrawCancel" class="btn btn-ghost btn-xs w-full">Batal</button>
                        </div>
                    </div>
                    <div id="drawHint" class="alert alert-info shadow-sm mt-3 hidden">
                        <x-lucide-info class="w-4 h-4" />
                        <span id="drawHintText">Mode gambar aktif — klik di peta untuk menambah titik polygon (minimal 3 titik), lalu klik "Selesai".</span>
                    </div>
                    <div class="flex items-center gap-2 mt-3">
                        <button type="button" id="btnRenderPreview" class="btn btn-outline btn-sm">
                            <x-lucide-refresh-cw class="w-4 h-4 mr-1" /> Render Ulang Preview
                        </button>
                        <span id="filterActiveBadge" class="badge badge-warning gap-1 hidden">
                            <x-lucide-filter class="w-3 h-3" />
                            <span id="filterActiveBadgeText"></span>
                        </span>
                    </div>
                    <div id="previewError" class="alert alert-warning shadow-sm mt-3 hidden">
                        <x-lucide-info class="w-4 h-4" />
                        <span id="previewErrorText" class="text-sm"></span>
                    </div>
                    <style>
                        /* Browser (Chrome/Firefox) menggambar focus outline BAWAAN sebagai bounding-box
                           persegi saat elemen SVG path (polygon) di-klik/fokus — karena browser tidak tahu
                           cara menggambar outline mengikuti bentuk asli polygon. Matikan di sini. */
                        .leaflet-interactive:focus {
                            outline: none;
                        }
                        /* Paksa panel Leaflet (tiles, kontrol zoom, dsb) tetap terkurung di dalam
                           #geoPreviewMap — jangan andalkan .leaflet-container dari leaflet.css saja,
                           karena bisa keserobot timing Tailwind CDN yang inject CSS-nya belakangan. */
                        #geoPreviewMap .leaflet-pane,
                        #geoPreviewMap .leaflet-control-container {
                            z-index: 1;
                        }
                    </style>
                </x-section-card>

                <x-section-card title="Data GeoJSON" title-size="text-lg">

                    <div role="tablist" class="tabs tabs-boxed mb-4">
                        <a role="tab" id="tabTextBtn" class="tab tab-active">GeoJSON (Teks)</a>
                        <a role="tab" id="tabTableBtn" class="tab">Tabel per RT</a>
                    </div>

                    <div id="tabTextPane">
                        <textarea
                            name="geojson"
                            id="geojsonTextarea"
                            rows="24"
                            class="textarea w-full font-mono text-xs"
                            spellcheck="false"
                        >{{ old('geojson', $geojsonText) }}</textarea>
                        <x-forms.error name="geojson" />
                    </div>

                    <div id="tabTablePane" class="hidden">
                        <div class="flex justify-between items-center mb-2">
                            <button type="button" id="btnAddRow" class="btn btn-outline btn-sm">
                                <x-lucide-plus class="w-4 h-4 mr-1" /> Tambah Baris RT
                            </button>
                            <button type="button" id="btnToggleFullscreen" class="btn btn-outline btn-sm">
                                <x-lucide-maximize id="iconMaximize" class="w-4 h-4 mr-1" />
                                <x-lucide-minimize id="iconMinimize" class="w-4 h-4 mr-1 hidden" />
                                <span id="fullscreenBtnText">Full Screen</span>
                            </button>
                        </div>
                        <div id="tableWrapper" class="overflow-x-auto rounded-box border border-base-200 mb-3" style="max-height: 480px; overflow-y: auto;">
                            <table class="table table-sm table-pin-rows table-fixed" id="rowsTable" style="min-width: 900px;">
                                <thead class="bg-base-200/70">
                                    <tr>
                                        <th class="w-10"></th>
                                        <th class="w-56 resizable-col relative" data-col-key="kelurahan">Kelurahan<span class="col-resize-handle"></span></th>
                                        <th class="w-16 resizable-col relative" data-col-key="rw">RW<span class="col-resize-handle"></span></th>
                                        <th class="w-16 resizable-col relative" data-col-key="rt">RT<span class="col-resize-handle"></span></th>
                                        <th class="w-64">Koordinat Ring (array [lng,lat])</th>
                                        <th class="w-28 whitespace-nowrap">Gambar</th>
                                        <th class="w-20 whitespace-nowrap">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody id="rowsTableBody"></tbody>
                            </table>
                        </div>
                        <style>
                            .col-resize-handle {
                                position: absolute;
                                top: 0;
                                right: 0;
                                width: 6px;
                                height: 100%;
                                cursor: col-resize;
                                user-select: none;
                            }
                            .col-resize-handle:hover,
                            .col-resize-handle.resizing {
                                background: rgba(0, 0, 0, 0.15);
                            }
                        </style>
                        <p class="text-xs text-base-content/50 italic mt-2">
                            Kolom Koordinat diisi array [longitude, latitude] untuk 1 ring polygon, contoh:
                            [[122.6107, -5.4607], [122.6109, -5.4607], [122.6107, -5.4607]] (titik pertama & terakhir harus sama, poligon tertutup).
                            Centang kotak di kolom paling kiri untuk geser titik polygon baris itu langsung di peta.
                        </p>
                    </div>

                </x-section-card>
            </div>

            <div class="card-actions justify-end">
                <button type="submit" class="btn btn-secondary">
                    <x-lucide-upload class="w-5 h-5 mr-1" /> Import & Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Leaflet via CDN, konsisten dengan pola Chart.js/pdf.js yang sudah ada di proyek ini. --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const villages = @json($villages->pluck('name'));

            const textarea = document.getElementById('geojsonTextarea');
            const errorBox = document.getElementById('previewError');
            const errorText = document.getElementById('previewErrorText');

            const tabTextBtn = document.getElementById('tabTextBtn');
            const tabTableBtn = document.getElementById('tabTableBtn');
            const tabTextPane = document.getElementById('tabTextPane');
            const tabTablePane = document.getElementById('tabTablePane');
            const rowsTableBody = document.getElementById('rowsTableBody');
            const btnAddRow = document.getElementById('btnAddRow');
            const filterSelect = document.getElementById('filterKelurahan');
            const filterCount = document.getElementById('filterCount');
            let activeFilter = '';

            // Ikon kustom (gaya Lucide) untuk kolom pilih baris — dipakai lewat innerHTML karena
            // baris tabel di-generate murni via JS, tidak bisa panggil komponen Blade dari sini.
            const ICON_SQUARE = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/></svg>';
            const ICON_SQUARE_CHECK = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 12 2 2 4-4"/></svg>';
            let selectedRowId = null;

            const map = L.map('geoPreviewMap').setView([-5.481, 122.617], 13);

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

            let currentLayer = null;
            let selectedFeatureLayer = null;
            const defaultStyle = { color: '#059669', weight: 1, fillOpacity: 0.25 };
            const selectedStyle = { color: '#dc2626', weight: 3, fillOpacity: 0.45 };

            // ===== State tabel (sumber data saat tab Tabel aktif) =====
            let rows = [];
            let rowIdCounter = 0;

            function showPreviewError(message) {
                errorText.textContent = message;
                errorBox.classList.remove('hidden');
            }

            function hidePreviewError() {
                errorBox.classList.add('hidden');
            }

            // ===== Render peta dari isi textarea saat ini =====
            function renderPreview() {
                hidePreviewError();

                let parsed;
                try {
                    parsed = JSON.parse(textarea.value);
                } catch (e) {
                    showPreviewError('GeoJSON tidak valid: ' + e.message);
                    return;
                }

                if (currentLayer) {
                    map.removeLayer(currentLayer);
                    currentLayer = null;
                }
                selectedFeatureLayer = null;

                let featuresToRender = Array.isArray(parsed.features) ? parsed.features : [];
                if (activeFilter) {
                    featuresToRender = featuresToRender.filter(function (f) {
                        const nama = (f.properties && f.properties.NAMA_KELURAHAN) || '';
                        return String(nama).toLowerCase() === activeFilter.toLowerCase();
                    });
                }
                const filteredCollection = { type: 'FeatureCollection', features: featuresToRender };

                try {
                    currentLayer = L.geoJSON(filteredCollection, {
                        style: defaultStyle,
                        onEachFeature: function (feature, layer) {
                            const p = feature.properties || {};
                            layer.bindTooltip(`${p.NAMA_KELURAHAN ?? '-'} / ${p.NAMA_RW ?? '-'} / ${p.NAMA_RT ?? '-'}`);

                            layer.on('click', function () {
                                if (selectedFeatureLayer && selectedFeatureLayer !== layer) {
                                    selectedFeatureLayer.setStyle(defaultStyle);
                                }
                                layer.setStyle(selectedStyle);
                                layer.bringToFront();
                                selectedFeatureLayer = layer;
                            });
                        },
                    }).addTo(map);

                    if (currentLayer.getBounds().isValid()) {
                        map.fitBounds(currentLayer.getBounds());
                    }
                } catch (e) {
                    showPreviewError('Gagal render sebagai GeoJSON: ' + e.message);
                }
            }

            document.getElementById('btnRenderPreview').addEventListener('click', renderPreview);
            renderPreview(); // render pertama kali dari data awal server

            // ===== Konversi: teks GeoJSON -> array rows (dipakai saat pindah ke tab Tabel) =====
            function parseTextareaToRows() {
                let parsed;
                try {
                    parsed = JSON.parse(textarea.value);
                } catch (e) {
                    return { ok: false, error: 'GeoJSON tidak valid: ' + e.message };
                }
                if (parsed.type !== 'FeatureCollection' || !Array.isArray(parsed.features)) {
                    return { ok: false, error: 'Struktur harus FeatureCollection dengan array features.' };
                }

                const newRows = parsed.features.map(function (f) {
                    const props = f.properties || {};
                    const rwMatch = String(props.NAMA_RW || '').match(/(\d+)/);
                    const rtMatch = String(props.NAMA_RT || '').match(/(\d+)/);
                    let ringText = '';
                    try {
                        const ring = (f.geometry && f.geometry.coordinates && f.geometry.coordinates[0]) || [];
                        ringText = JSON.stringify(ring);
                    } catch (e) {
                        ringText = '';
                    }
                    return {
                        id: 'row-' + (rowIdCounter++),
                        kelurahan: props.NAMA_KELURAHAN || '',
                        rw: rwMatch ? rwMatch[1] : '',
                        rt: rtMatch ? rtMatch[1] : '',
                        ringText: ringText,
                    };
                });

                return { ok: true, rows: newRows };
            }

            // ===== Konversi: array rows -> teks GeoJSON (dipanggil tiap kali tabel diedit) =====
            function rebuildTextareaFromRows() {
                const features = [];

                rows.forEach(function (r) {
                    if (!r.kelurahan || !r.rw || !r.rt || !r.ringText.trim()) {
                        return; // baris belum lengkap, jangan diikutkan dulu (belum tentu error, mungkin baru ditambah)
                    }
                    let ring;
                    try {
                        ring = JSON.parse(r.ringText);
                        if (!Array.isArray(ring)) throw new Error('bukan array');
                    } catch (e) {
                        return; // koordinat baris ini belum valid, skip sementara (jangan gagalkan baris lain)
                    }
                    const rwPadded = String(r.rw).padStart(3, '0');
                    const rtPadded = String(r.rt).padStart(3, '0');
                    features.push({
                        type: 'Feature',
                        properties: {
                            join_key: `${r.kelurahan}-RW${rwPadded}-RT${rtPadded}`,
                            NAMA_KELURAHAN: r.kelurahan,
                            NAMA_RW: `RW ${rwPadded}`,
                            NAMA_RT: `RT ${rtPadded}`,
                        },
                        geometry: { type: 'Polygon', coordinates: [ring] },
                    });
                });

                textarea.value = JSON.stringify({ type: 'FeatureCollection', features: features }, null, 2);
            }

            // ===== Render tabel dari state `rows` =====
            function renderTable() {
                rowsTableBody.innerHTML = '';

                let shownCount = 0;

                rows.forEach(function (r) {
                    if (activeFilter && String(r.kelurahan || '').toLowerCase() !== activeFilter.toLowerCase()) {
                        return; // disembunyikan oleh filter, TETAP ada di array `rows` & di textarea
                    }
                    shownCount++;

                    const tr = document.createElement('tr');
                    tr.dataset.rowId = r.id;

                    // Kolom pilih (checkbox kustom square/square-check) — centang = mode edit-vertex aktif
                    const tdSelect = document.createElement('td');
                    const btnSelect = document.createElement('button');
                    btnSelect.type = 'button';
                    btnSelect.className = 'row-select-toggle text-base-content/60 hover:text-primary';
                    btnSelect.title = 'Centang untuk geser titik polygon baris ini langsung di peta (edit bentuk yang sudah ada, bukan gambar dari nol)';
                    btnSelect.innerHTML = (selectedRowId === r.id) ? ICON_SQUARE_CHECK : ICON_SQUARE;
                    tdSelect.appendChild(btnSelect);

                    // Kolom Kelurahan (select)
                    const tdKel = document.createElement('td');
                    const selectKel = document.createElement('select');
                    selectKel.className = 'select select-sm w-full row-kelurahan';
                    const blankOpt = document.createElement('option');
                    blankOpt.value = '';
                    blankOpt.textContent = '-- Pilih --';
                    selectKel.appendChild(blankOpt);
                    villages.forEach(function (name) {
                        const opt = document.createElement('option');
                        opt.value = name;
                        opt.textContent = name;
                        // Case-insensitive: data lama tersimpan uppercase ("BATARAGURU") sementara
                        // villages.name di database bisa campuran ("Bataraguru") — samakan dulu.
                        if (String(name).toLowerCase() === String(r.kelurahan).toLowerCase()) {
                            opt.selected = true;
                        }
                        selectKel.appendChild(opt);
                    });
                    tdKel.appendChild(selectKel);

                    // Kolom RW
                    const tdRw = document.createElement('td');
                    const inputRw = document.createElement('input');
                    inputRw.type = 'number';
                    inputRw.min = '1';
                    inputRw.className = 'input input-sm w-16 row-rw';
                    inputRw.value = r.rw;
                    tdRw.appendChild(inputRw);

                    // Kolom RT
                    const tdRt = document.createElement('td');
                    const inputRt = document.createElement('input');
                    inputRt.type = 'number';
                    inputRt.min = '1';
                    inputRt.className = 'input input-sm w-16 row-rt';
                    inputRt.value = r.rt;
                    tdRt.appendChild(inputRt);

                    // Kolom Koordinat
                    const tdRing = document.createElement('td');
                    const textareaRing = document.createElement('textarea');
                    textareaRing.rows = 3;
                    textareaRing.className = 'textarea textarea-sm w-full font-mono text-xs row-ring';
                    textareaRing.value = r.ringText;
                    tdRing.appendChild(textareaRing);

                    // Kolom Aksi Gambar
                    const tdDraw = document.createElement('td');
                    const btnDraw = document.createElement('button');
                    btnDraw.type = 'button';
                    btnDraw.className = 'btn btn-primary btn-xs row-draw whitespace-nowrap';
                    btnDraw.textContent = 'Gambar Baru';
                    btnDraw.title = 'Gambar polygon BARU dari awal di peta — akan MENGGANTI koordinat yang sudah ada di baris ini. Untuk perbaiki bentuk yang sudah ada tanpa mengulang dari nol, pakai checkbox di kolom paling kiri.';
                    tdDraw.appendChild(btnDraw);

                    // Kolom Aksi Hapus
                    const tdAction = document.createElement('td');
                    const btnDelete = document.createElement('button');
                    btnDelete.type = 'button';
                    btnDelete.className = 'btn btn-error btn-xs row-delete';
                    btnDelete.textContent = 'Hapus';
                    tdAction.appendChild(btnDelete);

                    tr.append(tdSelect, tdKel, tdRw, tdRt, tdRing, tdDraw, tdAction);
                    rowsTableBody.appendChild(tr);
                });

                filterCount.textContent = activeFilter
                    ? `Menampilkan ${shownCount} dari ${rows.length} baris (Kelurahan lain disembunyikan, tidak dihapus)`
                    : '';
            }

            // Delegasi event: perubahan input di dalam tabel -> update state `rows` + textarea live
            rowsTableBody.addEventListener('input', handleRowFieldChange);
            rowsTableBody.addEventListener('change', handleRowFieldChange);

            function handleRowFieldChange(e) {
                const tr = e.target.closest('tr');
                if (!tr) return;
                const row = rows.find(function (r) { return r.id === tr.dataset.rowId; });
                if (!row) return;

                if (e.target.classList.contains('row-kelurahan')) row.kelurahan = e.target.value;
                if (e.target.classList.contains('row-rw')) row.rw = e.target.value;
                if (e.target.classList.contains('row-rt')) row.rt = e.target.value;
                if (e.target.classList.contains('row-ring')) row.ringText = e.target.value;

                rebuildTextareaFromRows();
            }

            rowsTableBody.addEventListener('click', function (e) {
                if (e.target.classList.contains('row-delete')) {
                    const tr = e.target.closest('tr');
                    if (selectedRowId === tr.dataset.rowId) {
                        selectedRowId = null;
                        endEditMode();
                    }
                    rows = rows.filter(function (r) { return r.id !== tr.dataset.rowId; });
                    renderTable();
                    rebuildTextareaFromRows();
                    return;
                }
                if (e.target.classList.contains('row-draw')) {
                    const tr = e.target.closest('tr');
                    const row = rows.find(function (r) { return r.id === tr.dataset.rowId; });
                    if (row) startDrawMode(row);
                    return;
                }
                const selectBtn = e.target.closest('.row-select-toggle');
                if (selectBtn) {
                    const tr = selectBtn.closest('tr');
                    const row = rows.find(function (r) { return r.id === tr.dataset.rowId; });
                    if (!row) return;

                    if (selectedRowId === row.id) {
                        selectedRowId = null;
                        endEditMode();
                    } else {
                        selectedRowId = row.id;
                        startEditMode(row);
                    }
                    renderTable();
                }
            });

            // ===== Mode gambar polygon / edit-vertex langsung di peta (saling eksklusif) =====
            let currentMode = null; // 'draw' | 'edit' | null
            let drawingRow = null;
            let drawPoints = [];
            let drawPreviewLayer = null;
            let drawMarkers = [];

            let editingRow = null;
            let editingRowPoints = null;
            let editPolygonLayer = null;
            let editMarkers = [];

            function startDrawMode(row) {
                if (currentMode === 'draw') endDrawMode();
                if (currentMode === 'edit') { endEditMode(); selectedRowId = null; renderTable(); }

                drawingRow = row;
                drawPoints = [];
                drawPreviewLayer = L.polyline([], { color: '#2563eb', weight: 3, dashArray: '6,4' }).addTo(map);
                drawMarkers = [];
                currentMode = 'draw';

                document.getElementById('drawControls').classList.remove('hidden');
                document.getElementById('drawHint').classList.remove('hidden');
                document.getElementById('drawHintText').textContent =
                    'Mode gambar aktif — klik di peta untuk menambah titik polygon (minimal 3 titik), lalu klik "Selesai".';
                document.getElementById('drawPointCount').textContent = '0';
                map.getContainer().style.cursor = 'crosshair';
                map.on('click', handleDrawClick);
            }

            function handleDrawClick(e) {
                // Simpan sebagai [lng, lat] — konvensi GeoJSON, sama seperti format ring di kolom Koordinat.
                drawPoints.push([e.latlng.lng, e.latlng.lat]);
                drawPreviewLayer.setLatLngs(drawPoints.map(function (p) { return [p[1], p[0]]; })); // Leaflet pakai [lat,lng]

                const marker = L.circleMarker(e.latlng, {
                    radius: 4, color: '#2563eb', fillColor: '#2563eb', fillOpacity: 1,
                }).addTo(map);
                drawMarkers.push(marker);

                document.getElementById('drawPointCount').textContent = drawPoints.length;
            }

            function finishDraw() {
                if (drawPoints.length < 3) {
                    alert('Minimal 3 titik untuk membentuk polygon. Tambah titik dulu, atau klik Batal.');
                    return;
                }
                const closedRing = drawPoints.concat([drawPoints[0]]); // tutup polygon: titik awal = titik akhir
                drawingRow.ringText = JSON.stringify(closedRing);

                endDrawMode();
                renderTable();
                rebuildTextareaFromRows();
                renderPreview();
            }

            function cancelDraw() {
                endDrawMode();
            }

            function endDrawMode() {
                map.off('click', handleDrawClick);
                map.getContainer().style.cursor = '';
                if (drawPreviewLayer) {
                    map.removeLayer(drawPreviewLayer);
                    drawPreviewLayer = null;
                }
                drawMarkers.forEach(function (m) { map.removeLayer(m); });
                drawMarkers = [];
                drawPoints = [];
                drawingRow = null;

                document.getElementById('drawControls').classList.add('hidden');
                document.getElementById('drawHint').classList.add('hidden');
                currentMode = null;
            }

            // ===== Mode edit-vertex: geser titik polygon yang SUDAH ADA lewat baris tercentang =====
            function startEditMode(row) {
                if (currentMode === 'draw') endDrawMode();
                if (currentMode === 'edit') endEditMode();

                let ring;
                try {
                    ring = JSON.parse(row.ringText);
                    if (!Array.isArray(ring) || ring.length < 3) throw new Error('kurang titik');
                } catch (e) {
                    alert('Baris ini belum punya koordinat yang valid untuk diedit. Gambar dulu lewat tombol "Gambar".');
                    selectedRowId = null;
                    renderTable();
                    return;
                }

                editingRow = row;
                // Buang titik penutup duplikat (titik terakhir == titik pertama) supaya tidak dobel marker.
                editingRowPoints = ring.slice(0, -1);

                editPolygonLayer = L.polygon(
                    editingRowPoints.map(function (p) { return [p[1], p[0]]; }),
                    { color: '#f59e0b', weight: 2, fillOpacity: 0.3 }
                ).addTo(map);

                editMarkers = editingRowPoints.map(function (p, idx) {
                    const marker = L.marker([p[1], p[0]], { draggable: true }).addTo(map);
                    marker.on('drag', function () {
                        const latlng = marker.getLatLng();
                        editingRowPoints[idx] = [latlng.lng, latlng.lat];
                        editPolygonLayer.setLatLngs(editingRowPoints.map(function (pp) { return [pp[1], pp[0]]; }));
                    });
                    return marker;
                });

                if (editPolygonLayer.getBounds().isValid()) {
                    map.fitBounds(editPolygonLayer.getBounds());
                }

                currentMode = 'edit';
                document.getElementById('drawControls').classList.remove('hidden');
                document.getElementById('drawHint').classList.remove('hidden');
                document.getElementById('drawHintText').textContent =
                    'Mode edit aktif — geser titik kuning di peta untuk ubah bentuk polygon, lalu klik "Selesai".';
                document.getElementById('drawPointCount').textContent = editingRowPoints.length;
            }

            function finishEdit() {
                if (!editingRow || !editingRowPoints) return;
                const closedRing = editingRowPoints.concat([editingRowPoints[0]]);
                editingRow.ringText = JSON.stringify(closedRing);

                endEditMode();
                selectedRowId = null;
                renderTable();
                rebuildTextareaFromRows();
                renderPreview();
            }

            function cancelEdit() {
                endEditMode();
                selectedRowId = null;
                renderTable();
            }

            function endEditMode() {
                if (editPolygonLayer) { map.removeLayer(editPolygonLayer); editPolygonLayer = null; }
                editMarkers.forEach(function (m) { map.removeLayer(m); });
                editMarkers = [];
                editingRow = null;
                editingRowPoints = null;

                document.getElementById('drawControls').classList.add('hidden');
                document.getElementById('drawHint').classList.add('hidden');
                currentMode = null;
            }

            document.getElementById('btnDrawFinish').addEventListener('click', function () {
                if (currentMode === 'draw') finishDraw();
                else if (currentMode === 'edit') finishEdit();
            });
            document.getElementById('btnDrawCancel').addEventListener('click', function () {
                if (currentMode === 'draw') cancelDraw();
                else if (currentMode === 'edit') cancelEdit();
            });

            btnAddRow.addEventListener('click', function () {
                rows.push({ id: 'row-' + (rowIdCounter++), kelurahan: activeFilter || '', rw: '', rt: '', ringText: '' });
                renderTable();
                // Baris baru masih kosong -> tidak ikut masuk textarea sampai diisi, tidak perlu rebuild.
            });

            filterSelect.addEventListener('change', function () {
                activeFilter = this.value;
                renderTable();
                renderPreview();
                updateFilterBadge();
            });

            function updateFilterBadge() {
                const badge = document.getElementById('filterActiveBadge');
                const badgeText = document.getElementById('filterActiveBadgeText');
                if (activeFilter) {
                    badgeText.textContent = 'Filter aktif: ' + activeFilter;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }

            // ===== Tombol tab =====
            tabTableBtn.addEventListener('click', function () {
                const result = parseTextareaToRows();
                if (!result.ok) {
                    alert('Tidak bisa pindah ke tab Tabel — perbaiki dulu GeoJSON di tab Teks:\n' + result.error);
                    return;
                }
                rows = result.rows;
                renderTable();

                tabTextPane.classList.add('hidden');
                tabTablePane.classList.remove('hidden');
                tabTextBtn.classList.remove('tab-active');
                tabTableBtn.classList.add('tab-active');
            });

            tabTextBtn.addEventListener('click', function () {
                tabTablePane.classList.add('hidden');
                tabTextPane.classList.remove('hidden');
                tabTableBtn.classList.remove('tab-active');
                tabTextBtn.classList.add('tab-active');
                // Textarea sudah otomatis ter-update live tiap kali tabel diedit — tidak perlu rebuild di sini.
            });

            // ===== Full Screen untuk tab Tabel per RT =====
            const btnToggleFullscreen = document.getElementById('btnToggleFullscreen');
            const iconMaximize = document.getElementById('iconMaximize');
            const iconMinimize = document.getElementById('iconMinimize');
            const fullscreenBtnText = document.getElementById('fullscreenBtnText');
            const tableWrapper = document.getElementById('tableWrapper');

            btnToggleFullscreen.addEventListener('click', function () {
                const isNowFullscreen = tabTablePane.classList.toggle('fixed');
                tabTablePane.classList.toggle('inset-4', isNowFullscreen);
                tabTablePane.classList.toggle('z-[1200]', isNowFullscreen);
                tabTablePane.classList.toggle('bg-base-100', isNowFullscreen);
                tabTablePane.classList.toggle('p-6', isNowFullscreen);
                tabTablePane.classList.toggle('overflow-auto', isNowFullscreen);
                tabTablePane.classList.toggle('shadow-2xl', isNowFullscreen);
                tabTablePane.classList.toggle('rounded-box', isNowFullscreen);
                tabTablePane.classList.toggle('border', isNowFullscreen);
                tabTablePane.classList.toggle('border-base-300', isNowFullscreen);

                tableWrapper.style.maxHeight = isNowFullscreen ? 'calc(100vh - 14rem)' : '480px';

                iconMaximize.classList.toggle('hidden', isNowFullscreen);
                iconMinimize.classList.toggle('hidden', !isNowFullscreen);
                fullscreenBtnText.textContent = isNowFullscreen ? 'Tutup Full Screen' : 'Full Screen';
            });

            // ===== Resize kolom tabel (drag garis pembatas di header) =====
            // Lebar kolom di-persist ke localStorage: preferensi tampilan per-browser per-user,
            // bukan data penting — cukup diingat lokal, tidak perlu ke server.
            const COL_WIDTH_STORAGE_KEY = 'regionGeometries.tableColWidths.v1';

            function loadStoredWidths() {
                try {
                    return JSON.parse(localStorage.getItem(COL_WIDTH_STORAGE_KEY) || '{}');
                } catch (e) {
                    return {};
                }
            }

            function saveStoredWidths(widths) {
                try {
                    localStorage.setItem(COL_WIDTH_STORAGE_KEY, JSON.stringify(widths));
                } catch (e) {
                    // localStorage bisa penuh atau dimatikan user — silent fail, tidak mengganggu flow utama
                }
            }

            const storedWidths = loadStoredWidths();

            document.querySelectorAll('#rowsTable .resizable-col').forEach(function (th) {
                const colKey = th.dataset.colKey;

                // Restore lebar tersimpan (kalau ada) saat halaman pertama dibuka
                if (colKey && storedWidths[colKey]) {
                    th.style.width = storedWidths[colKey] + 'px';
                }

                const handle = th.querySelector('.col-resize-handle');
                let startX = 0;
                let startWidth = 0;

                handle.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    startX = e.clientX;
                    startWidth = th.offsetWidth;
                    handle.classList.add('resizing');
                    document.addEventListener('mousemove', onResizeMove);
                    document.addEventListener('mouseup', onResizeEnd);
                });

                function onResizeMove(e) {
                    const newWidth = Math.max(48, startWidth + (e.clientX - startX)); // minimal 48px
                    th.style.width = newWidth + 'px';
                }

                function onResizeEnd() {
                    handle.classList.remove('resizing');
                    document.removeEventListener('mousemove', onResizeMove);
                    document.removeEventListener('mouseup', onResizeEnd);

                    // Simpan lebar akhir setelah drag selesai (bukan setiap mousemove, biar tidak berisik)
                    if (colKey) {
                        const widths = loadStoredWidths();
                        widths[colKey] = th.offsetWidth;
                        saveStoredWidths(widths);
                    }
                }
            });

            // ===== Konfirmasi sebelum submit — Import bersifat full-sync (bisa menghapus data lama) =====
            document.getElementById('importForm').addEventListener('submit', function (e) {
                let featureCount = 0;
                try {
                    const parsed = JSON.parse(textarea.value);
                    featureCount = Array.isArray(parsed.features) ? parsed.features.length : 0;
                } catch (err) {
                    // Biarkan validasi server yang menangani JSON tidak valid, jangan blokir submit di sini.
                }

                const confirmed = confirm(
                    `Import ini akan MENGGANTI SELURUH data poligon tersimpan (saat ini {{ $existingCount }} poligon) ` +
                    `dengan ${featureCount} feature yang ada di form ini sekarang. Baris yang tidak ada di sini akan DIHAPUS. Lanjutkan?`
                );
                if (!confirmed) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-layout-admin-bps>