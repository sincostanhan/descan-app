<x-layout-admin-bps title="Panel Admin BPS | Template Tabel Statistik">
    <x-hero
        title="Template Tabel Statistik"
        subtitle="Kelola struktur kolom & baris tabel yang akan diisi oleh Admin Kelurahan"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <div class="flex flex-col gap-4 mb-6 pl-0 md:pl-6">

            <div class="w-full">
                <form action="{{ url()->current() }}" method="GET" class="relative w-full sm:max-w-md">
                    {{-- Pertahankan query string yang sedang aktif (seperti per_page) --}}
                    @foreach(request()->except(['search', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan judul template..."
                        class="input input-sm md:input-md input-bordered w-full pr-10"
                    />

                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-base-content/50 hover:text-primary">
                        <x-lucide-search class="w-4 h-4 md:w-5 md:h-5" />
                    </button>
                </form>
            </div>

            <div class="flex
            flex-col-reverse items-end gap-3
            md:flex-row md:justify-between md:items-center">
                <x-pagination-dropdown :perPage="$perPage" />

                <a class="btn btn-sm md:btn-md
                btn-secondary shrink-0"
                href="{{ route('admin-bps.statistic-templates.create') }}">
                    <x-lucide-plus class="w-5 h-5" /> Tambah Template Baru
                </a>
            </div>
        </div>

        <div class="card bg-base-100
        card-border
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary
                text-xl mb-4 border-b pb-2">Daftar Template</h2>

                @if($templates->isEmpty())
                    @if(request('search'))
                        <x-empty-alert message="Template dengan judul '{{ request('search') }}' tidak ditemukan." />
                    @else
                        <x-empty-alert message="Belum ada template tabel statistik. Tambah template pertama untuk memulai." />
                    @endif
                @else
                    <div class="overflow-x-auto
                    rounded-box border-base-200
                    border">
                        <table class="table table-zebra
                        w-full">
                            <thead class="bg-base-200/50 text-base-content
                            text-sm">
                                <tr>
                                    <th>Judul Template</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Dashboard Peta</th>
                                    <th class="text-center">Dipakai Kelurahan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                    <tr>
                                        <td class="font-medium max-w-50 md:max-w-xs text-wrap wrap-break-words">
                                            {{ $template->title }}
                                            @if($template->description)
                                                <p class="text-xs text-base-content/60 font-normal mt-1 line-clamp-1">
                                                    {{ $template->description }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($template->is_active)
                                                <div class="badge badge-soft badge-success whitespace-nowrap">Aktif</div>
                                            @else
                                                <div class="badge badge-soft badge-neutral whitespace-nowrap">Nonaktif</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($template->is_mapped)
                                                <div class="badge badge-soft badge-info whitespace-nowrap">Tampil di Peta</div>
                                            @else
                                                <div class="badge badge-outline whitespace-nowrap">Tidak Dipetakan</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="badge badge-outline whitespace-nowrap">
                                                {{ $template->entries_count }} Kelurahan
                                            </div>
                                        </td>
                                        <td class="flex justify-center gap-2">
                                            <button type="button" onclick="document.getElementById('modal_history_{{ $template->id }}').showModal()" class="btn btn-soft btn-info btn-sm">
                                                Riwayat
                                            </button>
                                            <a href="{{ route('admin-bps.statistic-templates.edit', $template) }}" class="btn btn-soft btn-warning btn-sm">
                                                Edit
                                            </a>
                                            {{-- <form action="{{ route('admin-bps.statistic-templates.destroy', $template) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini? Aksi ini tidak bisa dibatalkan.')"
                                            class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft btn-error btn-sm">Hapus</button>
                                            </form> --}}
                                            <form id="form-delete-template-{{ $template->id }}" action="{{ route('admin-bps.statistic-templates.destroy', $template) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" onclick="document.getElementById('modal_confirm_delete_template_{{ $template->id }}').showModal()" class="btn btn-soft btn-error btn-sm">
                                                Hapus
                                            </button>

                                            <dialog id="modal_confirm_delete_template_{{ $template->id }}" class="modal">
                                                <div class="modal-box">
                                                    <div class="flex flex-col items-center text-center">
                                                        <x-lucide-triangle-alert class="w-14 h-14 text-error mb-4" />
                                                        <h3 class="font-bold text-xl text-base-content">Konfirmasi Hapus</h3>
                                                        <p class="py-4 text-base-content/80">Apakah Anda yakin ingin menghapus template ini? Aksi ini tidak bisa dibatalkan.</p>
                                                    </div>
                                                    <div class="modal-action justify-center">
                                                        <form method="dialog">
                                                            <button class="btn btn-ghost">Batal</button>
                                                        </form>
                                                        <button type="submit" form="form-delete-template-{{ $template->id }}" class="btn btn-error">
                                                            Ya, Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                                <form method="dialog" class="modal-backdrop">
                                                    <button>close</button>
                                                </form>
                                            </dialog>
                                        </td>
                                        <x-template-history-modal :template="$template" :can-restore="true" />
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($templates, 'links'))
                        <div class="mt-6">
                            {{ $templates->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-layout-admin-bps>