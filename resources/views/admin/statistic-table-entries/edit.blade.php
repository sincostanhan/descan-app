@php
    $existingValues = $entry->values->pluck('value', 'statistic_template_cell_id')->all();
@endphp

<x-layout-admin title="Edit Tabel: {{ $entry->template->title }}">
    <x-hero title="{{ $entry->template->title }}" :subtitle="$entry->template->description" />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <form action="{{ route('admin.statistic-table-entries.update', $entry) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="card bg-base-100 card-border shadow-lg mb-6">
                <div class="card-body">
                    <fieldset class="fieldset w-full mb-4">
                        <legend class="fieldset-legend text-base">Publikasi</legend>
                        <select name="publication_id" class="select w-full">
                            <option value="">-- Tidak termasuk publikasi mana pun --</option>
                            @foreach($publications as $publication)
                                <option value="{{ $publication->id }}" @selected((string) old('publication_id', $entry->publication_id) === (string) $publication->id)>
                                    {{ $publication->title }}
                                </option>
                            @endforeach
                        </select>
                        <p class="label">Opsional. Satu tabel hanya bisa masuk ke satu publikasi.</p>
                        <x-forms.error name="publication_id" />
                    </fieldset>
                    <fieldset class="fieldset w-full mb-4">
                        <legend class="fieldset-legend text-base">Sumber Data</legend>
                        <input type="text" name="source" value="{{ old('source', $entry->source) }}" class="input w-full">
                        <x-forms.error name="source" />
                    </fieldset>
                    <fieldset class="fieldset w-full">
                        <legend class="fieldset-legend text-base">Keterangan</legend>
                        <textarea name="description" rows="2" class="textarea w-full">{{ old('description', $entry->description) }}</textarea>
                        <x-forms.error name="description" />
                    </fieldset>
                </div>
            </div>

            <div class="card bg-base-100 card-border shadow-lg mb-6">
                <div class="card-body">
                    <h2 class="card-title text-secondary text-xl mb-4 border-b pb-2">Input Data</h2>
                    @include('admin.statistic-table-entries.partials._spreadsheet-table', ['template' => $entry->template, 'existingValues' => $existingValues])
                </div>
            </div>

            <div class="card-actions justify-end">
                <a href="{{ route('admin.statistic-table-entries.index') }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-secondary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-layout-admin>