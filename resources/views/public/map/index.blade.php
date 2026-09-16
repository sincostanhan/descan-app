<x-layout title="Dashboard Peta Statistik">
    <x-hero
        title="Dashboard Peta Statistik"
        subtitle="Pilih tabel, kolom, dan wilayah untuk melihat data statistik pada peta"
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
                    <legend class="fieldset-legend">Wilayah (RT/RW)</legend>
                    <select id="mapWilayahSelect" class="select w-full" disabled>
                        <option value="">-- Memuat daftar wilayah... --</option>
                    </select>
                </fieldset>
            </div>

            <div id="mapAlert" class="alert alert-warning shadow-sm mb-4 hidden">
                <x-lucide-info class="w-5 h-5" />
                <span id="mapAlertText"></span>
            </div>

            <div id="map" class="w-full rounded-box border border-base-200" style="height: 500px;"></div>

        </x-section-card>
    </div>

    {{-- Leaflet dimuat via CDN, konsisten dengan pola Chart.js (statistic/show.blade.php) & pdf.js
         (admin/infographic/create.blade.php) — proyek ini tidak memakai bundel Vite di runtime. --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const templateSelect = document.getElementById('mapTemplateSelect');
            const columnSelect = document.getElementById('mapColumnSelect');
            const wilayahSelect = document.getElementById('mapWilayahSelect');
            const alertBox = document.getElementById('mapAlert');
            const alertText = document.getElementById('mapAlertText');

            // Koordinat default: pusatkan ke wilayah kelurahan aktif secara kasar.
            // Peta akan otomatis fitBounds() ke poligon begitu data pertama tampil.
            const map = L.map('map').setView([-5.481, 122.617], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            let activeLayer = null;

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

            // Daftar wilayah (RT/RW) diambil sekali saat halaman dimuat — sumbernya region_geometries
            // (BUKAN organizations.daftar_rt), supaya hanya menampilkan RT/RW yang sudah punya poligon.
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

            templateSelect.addEventListener('change', function () {
                hideAlert();
                resetSelect(columnSelect, '-- Memuat kolom... --');

                if (!this.value) {
                    resetSelect(columnSelect, '-- Pilih Judul Tabel Dahulu --');
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

            [columnSelect, wilayahSelect].forEach(select => {
                select.addEventListener('change', renderMapIfReady);
            });
            templateSelect.addEventListener('change', renderMapIfReady);

            function renderMapIfReady() {
                const templateId = templateSelect.value;
                const columnId = columnSelect.value;
                const wilayah = wilayahSelect.value;

                if (!templateId || !columnId || !wilayah) {
                    return;
                }

                const [rt, rw] = wilayah.split('|');
                hideAlert();

                const params = new URLSearchParams({
                    template_id: templateId,
                    column_id: columnId,
                    rt: rt,
                    rw: rw,
                });

                fetch(`{{ route('public.map.data') }}?${params.toString()}`)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('not_found');
                        }
                        return res.json();
                    })
                    .then(feature => {
                        if (activeLayer) {
                            map.removeLayer(activeLayer);
                        }

                        const geoJsonFeature = {
                            type: 'Feature',
                            properties: feature.properties,
                            geometry: feature.geojson,
                        };

                        activeLayer = L.geoJSON(geoJsonFeature, {
                            style: { color: '#059669', weight: 2, fillOpacity: 0.3 },
                        }).addTo(map);

                        const p = feature.properties;
                        const popupHtml = `
                            <div class="text-sm">
                                <strong>${p.kelurahan ?? '-'}</strong><br>
                                ${p.kecamatan ? p.kecamatan + '<br>' : ''}
                                RT ${p.rt} / RW ${p.rw}<br>
                                ${p.column_label}: <strong>${p.value ?? 'Belum diisi'}</strong>
                            </div>
                        `;
                        activeLayer.bindPopup(popupHtml).openPopup();
                        map.fitBounds(activeLayer.getBounds());
                    })
                    .catch(() => {
                        if (activeLayer) {
                            map.removeLayer(activeLayer);
                            activeLayer = null;
                        }
                        showAlert('Data tidak ditemukan untuk kombinasi Tabel/Kolom/Wilayah ini.');
                    });
            }
        });
    </script>
</x-layout>