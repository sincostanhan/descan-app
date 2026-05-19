<x-layout-admin title="Edit Tabel Statistik">
    <x-hero
        title="Edit Tabel Statistik"
    />

    <div class="max-w-5xl mx-auto px-4 lg:px-0 mb-12">
        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Perbarui Data Tabel</h2>
            
                <form action="{{ route('admin.statistical-table.update', $table) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="columns" value="{{ json_encode($columns) }}">
                    <input type="hidden" name="content" value="{{ json_encode($content) }}">

                    {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-2"> --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2">
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend 
                            text-base">Nama Publikasi</legend>
                            <input type="text" 
                                name="publication" 
                                value="{{ old('publication', $table->publication) }}" 
                                required 
                                placeholder="Masukkan judul publikasi ..." 
                                class="input w-full"/>
                            <x-forms.error name="publication" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend 
                            text-base">Bab Publikasi</legend>
                            <input type="number" 
                                name="chapter" 
                                value="{{ old('chapter', $table->chapter) }}" 
                                required 
                                placeholder="Masukkan bab publikasi ... " 
                                class="input w-full"/>
                            <x-forms.error name="chapter" />
                        </fieldset>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6
                    mt-4">
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend 
                            text-base">Judul Tabel</legend>
                            <input type="text" 
                                name="title" 
                                value="{{ old('title', $table->title) }}" 
                                required 
                                placeholder="Masukkan judul tabel ..." 
                                class="input w-full"/>
                            <x-forms.error name="title" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend 
                            text-base">Sumber Data</legend>
                            <input type="text" 
                                name="source" 
                                value="{{ old('source', $table->source) }}" 
                                placeholder="Masukkan sumber data ..." 
                                class="input w-full"/>
                            <x-forms.error name="source" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend 
                            text-base">Keterangan (Opsional)</legend>
                            <textarea 
                                name="description" 
                                placeholder="Masukkan keterangan tabel ..." 
                                class="textarea h-24 w-full text-base leading-relaxed">{{ old('description', $table->description) }}</textarea>
                            <x-forms.error name="description" />
                        </fieldset>
                    </div>

                    <div class="mt-4">
                        <div class="tabs tabs-border">
                            <input 
                                type="radio" 
                                name="edit_tabs" 
                                class="tab" 
                                aria-label="Data Tabel" 
                                checked="checked" />
                            <div class="tab-content border-base-300 
                            bg-base-50 p-6">
                                <div class="overflow-x-auto 
                                {{-- bg-white  --}}
                                rounded border border-base-200 
                                shadow-sm">
                                    <table class="table table-zebra 
                                    table-sm md:table-md
                                    w-full">
                                        <thead class="bg-base-200/50 text-base-content 
                                        text-sm">
                                            <tr>
                                                @foreach($columns as $col)
                                                    <th class="whitespace-nowrap">{{ $col }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($content as $row)
                                                <tr>
                                                    @foreach($columns as $col)
                                                        <td class="whitespace-nowrap">{{ $row[$col] ?? '-' }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <input 
                                type="radio" 
                                name="edit_tabs" 
                                class="tab" 
                                aria-label="Grafik" />
                            <div class="tab-content border-base-300 
                            bg-base-50 p-6">
                                <div class="w-full py-20 
                                border border-dashed border-base-300 bg-white rounded shadow-sm 
                                flex flex-col items-center justify-center">
                                    <x-lucide-chart-column class="w-16 h-16 text-base-300 mb-4" />
                                    <h3 class="text-lg text-base-content/70 font-semibold">Konfigurasi Grafik</h3>
                                    <p class="text-sm text-base-content/50 mt-1 max-w-sm text-center">Fitur visualisasi grafik interaktif belum tersedia dan akan diimplementasikan pada tahap selanjutnya.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end 
                    space-x-2 pt-6 mt-6 
                    border-t border-base-200">
                        <a 
                            href="{{ route('admin.statistical-table.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-warning text-white">
                            <x-lucide-save class="w-5 h-5 mr-1" /> Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout-admin>