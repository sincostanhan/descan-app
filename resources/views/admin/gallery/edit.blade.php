{{-- resources\views\admin\gallery\edit.blade.php --}}

<x-layout-admin title="Edit Galeri Kegiatan">
    <x-hero
        title="Edit Galeri Kegiatan"
    />

    <x-flash-message />

    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
    {{-- <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12
    py-8"> --}}
        {{-- <div class="card bg-base-100 
        card-border 
        shadow-lg
        mb-8">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Edit Kegiatan dan Tambah Foto Baru</h2>
        <x-section-card title="Edit Kegiatan dan Tambah Foto Baru" title-size="text-xl"> --}}
        <x-section-card title="Edit Kegiatan dan Tambah Foto Baru" title-size="text-xl" class="mb-8">
            
                <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST" 
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

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
                               value="{{ old('judul', $gallery->judul) }}" 
                               required 
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
                        
                        <p class="label
                        text-wrap break-words">
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
                        {{-- class="btn btn-secondary text-white"> --}}
                        class="btn btn-secondary">
                            <x-lucide-file-up class="w-5 h-5 
                            mr-1" />
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            {{-- </div>
        </div> --}}
        </x-section-card>

        {{-- <div class="card bg-base-100
        card-border 
        shadow-lg
        mb-8">
            <div class="card-body">
                <h2 class="card-title 
                text-xl text-secondary border-b pb-2
                mb-4">Kelola Foto Tersimpan</h2> --}}
        <x-section-card title="Kelola Foto Tersimpan" title-size="text-xl" class="mb-8">
                
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
                    {{-- <div class="flex justify-center w-full">
                        {{-- <div class="carousel carousel-center bg-neutral rounded-box max-w-full space-x-4 p-4 w-full"> --}
                        <div class="carousel carousel-center bg-neutral rounded-box max-w-full space-x-4 p-4 w-fit">
                            @foreach($gallery->photos as $photo)
                                <div class="carousel-item 
                                relative group rounded-box overflow-hidden">
                                    <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                    {{-- <img src="{{ Storage::url($photo->foto_path) }}"  --}
                                         alt="Foto {{ $gallery->nama_kegiatan }}"
                                         {{-- class="h-72 md:h-96 object-cover" /> --}
                                        class="h-72 md:h-96 object-cover"
                                         loading="lazy" decoding="async" />
                                    
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity 
                                    flex items-center justify-center">
                                        {{-- <form action="{{ route('admin.gallery.photo.destroy', $photo->id) }}" method="POST" 
                                            onsubmit="return confirm('Hapus foto ini dari galeri?');">
                                            @csrf
                                            @method('DELETE')
                                            {{-- <button type="submit" class="btn btn-error btn-sm text-white shadow-lg"> --}
                                            {{-- <button type="submit" class="btn btn-error btn-sm text-white"> --}
                                            <button type="submit" class="btn btn-error btn-sm">
                                                <x-lucide-trash-2 class="w-4 h-4 
                                                mr-1" />
                                                Hapus
                                            </button>
                                        </form> --}
                                        <form id="form-delete-photo-{{ $photo->id }}" action="{{ route('admin.gallery.photo.destroy', $photo->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" onclick="document.getElementById('modal_confirm_delete_photo_{{ $photo->id }}').showModal()" class="btn btn-error btn-sm">
                                            <x-lucide-trash-2 class="w-4 h-4 mr-1" />
                                            Hapus
                                        </button>

                                        <dialog id="modal_confirm_delete_photo_{{ $photo->id }}" class="modal">
                                            <div class="modal-box">
                                                <div class="flex flex-col items-center text-center">
                                                    <x-lucide-triangle-alert class="w-14 h-14 text-error mb-4" />
                                                    <h3 class="font-bold text-xl text-base-content">Konfirmasi Hapus</h3>
                                                    <p class="py-4 text-base-content/80">Hapus foto ini dari galeri?</p>
                                                </div>
                                                <div class="modal-action justify-center">
                                                    <form method="dialog">
                                                        <button class="btn btn-ghost">Batal</button>
                                                    </form>
                                                    <button type="submit" form="form-delete-photo-{{ $photo->id }}" class="btn btn-error">
                                                        Ya, Hapus
                                                    </button>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop">
                                                <button>close</button>
                                            </form>
                                        </dialog>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div> --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($gallery->photos as $photo)
                        <div class="relative group">
                            <button type="button" onclick="document.getElementById('modal_view_photo_{{ $photo->id }}').showModal()"
                                class="block w-full focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-lg">
                                <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                     alt="Foto {{ $gallery->judul }}"
                                     class="w-full h-32 object-cover rounded-lg" loading="lazy" decoding="async" />
                            </button>

                            <form id="form-delete-photo-{{ $photo->id }}" action="{{ route('admin.gallery.photo.destroy', $photo->id) }}" method="POST" class="absolute top-1 right-1">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" onclick="document.getElementById('modal_confirm_delete_photo_{{ $photo->id }}').showModal()"
                                class="btn btn-circle btn-error btn-xs absolute top-1 right-1">
                                <x-lucide-x class="w-3 h-3" />
                            </button>

                            {{-- Modal lightbox: lihat foto full view --}}
                            <dialog id="modal_view_photo_{{ $photo->id }}" class="modal">
                                {{-- <div class="modal-box max-w-4xl p-2"> --}}
                                <div class="modal-box max-w-4xl p-2 flex items-center justify-center">
                                    <form method="dialog">
                                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-10">
                                            <x-lucide-x class="w-4 h-4" />
                                        </button>
                                    </form>
                                    <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                         alt="Foto {{ $gallery->judul }}"
                                         {{-- class="w-full h-auto rounded-lg" loading="lazy" /> --}}
                                         class="max-h-[80vh] w-auto max-w-full object-contain rounded-lg" loading="lazy" />
                                </div>
                                <form method="dialog" class="modal-backdrop">
                                    <button>close</button>
                                </form>
                            </dialog>

                            {{-- Modal konfirmasi hapus --}}
                            <dialog id="modal_confirm_delete_photo_{{ $photo->id }}" class="modal">
                                <div class="modal-box">
                                    <div class="flex flex-col items-center text-center">
                                        <x-lucide-triangle-alert class="w-14 h-14 text-error mb-4" />
                                        <h3 class="font-bold text-xl text-base-content">Konfirmasi Hapus</h3>
                                        <p class="py-4 text-base-content/80">Hapus foto ini dari galeri?</p>
                                    </div>
                                    <div class="modal-action justify-center">
                                        <form method="dialog">
                                            <button class="btn btn-ghost">Batal</button>
                                        </form>
                                        <button type="submit" form="form-delete-photo-{{ $photo->id }}" class="btn btn-error">
                                            Ya, Hapus
                                        </button>
                                    </div>
                                </div>
                                <form method="dialog" class="modal-backdrop">
                                    <button>close</button>
                                </form>
                            </dialog>
                        </div>
                    @endforeach
                </div>
                @endif
            {{-- </div>
        </div> --}}
        </x-section-card>

    </div>
</x-layout-admin>