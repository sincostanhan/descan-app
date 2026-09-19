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

        <div class="alert alert-info shadow-sm mb-4">
            <x-lucide-info class="w-5 h-5" />
            <span>Saat ini ada <strong>{{ $existingCount }}</strong> poligon RT/RW tersimpan. Import bersifat <strong>semua-atau-tidak-sama-sekali</strong> — kalau ada 1 Kelurahan yang tidak cocok, seluruh import dibatalkan.</span>
        </div>

        <form action="{{ route('admin-bps.region-geometries.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                <x-section-card title="Preview Peta" title-size="text-lg">
                    <div id="geoPreviewMap" class="w-full rounded-box border border-base-200" style="height: 600px; position: relative; overflow: hidden; z-index: 0;"></div>
                    <button type="button" id="btnRenderPreview" class="btn btn-outline btn-sm mt-3">
                        <x-lucide-refresh-cw class="w-4 h-4 mr-1" /> Render Ulang Preview
                    </button>
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
                        <div class="overflow-x-auto rounded-box border border-base-200 mb-3" style="max-height: 480px; overflow-y: auto;">
                            <table class="table table-sm table-pin-rows w-full">
                                <thead class="bg-base-200/70">
                                    <tr>
                                        <th class="w-40">Kelurahan</th>
                                        <th class="w-20">RW</th>
                                        <th class="w-20">RT</th>
                                        <th>Koordinat Ring (array [lng,lat])</th>
                                        <th class="w-16"></th>
                                    </tr>
                                </thead>
                                <tbody id="rowsTableBody"></tbody>
                            </table>
                        </div>
                        <button type="button" id="btnAddRow" class="btn btn-outline btn-sm">
                            <x-lucide-plus class="w-4 h-4 mr-1" /> Tambah Baris RT
                        </button>
                        <p class="text-xs text-base-content/50 italic mt-2">
                            Kolom Koordinat diisi array [longitude, latitude] untuk 1 ring polygon, contoh:
                            [[122.6107, -5.4607], [122.6109, -5.4607], [122.6107, -5.4607]] (titik pertama & terakhir harus sama, poligon tertutup).
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

            const map = L.map('geoPreviewMap').setView([-5.481, 122.617], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

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

                try {
                    currentLayer = L.geoJSON(parsed, {
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

                rows.forEach(function (r) {
                    const tr = document.createElement('tr');
                    tr.dataset.rowId = r.id;

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
                        if (name === r.kelurahan) opt.selected = true;
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

                    // Kolom Aksi
                    const tdAction = document.createElement('td');
                    const btnDelete = document.createElement('button');
                    btnDelete.type = 'button';
                    btnDelete.className = 'btn btn-error btn-xs row-delete';
                    btnDelete.textContent = 'Hapus';
                    tdAction.appendChild(btnDelete);

                    tr.append(tdKel, tdRw, tdRt, tdRing, tdAction);
                    rowsTableBody.appendChild(tr);
                });
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
                if (!e.target.classList.contains('row-delete')) return;
                const tr = e.target.closest('tr');
                rows = rows.filter(function (r) { return r.id !== tr.dataset.rowId; });
                renderTable();
                rebuildTextareaFromRows();
            });

            btnAddRow.addEventListener('click', function () {
                rows.push({ id: 'row-' + (rowIdCounter++), kelurahan: '', rw: '', rt: '', ringText: '' });
                renderTable();
                // Baris baru masih kosong -> tidak ikut masuk textarea sampai diisi, tidak perlu rebuild.
            });

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
        });
    </script>
</x-layout-admin-bps>