<x-layout-admin title="Tabel Statistik">
    <x-hero
        title="Tabel Statistik"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <div class="flex flex-col gap-4 mb-6 pl-0 md:pl-6">
            
            <div class="w-full">
                <form action="{{ url()->current() }}" method="GET" class="relative w-full sm:max-w-md">
                    {{-- Pertahankan query string yang sedang aktif (seperti sort_by dan per_page) --}}
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

            <div class="flex 
            flex-col-reverse items-end gap-3
            md:flex-row md:justify-between md:items-center">
                <x-pagination-dropdown :perPage="$perPage" />

                <a 
                    href="{{ route('admin.statistical-table.create') }}" 
                    class="btn btn-sm md:btn-md 
                        {{-- btn-secondary"> --}}
                        btn-secondary shrink-0">
                    {{-- <x-lucide-plus class="w-5 h-5" /> --}}
                    <x-lucide-plus class="w-5 h-5 mr-1" />
                    Tambah Tabel
                </a>
            </div>
            
        </div>

        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Daftar Tabel Statistik</h2>

                @if($tables->isEmpty())
                    {{-- <x-empty-alert message="Belum ada tabel statistik yang ditambahkan." /> --}}
                    @if(request('search'))
                        <x-empty-alert message="Data dengan judul '{{ request('search') }}' tidak ditemukan." />
                    @else
                        <x-empty-alert message="Belum ada tabel statistik yang ditambahkan." />
                    @endif
                @else
                    <div class="overflow-x-auto 
                    rounded-box border-base-200 
                    border">
                        <table class="table table-zebra 
                        w-full">
                            <thead class="bg-base-200/50 text-base-content text-sm select-none">
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

                                    $pubProps = $getSortProps('publication');
                                    $titleProps = $getSortProps('title');
                                    $updatedProps = $getSortProps('updated_at');
                                @endphp

                                <tr>
                                    <th class="w-16 text-center">No</th>
                                    
                                    {{-- Kolom Publikasi --}}
                                    <th class="{{ $pubProps->isDisabled ? 'cursor-not-allowed' : 'cursor-pointer hover:bg-base-300' }} transition-colors"
                                        @if(!$pubProps->isDisabled) onclick="window.location='{{ $pubProps->url }}'" @endif>
                                        <div class="flex items-center gap-1">
                                            Publikasi & Bab
                                            @if($pubProps->icon === 'arrow-up-down') <x-lucide-arrow-up-down class="w-4 h-4 text-base-content/40" />
                                            @elseif($pubProps->icon === 'arrow-up') <x-lucide-arrow-up class="w-4 h-4" />
                                            @else <x-lucide-arrow-down class="w-4 h-4" /> @endif
                                        </div>
                                    </th>

                                    {{-- Kolom Judul --}}
                                    <th class="{{ $titleProps->isDisabled ? 'cursor-not-allowed' : 'cursor-pointer hover:bg-base-300' }} transition-colors"
                                        @if(!$titleProps->isDisabled) onclick="window.location='{{ $titleProps->url }}'" @endif>
                                        <div class="flex items-center gap-1">
                                            Judul Tabel
                                            @if($titleProps->icon === 'arrow-up-down') <x-lucide-arrow-up-down class="w-4 h-4 text-base-content/40" />
                                            @elseif($titleProps->icon === 'arrow-up') <x-lucide-arrow-up class="w-4 h-4" />
                                            @else <x-lucide-arrow-down class="w-4 h-4" /> @endif
                                        </div>
                                    </th>

                                    {{-- Kolom Terakhir Diperbarui --}}
                                    <th class="{{ $updatedProps->isDisabled ? 'cursor-not-allowed' : 'cursor-pointer hover:bg-base-300' }} transition-colors"
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
                                @foreach($tables as $table)
                                    <tr>
                                        {{-- <th>{{ $loop->iteration }}</th> --}}
                                        <th>{{ $tables->firstItem() + $loop->index }}</th>
                                        
                                        <td>
                                            <div class="font-medium
                                            whitespace-nowrap">{{ $table->publication }}</div>
                                            <div class="badge badge-secondary badge-outline badge-sm mt-1">Bab {{ $table->chapter }}</div>
                                        </td>
                                        
                                        {{-- <td>
                                            <div class="font-medium max-w-50 md:max-w-xs text-wrap wrap-break-words">
                                                {{ $table->title }}
                                            </div>
                                        </td> --}}
                                        <td class="font-medium
                                        whitespace-nowrap">{{ $table->title }}</td>

                                        <td>{{ $table->updated_at->format('d M Y') }}</td>

                                        <td class="text-center space-x-1 
                                        whitespace-nowrap">
                                            <a href="{{ route('admin.statistical-table.edit', $table->id) }}" class="btn btn-soft btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.statistical-table.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tabel statistik ini?');" class="inline-block">
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
                    
                    @if(method_exists($tables, 'links'))
                        <div class="mt-6">
                        {{-- <div class="mt-6 flex justify-end"> --}}
                            {{ $tables->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-layout-admin>