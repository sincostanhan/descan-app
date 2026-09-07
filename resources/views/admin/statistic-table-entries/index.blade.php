<x-layout-admin title="Tabel dan Grafik">
    <x-hero
        title="Tabel Statistik"
        subtitle="Daftar tabel yang sudah Anda isi berdasarkan template dari Admin BPS"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <div class="flex flex-col gap-4 mb-6 pl-0 md:pl-6">

            <div class="w-full">
                <form action="{{ url()->current() }}" method="GET" class="relative w-full sm:max-w-md">
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
                href="{{ route('admin.statistic-table-entries.select-template') }}">
                    <x-lucide-plus class="w-5 h-5" /> Tambah Tabel Baru
                </a>
            </div>
        </div>

        <div class="card bg-base-100
        card-border
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary
                text-xl mb-4 border-b pb-2">Daftar Tabel Statistik</h2>

                @if($entries->isEmpty())
                    @if(request('search'))
                        <x-empty-alert message="Tabel dengan judul '{{ request('search') }}' tidak ditemukan." />
                    @else
                        <x-empty-alert message="Anda belum mengisi tabel statistik apa pun. Klik &quot;Tambah Tabel Baru&quot; untuk memulai." />
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
                                    <th>Sumber Data</th>
                                    <th class="text-center">Grafik</th>
                                    <th class="text-center">Terakhir Diperbarui</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entries as $entry)
                                    <tr>
                                        <td class="font-medium max-w-50 md:max-w-xs text-wrap wrap-break-words">
                                            {{ $entry->title ?? $entry->template->title }}
                                        </td>
                                        <td class="text-sm text-base-content/70">
                                            {{ $entry->source ?: '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if($entry->chart)
                                                <div class="badge badge-soft badge-success whitespace-nowrap">Ada</div>
                                            @else
                                                <div class="badge badge-outline whitespace-nowrap">Belum Ada</div>
                                            @endif
                                        </td>
                                        <td class="text-center text-sm">
                                            {{ $entry->updated_at->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="flex justify-center gap-2">
                                            {{-- <button type="button" onclick="document.getElementById('modal_history_{{ $entry->template->id }}').showModal()" class="btn btn-soft btn-info btn-sm"> --}}
                                            <button type="button" onclick="document.getElementById('modal_history_{{ $entry->template->id }}').showModal()" class="btn btn-soft btn-info btn-sm gap-1">
                                                Riwayat
                                                <x-lucide-bell class="w-4 h-4 {{ ($unreadCounts[$entry->template->id] ?? 0) > 0 ? 'text-error' : '' }}" />
                                                @if(($unreadCounts[$entry->template->id] ?? 0) > 0)
                                                    <span class="text-error font-bold text-xs">{{ $unreadCounts[$entry->template->id] }}</span>
                                                @endif
                                            </button>
                                            <a href="{{ route('admin.statistic-table-entries.edit', $entry) }}" class="btn btn-soft btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.statistic-table-entries.destroy', $entry) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus tabel ini beserta grafiknya (jika ada)? Aksi ini tidak bisa dibatalkan.')"
                                            class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft btn-error btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                        {{-- <x-template-history-modal :template="$entry->template" :can-restore="false" /> --}}
                                        <x-template-history-modal :template="$entry->template" :can-restore="false" :village-id="$villageId" />
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($entries, 'links'))
                        <div class="mt-6">
                            {{ $entries->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-layout-admin>