<x-layout-admin title="Grafik: {{ $statisticalTableEntry->template->title }}">
    <x-hero title="Visualisasi Grafik" :subtitle="$statisticalTableEntry->template->title" />

    <div class="max-w-5xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <div class="card bg-base-100 card-border shadow-lg">
            <div class="card-body">
                <form action="{{ route('admin.statistic-chart.store', $statisticalTableEntry) }}" method="POST">
                    @csrf

                    <div class="tabs tabs-border">
                        <input type="radio" name="chart_tabs" class="tab" aria-label="Data Tabel" checked="checked" />
                        <div class="tab-content border-base-300 bg-base-50 p-6">
                            @include('admin.statistic-chart.partials._data-preview')
                        </div>

                        <input type="radio" name="chart_tabs" class="tab" aria-label="Grafik" />
                        <div class="tab-content border-base-300 bg-base-50 p-6">
                            @include('admin.statistic-chart.partials._form-fields')
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-6 mt-6 border-t border-base-200">
                        <a href="{{ route('admin.statistic-table-entries.index') }}" class="btn btn-ghost">Batal</a>
                        {{-- <button type="submit" class="btn btn-secondary text-white"> --}}
                        <button type="submit" class="btn btn-secondary">
                            <x-lucide-save class="w-5 h-5 mr-1" /> Simpan Grafik
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.statistic-chart.partials._preview-script')
</x-layout-admin>