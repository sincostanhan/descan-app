<x-layout-admin title="Tambah Potensi Wisata">
    <x-hero title="Tambah Potensi Wisata" />

    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <x-section-card title="Tambah Data dan Unggah Foto" title-size="text-xl">
            <form action="{{ route('admin.potensi-wisata.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <fieldset class="fieldset w-full mb-6">
                    <legend class="fieldset-legend text-base">Nama</legend>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        placeholder="Contoh: Benteng Baadia"
                        class="input w-full" />
                    <x-forms.error name="nama" />
                </fieldset>

                <fieldset class="fieldset w-full mb-6">
                    <legend class="fieldset-legend text-base">Kategori</legend>
                    <select name="kategori" class="select w-full" required>
                        <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>-- Pilih Kategori --</option>
                        <option value="umum" {{ old('kategori') === 'umum' ? 'selected' : '' }}>Potensi Wisata (Umum)</option>
                        <option value="situs_bersejarah" {{ old('kategori') === 'situs_bersejarah' ? 'selected' : '' }}>Situs Bersejarah</option>
                    </select>
                    <x-forms.error name="kategori" />
                </fieldset>

                <fieldset class="fieldset w-full mb-8">
                    <legend class="fieldset-legend text-base">Foto</legend>
                    <input type="file" name="photos[]" multiple accept="image/*" required
                        class="file-input w-full file-input-secondary" />
                    <p class="label text-wrap break-words">
                        Format didukung: JPG, PNG (Maks. 2MB). Anda dapat mengupload lebih dari satu foto.
                    </p>
                    <x-forms.error name="photos.*" />
                </fieldset>

                <div class="flex justify-end space-x-2 pt-4 border-t border-base-200">
                    <a href="{{ route('admin.potensi-wisata.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-secondary">
                        <x-lucide-file-up class="w-5 h-5 mr-1" /> Simpan & Upload
                    </button>
                </div>
            </form>
        </x-section-card>
    </div>
</x-layout-admin>