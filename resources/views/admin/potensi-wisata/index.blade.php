<x-layout-admin title="Potensi Wisata">
    <x-hero title="Potensi Wisata" />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <div class="flex justify-end mb-6">
            <a class="btn btn-sm md:btn-md btn-secondary" href="{{ route('admin.potensi-wisata.create') }}">
                <x-lucide-plus class="w-5 h-5" /> Tambah Potensi Wisata
            </a>
        </div>

        <x-section-card title="Daftar Potensi Wisata" title-size="text-xl">
            @if($items->isEmpty())
                <x-empty-alert message="Belum ada data Potensi Wisata." />
            @else
                <div class="overflow-x-auto rounded-box border-base-200 border">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-200/50 text-base-content text-sm">
                            <tr>
                                <th class="w-16">No</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Jumlah Foto</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td class="font-medium">{{ $item->nama }}</td>
                                    <td>
                                        <span class="badge badge-outline {{ $item->kategori === 'situs_bersejarah' ? 'badge-secondary' : 'badge-primary' }}">
                                            {{ $item->kategori_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="badge badge-outline">{{ $item->photos->count() }} Foto</div>
                                    </td>
                                    <td class="text-center space-x-1 whitespace-nowrap">
                                        <a href="{{ route('admin.potensi-wisata.edit', $item->id) }}" class="btn btn-soft btn-warning btn-sm">Edit</a>
                                        <form id="form-delete-pw-{{ $item->id }}" action="{{ route('admin.potensi-wisata.destroy', $item->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        {{-- <button type="submit" form="form-delete-pw-{{ $item->id }}"
                                            onclick="return confirm('Yakin ingin menghapus data ini beserta semua fotonya?')"
                                            class="btn btn-soft btn-error btn-sm"> --}}
                                        <button type="button" onclick="document.getElementById('modal_confirm_delete_pw_{{ $item->id }}').showModal()" class="btn btn-soft btn-error btn-sm">
                                             Hapus
                                        </button>

                                        <dialog id="modal_confirm_delete_pw_{{ $item->id }}" class="modal">
                                            <div class="modal-box">
                                                <div class="flex flex-col items-center text-center">
                                                    <x-lucide-triangle-alert class="w-14 h-14 text-error mb-4" />
                                                    <h3 class="font-bold text-xl text-base-content">Konfirmasi Hapus</h3>
                                                    <p class="py-4 text-base-content/80">Yakin ingin menghapus "{{ $item->nama }}" beserta semua fotonya? Aksi ini tidak bisa dibatalkan.</p>
                                                </div>
                                                <div class="modal-action justify-center">
                                                    <form method="dialog">
                                                        <button class="btn btn-ghost">Batal</button>
                                                    </form>
                                                    <button type="submit" form="form-delete-pw-{{ $item->id }}" class="btn btn-error">
                                                        Ya, Hapus
                                                    </button>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop">
                                                <button>close</button>
                                            </form>
                                        </dialog>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-section-card>
    </div>
</x-layout-admin>