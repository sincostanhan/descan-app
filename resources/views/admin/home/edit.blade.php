{{-- resources\views\admin\home\edit.blade.php --}}

<x-layout-admin title="Beranda">
    <x-hero 
        title="Kelurahan Cantik" 
        subtitle="Kelola informasi Latar Belakang, Tujuan, dan Output Kelurahan Cantik" 
    />

    <div class="max-w-5xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <form action="{{ route('admin.home.update') }}" method="POST">
            @csrf

            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                {{-- <div class="card-body">
                    
                    {{-- Latar Belakang --}
                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend 
                        {{-- text-lg text-secondary border-b w-full pb-2 mb-2">Latar Belakang</legend> --}
                        font-bold text-lg text-secondary border-b w-full pb-2 mb-2">Latar Belakang</legend>
                        <textarea 
                            name="latar_belakang" 
                            class="textarea h-64 w-full text-base leading-relaxed" 
                            required>{{ old('latar_belakang', $home->latar_belakang) }}</textarea>
                        <x-forms.error name="latar_belakang" />
                    </fieldset>

                    {{-- Tujuan --}
                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend 
                        {{-- font-bold text-lg text-secondary border-b w-full pb-2 mb-2">Tujuan</legend> --}
                        text-lg text-secondary border-b w-full pb-2 mb-2">Tujuan</legend>
                        <textarea 
                            name="tujuan" 
                            {{-- class="textarea h-64 w-full text-base leading-relaxed"  --}
                            class="textarea h-48 w-full text-base leading-relaxed" 
                            required>{{ old('tujuan', $home->tujuan) }}</textarea>
                        <x-forms.error name="tujuan" />
                    </fieldset>

                    {{-- Output --}
                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend 
                        {{-- font-bold text-lg text-secondary border-b w-full pb-2 mb-2">Output</legend> --}
                        text-lg text-secondary border-b w-full pb-2 mb-2">Output</legend>
                        <textarea 
                            name="output" 
                            class="textarea h-64 w-full text-base leading-relaxed" 
                            required>{{ old('output', $home->output) }}</textarea>
                        <x-forms.error name="output" />
                    </fieldset>

                    <div class="card-actions 
                    justify-end mt-8 border-t pt-4">
                        <button type="submit" class="btn btn-secondary">
                            <x-lucide-save class="w-5 h-5 mr-1" /> Simpan Perubahan
                        </button>
                    </div>
                </div> --}}
                <div class="card-body">

                                        <div class="divider">Gambar Homepage</div>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Foto Card "Galeri Kegiatan"</legend>
                        <select name="featured_gallery_photo_id" class="select w-full">
                            <option value="">-- Otomatis (foto terbaru) --</option>
                            @foreach($galleryPhotos as $photo)
                                <option value="{{ $photo->id }}"
                                    {{ old('featured_gallery_photo_id', $home->featured_gallery_photo_id) == $photo->id ? 'selected' : '' }}>
                                    {{ $photo->gallery->judul }} — {{ $photo->created_at->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                        <x-forms.error name="featured_gallery_photo_id" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Foto Card "Potensi Wisata"</legend>
                        <select name="featured_potensi_wisata_photo_id" class="select w-full">
                            <option value="">-- Otomatis (foto terbaru) --</option>
                            @foreach($potensiWisataPhotos as $photo)
                                <option value="{{ $photo->id }}"
                                    {{ old('featured_potensi_wisata_photo_id', $home->featured_potensi_wisata_photo_id) == $photo->id ? 'selected' : '' }}>
                                    {{ $photo->potensiWisata->nama }} ({{ $photo->potensiWisata->kategori_label }}) — {{ $photo->created_at->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                        <x-forms.error name="featured_potensi_wisata_photo_id" />
                    </fieldset>
                    
                    <div class="space-y-3">
                        {{-- 1. Latar Belakang --}}
                        <div class="collapse collapse-plus bg-base-100 border border-base-300">
                            <input type="checkbox" checked="checked" />
                            <div class="collapse-title font-semibold text-lg text-secondary">
                                Latar Belakang
                            </div>
                            <div class="collapse-content text-sm space-y-3">
                                <label class="label cursor-pointer justify-start gap-3">
                                    <input type="checkbox" name="show_latar_belakang" class="toggle toggle-primary toggle-sm"
                                        {{ old('show_latar_belakang', $home->show_latar_belakang ?? true) ? 'checked' : '' }} />
                                    <span class="label-text">Tampilkan di beranda publik</span>
                                </label>
                                <textarea
                                    name="latar_belakang"
                                    class="textarea h-64 w-full text-base leading-relaxed"
                                    required>{{ old('latar_belakang', $home->latar_belakang) }}</textarea>
                                <x-forms.error name="latar_belakang" />
                            </div>
                        </div>

                        {{-- 2. Tujuan --}}
                        <div class="collapse collapse-plus bg-base-100 border border-base-300">
                            <input type="checkbox" />
                            <div class="collapse-title font-semibold text-lg text-secondary">
                                Tujuan Program
                            </div>
                            <div class="collapse-content text-sm space-y-3">
                                <label class="label cursor-pointer justify-start gap-3">
                                    <input type="checkbox" name="show_tujuan" class="toggle toggle-primary toggle-sm"
                                        {{ old('show_tujuan', $home->show_tujuan ?? true) ? 'checked' : '' }} />
                                    <span class="label-text">Tampilkan di beranda publik</span>
                                </label>
                                <textarea
                                    name="tujuan"
                                    class="textarea h-48 w-full text-base leading-relaxed"
                                    required>{{ old('tujuan', $home->tujuan) }}</textarea>
                                <x-forms.error name="tujuan" />
                            </div>
                        </div>

                        {{-- 3. Output --}}
                        <div class="collapse collapse-plus bg-base-100 border border-base-300">
                            <input type="checkbox" />
                            <div class="collapse-title font-semibold text-lg text-secondary">
                                Output Kelurahan Cantik
                            </div>
                            <div class="collapse-content text-sm space-y-3">
                                <label class="label cursor-pointer justify-start gap-3">
                                    <input type="checkbox" name="show_output" class="toggle toggle-primary toggle-sm"
                                        {{ old('show_output', $home->show_output ?? true) ? 'checked' : '' }} />
                                    <span class="label-text">Tampilkan di beranda publik</span>
                                </label>
                                <textarea
                                    name="output"
                                    class="textarea h-64 w-full text-base leading-relaxed"
                                    required>{{ old('output', $home->output) }}</textarea>
                                <x-forms.error name="output" />
                            </div>
                        </div>

                        {{-- 4. Tim Kelurahan Cantik (baru) --}}
                        <div class="collapse collapse-plus bg-base-100 border border-base-300">
                            <input type="checkbox" />
                            <div class="collapse-title font-semibold text-lg text-secondary">
                                Tim Kelurahan Cantik
                            </div>
                            <div class="collapse-content text-sm space-y-3">
                                <label class="label cursor-pointer justify-start gap-3">
                                    <input type="checkbox" name="show_tim" class="toggle toggle-primary toggle-sm"
                                        {{ old('show_tim', $home->show_tim ?? true) ? 'checked' : '' }} />
                                    <span class="label-text">Tampilkan di beranda publik</span>
                                </label>
                                <textarea
                                    name="tim_kelurahan"
                                    class="textarea h-48 w-full text-base leading-relaxed"
                                    placeholder="Contoh:&#10;Agen Data Koordinator:&#10;USMAN JAFAR, S.IP&#10;&#10;Agen Data Pengolah:&#10;1. RASFIA USI, S.Kom.&#10;2. BERI SAPUTRA&#10;&#10;Agen Data Lapangan:&#10;1. M. FITRIADI&#10;2. NURHAYATI&#10;3. ROSIDA H.D."
                                >{{ old('tim_kelurahan', $home->tim_kelurahan) }}</textarea>
                                <x-forms.error name="tim_kelurahan" />
                            </div>
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-8 border-t pt-4">
                        <button type="submit" class="btn btn-secondary">
                            <x-lucide-save class="w-5 h-5 mr-1" /> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout-admin>