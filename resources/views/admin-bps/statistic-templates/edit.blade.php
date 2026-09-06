@php
    $buildTree = function ($headers, $axis, $parentId = null) use (&$buildTree) {
        return $headers->where('axis', $axis)->where('parent_id', $parentId)->sortBy('order')
            ->map(fn ($h) => [
                'id' => $h->id,
                'label' => $h->label,
                'data_type' => $h->data_type,
                'rt_value' => $h->rt_value,
                'children' => $buildTree($headers, $axis, $h->id),
            ])->values()->all();
    };

    $rowTree = $buildTree($statistic_template->headers, 'row');
    $columnTree = $buildTree($statistic_template->headers, 'column');
@endphp

<x-layout-admin-bps title="Panel Admin BPS | Edit Template">
    <x-hero title="Edit Template Tabel" />

    <div class="max-w-5xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <form action="{{ route('admin-bps.statistic-templates.update', $statistic_template) }}" method="POST" onsubmit="return prepareSubmit()">
            @csrf
            @method('PATCH')

            <div class="card bg-base-100 card-border shadow-lg mb-6">
                <div class="card-body">
                    <h2 class="card-title text-secondary text-xl mb-4 border-b pb-2">Informasi Template</h2>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Judul Template</legend>
                        <input type="text" name="title" value="{{ old('title', $statistic_template->title) }}" class="input w-full" required>
                        <x-forms.error name="title" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Deskripsi / Petunjuk Pengisian</legend>
                        <textarea name="description" rows="2" class="textarea w-full">{{ old('description', $statistic_template->description) }}</textarea>
                        <x-forms.error name="description" />
                    </fieldset>

                    <div class="flex flex-col gap-3">
                        <label class="label cursor-pointer justify-start gap-3 w-fit">
                            <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-sm" {{ old('is_active', $statistic_template->is_active) ? 'checked' : '' }}>
                            <span class="label-text">Template aktif (bisa dipilih Kelurahan)</span>
                        </label>

                        <label class="label cursor-pointer justify-start gap-3 w-fit">
                            <input type="checkbox" name="is_mapped" value="1" class="checkbox checkbox-sm checkbox-info" {{ old('is_mapped', $statistic_template->is_mapped) ? 'checked' : '' }}>
                            <span class="label-text">Tampilkan di Dashboard Peta Publik</span>
                        </label>
                        <p class="text-xs text-base-content/60 -mt-2 ml-9">
                            Hanya bisa diaktifkan jika minimal satu level struktur Baris diisi "Nilai RT".
                        </p>
                        <x-forms.error name="is_mapped" />
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 card-border shadow-lg mb-6">
                <div class="card-body">
                    <h2 class="card-title text-secondary text-xl mb-4 border-b pb-2">Sumber Baris</h2>
                    <div class="flex flex-col gap-2">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="radio" name="row_source" value="manual" class="radio radio-sm"
                                {{ old('row_source', $statistic_template->row_source ?? 'manual') === 'manual' ? 'checked' : '' }}
                                onchange="toggleRowSourceMode(this.value)">
                            <span class="label-text">Manual — BPS menyusun baris sendiri, sama untuk semua Kelurahan</span>
                        </label>
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="radio" name="row_source" value="rt_rw" class="radio radio-sm"
                                {{ old('row_source', $statistic_template->row_source ?? 'manual') === 'rt_rw' ? 'checked' : '' }}
                                onchange="toggleRowSourceMode(this.value)">
                            <span class="label-text">Otomatis dari RT/RW — baris "RT 00X RW 00X" digenerate otomatis per Kelurahan</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 card-border shadow-lg mb-6">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-2 border-b pb-2">
                        <h2 class="card-title text-secondary text-xl">Struktur Baris (Sisi Kiri Tabel)</h2>
                        {{-- <button type="button" class="btn btn-sm btn-outline" onclick="addRootNode('row-headers-container', 'row')"> --}}
                        <button type="button" id="btn-add-row-root" class="btn btn-sm btn-outline" onclick="addRootNode('row-headers-container', 'row')">
                            <x-lucide-plus class="w-4 h-4" /> Tambah Baris Utama
                        </button>
                    </div>
                    <div id="row-headers-container" class="space-y-2">
                        @foreach($rowTree as $node)
                            @include('admin-bps.statistic-templates.partials._header-node', ['node' => $node, 'axis' => 'row'])
                        @endforeach
                    </div>
                    <x-forms.error name="row_headers" />
                </div>
            </div>

            <div class="card bg-base-100 card-border shadow-lg mb-6">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-2 border-b pb-2">
                        <h2 class="card-title text-secondary text-xl">Struktur Kolom (Bagian Atas Tabel)</h2>
                        <button type="button" class="btn btn-sm btn-outline" onclick="addRootNode('column-headers-container', 'column')">
                            <x-lucide-plus class="w-4 h-4" /> Tambah Kolom Utama
                        </button>
                    </div>
                    <div id="column-headers-container" class="space-y-2">
                        @foreach($columnTree as $node)
                            @include('admin-bps.statistic-templates.partials._header-node', ['node' => $node, 'axis' => 'column'])
                        @endforeach
                    </div>
                    <x-forms.error name="column_headers" />
                </div>
            </div>

            <div class="card bg-base-100 card-border shadow-lg mb-6">
                <div class="card-body">
                    <h2 class="card-title text-secondary text-xl mb-4 border-b pb-2">Preview Struktur Tabel</h2>
                    <div id="template-preview-wrapper">
                        <p class="text-sm text-base-content/50 italic">Tambahkan minimal 1 struktur Baris dan 1 struktur Kolom untuk melihat preview.</p>
                    </div>
                </div>
            </div>

            <input type="hidden" name="row_headers" id="row_headers_input">
            <input type="hidden" name="column_headers" id="column_headers_input">

            <div class="card-actions justify-end mt-2">
                <a href="{{ route('admin-bps.statistic-templates.index') }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-secondary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    @push('scripts')
        @include('admin-bps.statistic-templates.partials._header-builder-script')
    @endpush
</x-layout-admin-bps>