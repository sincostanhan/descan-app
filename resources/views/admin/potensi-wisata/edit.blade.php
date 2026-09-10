<x-layout-admin title="Edit Potensi Wisata">
    <x-hero title="Edit Potensi Wisata" />

    <x-flash-message />

    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
        <x-section-card title="Edit Data dan Tambah Foto Baru" title-size="text-xl" class="mb-8">
            <form action="{{ route('admin.potensi-wisata.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <fieldset class="fieldset w-full mb-6">
                    <legend class="fieldset-legend text-base">Nama</legend>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" required class="input w-full" />
                    <x-forms.error name="nama" />
                </fieldset>

                <fieldset class="fieldset w-full mb-6">
                    <legend class="fieldset-legend text-base">Kategori</legend>
                    <select name="kategori" class="select w-full" required>
                        <option value="umum" {{ old('kategori', $item->kategori) === 'umum' ? 'selected' : '' }}>Potensi Wisata (Umum)</option>
                        <option value="situs_bersejarah" {{ old('kategori', $item->kategori) === 'situs_bersejarah' ? 'selected' : '' }}>Situs Bersejarah</option>
                    </select>
                    <x-forms.error name="kategori" />
                </fieldset>

                <fieldset class="fieldset w-full mb-8">
                    <legend class="fieldset-legend text-base">Tambah Foto Baru (Opsional)</legend>
                    <input type="file" name="photos[]" multiple accept="image/*" class="file-input w-full file-input-secondary" />
                    <p class="label text-wrap break-words">Abaikan jika tidak ingin menambah foto baru.</p>
                    <x-forms.error name="photos.*" />
                </fieldset>

                <div class="flex justify-end space-x-2 pt-4 border-t border-base-200">
                    <a href="{{ route('admin.potensi-wisata.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-secondary">
                        <x-lucide-save class="w-5 h-5 mr-1" /> Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-section-card>

        <x-section-card title="Foto Existing" title-size="text-xl">
            @if($item->photos->isEmpty())
                <x-empty-alert message="Belum ada foto." />
            @else
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($item->photos as $photo)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $photo->foto_path) }}" class="w-full h-32 object-cover rounded-lg" loading="lazy" />
                            <form action="{{ route('admin.potensi-wisata.photo.destroy', $photo->id) }}" method="POST"
                                onsubmit="return confirm('Hapus foto ini?')" class="absolute top-1 right-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-circle btn-error btn-xs">
                                    <x-lucide-x class="w-3 h-3" />
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-section-card>
    </div>
</x-layout-admin>