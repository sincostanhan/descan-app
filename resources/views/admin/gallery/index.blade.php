{{-- resources\views\admin\gallery\index.blade.php --}}

<x-layout-admin title="Galeri">
    <x-hero
        title="Galeri"
    />
    
    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <div class="flex justify-end mb-6">
            {{-- <button class="btn btn-xs sm:btn-sm md:btn-md lg:btn-lg xl:btn-xl"> --}}
            {{-- <button class="btn btn-xs sm:btn-sm md:btn-md --}}
            {{-- <button class="btn btn-sm md:btn-md --}}
            <a class="btn btn-sm md:btn-md
            btn-secondary"
            href="{{ route('admin.gallery.create') }}">
                <x-lucide-plus class="w-5 h-5" />
                {{-- Tambah Galeri Baru --}}
                Tambah Galeri
            </a>
        </div>

        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Daftar Galeri</h2>

                @if($galleries->isEmpty())
                    <x-empty-alert message="Belum ada galeri." />
                @else
                    <div class="overflow-x-auto 
                    rounded-box border-base-200 
                    border">
                        <table class="table table-zebra 
                        w-full">
                            <thead class="bg-base-200/50 text-base-content 
                            text-sm">
                                <tr>
                                    <th class="w-16">No</th>
                                    {{-- <th>Nama Kegiatan</th> --}}
                                    <th>Judul Galeri</th>
                                    <th>Jumlah Foto</th>
                                    <th>Tanggal Update</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($galleries as $gallery)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        {{-- <td class="font-medium">{{ $gallery->nama_kegiatan }}</td> --}}
                                        <td class="font-medium">{{ $gallery->judul }}</td>
                                        <td>
                                            <div class="badge badge-outline badge-secondary">{{ $gallery->photos->count() }} Foto</div>
                                        </td>
                                        <td>
                                            {{ $gallery->updated_at->format('d M Y') }}
                                        </td>
                                        <td class="text-center space-x-1
                                        whitespace-nowrap">
                                            {{-- <a href="{{ route('admin.gallery.show', $gallery->id) }}" class="btn btn-info btn-sm 
                                                text-white">
                                                Lihat
                                            </a> --}}
                                            <button onclick="document.getElementById('modal_show_{{ $gallery->id }}').showModal()" 
                                                {{-- class="btn btn-info btn-sm  --}}
                                                class="btn btn-soft btn-info btn-sm 
                                                {{-- text-white --}}
                                                ">
                                                Lihat
                                            </button>
                                            <a href="{{ route('admin.gallery.edit', $gallery->id) }}" 
                                                {{-- class="btn btn-warning btn-sm  --}}
                                                class="btn btn-soft btn-warning btn-sm 
                                                {{-- text-white --}}
                                                ">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.gallery.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus galeri ini?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                {{-- class="btn btn-error btn-sm --}}
                                                class="btn btn-soft btn-error btn-sm
                                                {{-- text-white --}}
                                                ">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <dialog id="modal_show_{{ $gallery->id }}" class="modal
                                        modal-middle">
                                        <div class="modal-box w-11/12 max-w-5xl">
                                            <form method="dialog">
                                                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                            </form>
                                            
                                            {{-- <h3 class="text-lg font-bold mb-1">{{ $gallery->nama_kegiatan }}</h3> --}}
                                            {{-- <h3 class="text-xl font-bold mb-1">{{ $gallery->nama_kegiatan }}</h3> --}}
                                            <h3 class="text-2xl font-bold mb-1">{{ $gallery->judul }}</h3>
                                            {{-- <p class="text-base text-base-content/70 mb-6">Diperbarui pada: {{ $gallery->updated_at->format('d F Y, H:i') }}</p> --}}
                                            <p class="text-sm text-base-content/70 mb-6">Diperbarui pada: {{ $gallery->updated_at->format('d F Y, H:i') }}</p>
                                            
                                            @if($gallery->photos->isEmpty())
                                                <div role="alert" class="alert alert-warning
                                                my-12">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    <span>Tidak ada foto dalam galeri ini</span>
                                                </div>    
                                            @else
                                                <div class="flex justify-center w-full">
                                                    {{-- <div class="carousel carousel-center bg-neutral rounded-box max-w-md space-x-4 p-4"> --}}
                                                    {{-- <div class="carousel carousel-center bg-neutral rounded-box w-full space-x-4 p-4 --}}
                                                    <div class="carousel carousel-center bg-neutral rounded-box max-w-full space-x-4 p-4
                                                    w-fit">
                                                        @foreach($gallery->photos as $photo)
                                                            <div class="carousel-item">
                                                                <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                                                {{-- <img src="{{ Storage::url($photo->foto_path) }}"  --}}
                                                                    alt="Foto {{ $gallery->judul }}"
                                                                    {{-- class="rounded-box" /> --}}
                                                                    class="rounded-box 
                                                                    h-72 md:h-96 object-cover" />
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="modal-action">
                                                <form method="dialog">
                                                    <button class="btn">Tutup</button>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout-admin>