<x-layout-admin-bps title="Kelola Peta Wilayah">
    <x-hero
        title="Kelola Peta Wilayah"
        subtitle="Tempel/edit GeoJSON gabungan seluruh RT/RW di sini. Sistem otomatis mencocokkan tiap feature ke Kelurahan lewat properti NAMA_KELURAHAN."
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
                    <div id="geoPreviewMap" class="w-full rounded-box border border-base-200" style="height: 600px;"></div>
                    <button type="button" id="btnRenderPreview" class="btn btn-outline btn-sm mt-3">
                        <x-lucide-refresh-cw class="w-4 h-4 mr-1" /> Render Ulang Preview
                    </button>
                    <div id="previewError" class="alert alert-warning shadow-sm mt-3 hidden">
                        <x-lucide-info class="w-4 h-4" />
                        <span id="previewErrorText" class="text-sm"></span>
                    </div>
                </x-section-card>

                <x-section-card title="FeatureCollection (GeoJSON)" title-size="text-lg">
                    <textarea
                        name="geojson"
                        id="geojsonTextarea"
                        rows="26"
                        class="textarea w-full font-mono text-xs"
                        spellcheck="false"
                    >{{ old('geojson', $geojsonText) }}</textarea>
                    <x-forms.error name="geojson" />
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('geojsonTextarea');
            const errorBox = document.getElementById('previewError');
            const errorText = document.getElementById('previewErrorText');

            const map = L.map('geoPreviewMap').setView([-5.481, 122.617], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            let currentLayer = null;

            function renderPreview() {
                errorBox.classList.add('hidden');

                let parsed;
                try {
                    parsed = JSON.parse(textarea.value);
                } catch (e) {
                    errorText.textContent = 'GeoJSON tidak valid: ' + e.message;
                    errorBox.classList.remove('hidden');
                    return;
                }

                if (currentLayer) {
                    map.removeLayer(currentLayer);
                    currentLayer = null;
                }

                try {
                    currentLayer = L.geoJSON(parsed, {
                        style: { color: '#059669', weight: 1, fillOpacity: 0.25 },
                        onEachFeature: function (feature, layer) {
                            const p = feature.properties || {};
                            layer.bindTooltip(`${p.NAMA_KELURAHAN ?? '-'} / ${p.NAMA_RW ?? '-'} / ${p.NAMA_RT ?? '-'}`);
                        },
                    }).addTo(map);

                    if (currentLayer.getBounds().isValid()) {
                        map.fitBounds(currentLayer.getBounds());
                    }
                } catch (e) {
                    errorText.textContent = 'Gagal render sebagai GeoJSON: ' + e.message;
                    errorBox.classList.remove('hidden');
                }
            }

            document.getElementById('btnRenderPreview').addEventListener('click', renderPreview);

            // Render pertama kali otomatis (data awal dari server / hasil submit sebelumnya).
            renderPreview();
        });
    </script>
</x-layout-admin-bps>