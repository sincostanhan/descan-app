<x-layout-admin title="Edit Kegiatan">
    <div class="hero bg-base-100">
        <div class="hero-content text-center py-8">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold">Edit Galeri Kegiatan</h1>
            </div>
        </div>
    </div>

    <x-flash-message />

    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
    {{-- <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12
    py-8"> --}}
        <div class="card bg-base-100 
        card-border 
        shadow-lg
        mb-8">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Edit Kegiatan dan Tambah Foto Baru</h2>
            
                <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST" 
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <fieldset class="fieldset w-full 
                    mb-6">
                        {{-- <legend class="fieldset-legend">Nama Kegiatan</legend> --}}
                        <legend class="fieldset-legend 
                        text-base">Nama Kegiatan</legend>
                        <input type="text" 
                               id="nama_kegiatan" 
                               name="nama_kegiatan" 
                               {{-- value="{{ old('nama_kegiatan') }}"  --}}
                               value="{{ old('nama_kegiatan', $gallery->nama_kegiatan) }}" 
                               required 
                               placeholder="Masukkan nama kegiatan ..." 
                               class="input w-full 
                               {{-- @error('nama_kegiatan') input-error @enderror"  --}}
                               "/>
                        <x-forms.error name="nama_kegiatan" />
                    </fieldset>

                    <fieldset class="fieldset w-full 
                    mb-8">
                        <legend class="fieldset-legend 
                        text-base">Tambah Foto Baru (Opsional)</legend>
                        <input type="file" 
                               id="photos" 
                               name="photos[]" 
                               multiple 
                               class="file-input w-full 
                               file-input-secondary
                               {{-- @error('photos') file-input-error @enderror @error('photos.*') file-input-error @enderror"  --}}
                               "
                               accept="image/*" 
                               {{-- required /> --}}
                               />
                        
                        <p class="label">
                            Format didukung: JPG, PNG (Maks. 2MB). Anda dapat mengupload lebih dari satu foto kegiatan. <span class="text-error">Abaikan jika tidak ingin menambah foto baru.</span>
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
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card bg-base-100
        card-border 
        shadow-lg
        mb-8">
            <div class="card-body">
                <h2 class="card-title 
                text-xl text-secondary border-b pb-2
                mb-4">Kelola Foto Tersimpan</h2>
                
                @if($gallery->photos->isEmpty())
                    <div role="alert" class="alert alert-warning
                    {{-- my-12"> --}}
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Tidak ada foto dalam galeri ini</span>
                    </div>
                @else
                    <div class="flex justify-center w-full">
                        {{-- <div class="carousel carousel-center bg-neutral rounded-box max-w-full space-x-4 p-4 w-full"> --}}
                        <div class="carousel carousel-center bg-neutral rounded-box max-w-full space-x-4 p-4 w-fit">
                            @foreach($gallery->photos as $photo)
                                <div class="carousel-item 
                                relative group rounded-box overflow-hidden">
                                    
                                    <img src="{{ Storage::url($photo->foto_path) }}" 
                                         alt="Foto {{ $gallery->nama_kegiatan }}"
                                         class="h-72 md:h-96 object-cover" />
                                    
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity 
                                    flex items-center justify-center">
                                        <form action="{{ route('admin.gallery.photo.destroy', $photo->id) }}" method="POST" 
                                            onsubmit="return confirm('Hapus foto ini dari galeri?');">
                                            @csrf
                                            @method('DELETE')
                                            {{-- <button type="submit" class="btn btn-error btn-sm text-white shadow-lg"> --}}
                                            <button type="submit" class="btn btn-error btn-sm text-white">
                                                <x-lucide-trash-2 class="w-4 h-4 
                                                mr-1" />
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-layout-admin>