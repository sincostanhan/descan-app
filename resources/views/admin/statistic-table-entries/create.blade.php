<x-layout-admin title="Isi Tabel: {{ $template->title }}">
    <x-hero title="{{ $template->title }}" :subtitle="$template->description" />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <form action="{{ route('admin.statistic-table-entries.store', $template) }}" method="POST">
            @csrf

            <div class="card bg-base-100 card-border shadow-lg mb-6">
                <div class="card-body">
                    <fieldset class="fieldset w-full mb-4">
                        <legend class="fieldset-legend text-base">Sumber Data</legend>
                        <input type="text" name="source" value="{{ old('source') }}" class="input w-full" placeholder="Contoh: Data Monografi Kelurahan 2026">
                        <x-forms.error name="source" />
                    </fieldset>
                    <fieldset class="fieldset w-full">
                        <legend class="fieldset-legend text-base">Keterangan</legend>
                        <textarea name="description" rows="2" class="textarea w-full">{{ old('description') }}</textarea>
                        <x-forms.error name="description" />
                    </fieldset>
                </div>
            </div>

            <div class="card bg-base-100 card-border shadow-lg mb-6">
                <div class="card-body">
                    <h2 class="card-title text-secondary text-xl mb-4 border-b pb-2">Input Data</h2>
                    @include('admin.statistic-table-entries.partials._spreadsheet-table', ['template' => $template])
                </div>
            </div>

            <div class="card-actions justify-end">
                <a href="{{ route('admin.statistic-table-entries.index') }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-secondary">Simpan Tabel</button>
            </div>
        </form>
    </div>
</x-layout-admin>