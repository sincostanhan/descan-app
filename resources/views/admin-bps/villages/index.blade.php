<x-layout-admin-bps title="Panel Admin BPS | Kelurahan">
    <x-hero
        title="Kelurahan"
        subtitle="Daftar Kelurahan yang terdaftar di sistem"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        @if($errors->has('village'))
            <div class="alert alert-error shadow-sm mb-4">
                <x-lucide-alert-triangle class="w-5 h-5" />
                <span>{{ $errors->first('village') }}</span>
            </div>
        @endif

        <div class="flex justify-end mb-6">
            <a class="btn btn-sm md:btn-md btn-secondary" href="{{ route('admin-bps.villages.create') }}">
                <x-lucide-plus class="w-5 h-5" /> Tambah Kelurahan
            </a>
        </div>

        <div class="card bg-base-100 card-border shadow-lg">
            @if($villages->isEmpty())
                <x-empty-alert message="Belum ada Kelurahan yang terdaftar." />
            @else
                <div class="overflow-x-auto rounded-box border-base-200 border">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-200/50 text-base-content text-sm">
                            <tr>
                                <th>Nama Kelurahan</th>
                                <th>Subdomain</th>
                                <th class="text-center">Admin</th>
                                <th class="text-center">Data Statistik</th>
                                <th class="text-center">Status Publik</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($villages as $village)
                                <tr>
                                    <td class="font-medium">{{ $village->name }}</td>
                                    <td>
                                        <code class="text-xs bg-base-200 px-2 py-1 rounded">{{ $village->subdomain }}</code>
                                    </td>
                                    <td class="text-center">{{ $village->users_count }}</td>
                                    <td class="text-center">{{ $village->statistic_table_entries_count }}</td>
                                    <td class="text-center">
                                        @if($village->setting?->is_published)
                                            <div class="badge badge-success badge-soft">Published</div>
                                        @else
                                            <div class="badge badge-ghost">Belum Publish</div>
                                        @endif
                                    </td>
                                    <td class="flex justify-center gap-2">
                                        <a href="{{ route('admin-bps.villages.edit', $village) }}" class="btn btn-soft btn-warning btn-sm">Edit</a>

                                        <button type="button" onclick="document.getElementById('modal_confirm_delete_village_{{ $village->id }}').showModal()" class="btn btn-soft btn-error btn-sm">
                                            Hapus
                                        </button>
                                        <dialog id="modal_confirm_delete_village_{{ $village->id }}" class="modal">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg">Hapus Kelurahan?</h3>
                                                <p class="py-2 text-sm text-base-content/70">
                                                    Kelurahan "{{ $village->name }}" akan dihapus. Aksi ini tidak bisa dibatalkan.
                                                </p>
                                                <div class="modal-action justify-center">
                                                    <form method="dialog">
                                                        <button class="btn btn-ghost">Batal</button>
                                                    </form>
                                                    <button type="submit" form="form-delete-village-{{ $village->id }}" class="btn btn-error">
                                                        Ya, Hapus
                                                    </button>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop">
                                                <button>close</button>
                                            </form>
                                        </dialog>
                                        <form id="form-delete-village-{{ $village->id }}" action="{{ route('admin-bps.villages.destroy', $village) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layout-admin-bps>