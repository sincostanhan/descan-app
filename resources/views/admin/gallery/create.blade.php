<x-layout-admin title="Tambah Galeri Kegiatan">
    <x-hero
        title="Tambah Galeri Kegiatan"
    />

    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
    {{-- <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12
    py-8"> --}}
        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Tambah Kegiatan dan Unggah Foto</h2>
            
                <form action="{{ route('admin.gallery.store') }}" method="POST" 
                enctype="multipart/form-data">
                    @csrf

                    <fieldset class="fieldset w-full 
                    mb-6">
                        {{-- <legend class="fieldset-legend">Nama Kegiatan</legend> --}}
                        <legend class="fieldset-legend 
                        text-base">Judul Galeri</legend>
                        <input type="text" 
                               {{-- id="nama_kegiatan"  --}}
                               id="judul" 
                               {{-- name="nama_kegiatan"  --}}
                               name="judul" 
                               {{-- value="{{ old('nama_kegiatan') }}"  --}}
                               value="{{ old('judul') }}" 
                               required 
                               {{-- placeholder="Masukkan nama kegiatan ..."  --}}
                               placeholder="Masukkan judul galeri ..." 
                               class="input w-full 
                               {{-- @error('nama_kegiatan') input-error @enderror"  --}}
                               "/>
                        {{-- <x-forms.error name="nama_kegiatan" /> --}}
                        <x-forms.error name="judul" />
                    </fieldset>

                    <fieldset class="fieldset w-full 
                    mb-8">
                        <legend class="fieldset-legend 
                        text-base">Foto Kegiatan</legend>
                        <input type="file" 
                               id="photos" 
                               name="photos[]" 
                               multiple 
                               class="file-input w-full 
                               file-input-secondary
                               {{-- @error('photos') file-input-error @enderror @error('photos.*') file-input-error @enderror"  --}}
                               "
                               accept="image/*" 
                               required />
                        
                        <p class="label">
                            Format didukung: JPG, PNG (Maks. 2MB). Anda dapat mengupload lebih dari satu foto kegiatan.
                        </p>
                        
                        {{-- <x-forms.error name="photos" /> --}}
                        <x-forms.error name='photos.*' />
                        {{-- @error('photos.*')
                            <span class="text-error text-sm mt-1">{{ $message }}</span>
                        @enderror --}}
                    </fieldset>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-base-200">
                        <a href="{{ route('admin.gallery.index') }}" 
                        class="btn btn-ghost">Batal</a>
                        <button type="submit" 
                        class="btn btn-secondary text-white">
                            <x-lucide-file-up class="w-5 h-5 
                            mr-1" />
                            Simpan & Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout-admin>