<x-layout-admin title="Metadata Statistik">
    <x-hero
        title="Metadata Statistik"
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
                        placeholder="Cari berdasarkan judul..."
                        class="input input-sm md:input-md input-bordered w-full pr-10"
                    />

                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-base-content/50 hover:text-primary">
                        <x-lucide-search class="w-4 h-4 md:w-5 md:h-5" />
                    </button>
                </form>
            </div>

            <div class="flex flex-row justify-between items-center">
                <x-pagination-dropdown :perPage="$perPage" />

                <a class="btn btn-sm md:btn-md btn-secondary shrink-0"
                href="{{ route('admin.metadata-statistik.create') }}">
                    <x-lucide-plus class="w-5 h-5" /> Tambah Metadata
                </a>
            </div>

        </div>

        <x-section-card title="Daftar Metadata Statistik" title-size="text-xl">

                @if($metadataStatistiks->isEmpty())
                    @if(request('search'))
                        <x-empty-alert message="Data dengan judul '{{ request('search') }}' tidak ditemukan." />
                    @else
                        <x-empty-alert message="Belum ada data metadata statistik." />
                    @endif
                @else
                    @php
                        $currentSortBy = request('sort_by');
                        $currentSortDir = request('sort_dir', 'asc');
                        $isAnyActive = !empty($currentSortBy);

                        $getSortProps = function($column) use ($currentSortBy, $currentSortDir, $isAnyActive) {
                            $isActive = $currentSortBy === $column;
                            $isDisabled = $isAnyActive && !$isActive;
                            $icon = 'arrow-up-down';
                            $url = '#';

                            if ($isActive) {
                                if ($currentSortDir === 'asc') {
                                    $icon = 'arrow-up';
                                    $url = request()->fullUrlWithQuery(['sort_by' => $column, 'sort_dir' => 'desc', 'page' => 1]);
                                } else {
                                    $icon = 'arrow-down';
                                    $url = request()->fullUrlWithQuery(['sort_by' => null, 'sort_dir' => null, 'page' => 1]);
                                }
                            } elseif (!$isAnyActive) {
                                $url = request()->fullUrlWithQuery(['sort_by' => $column, 'sort_dir' => 'asc', 'page' => 1]);
                            }

                            return (object) compact('isActive', 'isDisabled', 'icon', 'url');
                        };

                        $titleProps = $getSortProps('title');
                        $updatedProps = $getSortProps('updated_at');
                    @endphp

                    <div class="overflow-x-auto rounded-box border-base-200 border">
                        <table class="table table-zebra w-full">
                            <thead class="bg-base-200/50 text-base-content text-sm select-none">
                                <tr>
                                    <th class="w-16">No</th>

                                    <th class="{{ $titleProps->isDisabled ? 'cursor-not-allowed text-base-content/50' : 'cursor-pointer hover:bg-base-300' }}"
                                        @if($titleProps->isDisabled) disabled @endif
                                        @if(!$titleProps->isDisabled) onclick="window.location='{{ $titleProps->url }}'" @endif>
                                        <div class="flex items-center gap-1">
                                            Judul Metadata
                                            @if($titleProps->icon === 'arrow-up-down') <x-lucide-arrow-up-down class="w-4 h-4 text-base-content/40" />
                                            @elseif($titleProps->icon === 'arrow-up') <x-lucide-arrow-up class="w-4 h-4" />
                                            @else <x-lucide-arrow-down class="w-4 h-4" /> @endif
                                        </div>
                                    </th>

                                    <th class="text-center">File</th>

                                    <th class="{{ $updatedProps->isDisabled ? 'cursor-not-allowed text-base-content/50' : 'cursor-pointer hover:bg-base-300' }}"
                                        @if($updatedProps->isDisabled) disabled @endif
                                        @if(!$updatedProps->isDisabled) onclick="window.location='{{ $updatedProps->url }}'" @endif>
                                        <div class="flex items-center gap-1">
                                            Terakhir Diperbarui
                                            @if($updatedProps->icon === 'arrow-up-down') <x-lucide-arrow-up-down class="w-4 h-4 text-base-content/40" />
                                            @elseif($updatedProps->icon === 'arrow-up') <x-lucide-arrow-up class="w-4 h-4" />
                                            @else <x-lucide-arrow-down class="w-4 h-4" /> @endif
                                        </div>
                                    </th>

                                    <th class="w-32 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($metadataStatistiks as $item)
                                    <tr>
                                        <th>{{ $metadataStatistiks->firstItem() + $loop->index }}</th>

                                        <td>
                                            <div class="flex items-center gap-4">
                                                <div class="avatar">
                                                    <div class="w-12 h-12 rounded border border-base-300 flex items-center justify-center bg-base-200">
                                                        @if($item->cover_path)
                                                            <img src="{{ asset('storage/' . $item->cover_path) }}" alt="Cover" class="object-cover" loading="lazy" decoding="async" />
                                                        @else
                                                            <x-lucide-file-text class="w-6 h-6 text-base-content/40" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-medium max-w-50 md:max-w-xs text-wrap wrap-break-words">{{ $item->title }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center whitespace-nowrap">
                                            <a
                                                href="{{ asset('storage/' . $item->file_path) }}"
                                                target="_blank"
                                                class="btn btn-soft btn-info btn-sm"
                                            >
                                                <x-lucide-external-link class="w-4 h-4 mr-1"/> Lihat File
                                            </a>
                                            <a href="{{ route('admin.metadata-statistik.download', $item->id) }}" class="btn btn-soft btn-success btn-sm mt-1">
                                               <x-lucide-download class="w-4 h-4 mr-1"/> Download
                                           </a>
                                        </td>
                                        <td>{{ $item->updated_at->format('d M Y') }}</td>
                                        <td class="text-center space-x-1 whitespace-nowrap">
                                            <a href="{{ route('admin.metadata-statistik.edit', $item->id) }}" class="btn btn-soft btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.metadata-statistik.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus metadata ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft btn-error btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($metadataStatistiks, 'links') && $metadataStatistiks->isNotEmpty())
                        <div class="mt-6">
                            {{ $metadataStatistiks->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
        </x-section-card>
    </div>
</x-layout-admin>