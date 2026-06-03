<x-layout title="Tabel Statistik">
    <x-hero 
        title="Tabel Statistik" 
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        {{-- <div class="flex justify-end mb-4">
            <x-pagination-dropdown :perPage="$perPage" />
        </div> --}}
        <div class="
            flex gap-4 mb-4
            flex-col
            sm:flex-row sm:justify-between sm:items-center"
        >
            <form action="{{ url()->current() }}" method="GET" class="relative w-full 
                {{-- sm:w-64"> --}}
                sm:w-80">
                {{-- Pertahankan parameter limit per_page atau parameter sorting jika ada --}}
                @foreach(request()->except(['search', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari judul..." 
                    class="input input-sm md:input-md input-bordered w-full pr-10" 
                />
                
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-base-content/50 hover:text-primary">
                    <x-lucide-search class="w-4 h-4 md:w-5 md:h-5" />
                </button>
            </form>

            <div class="flex justify-end">
                <x-pagination-dropdown :perPage="$perPage" />
            </div>
        </div>

        <div class="card bg-base-100 card-border shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary text-xl mb-4 border-b pb-2">Daftar Tabel Statistik</h2>

                @if($tables->isEmpty())
                    {{-- <x-empty-alert message="Belum ada data statistik yang dipublikasikan saat ini." /> --}}
                    @if(request('search'))
                        <x-empty-alert message="Data dengan judul '{{ request('search') }}' tidak ditemukan." />
                    @else
                        <x-empty-alert message="Belum ada tabel statistik yang ditambahkan." />
                    @endif
                @else
                    <div class="overflow-x-auto rounded-box border-base-200 border">
                        <table class="table table-zebra w-full">
                            <thead class="bg-base-200/50 text-base-content text-sm select-none">
                                @php
                                    // Ambil status sorting saat ini
                                    $currentSortBy = request('sort_by');
                                    $currentSortDir = request('sort_dir', 'asc');
                                    $isAnyActive = !empty($currentSortBy);

                                    // Fungsi bantuan untuk menentukan status, url, dan ikon per kolom
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
                                                // Reset urutan (kembali ke default arrow-up-down)
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
                                    
                                    {{-- Kolom Publikasi & Bab --}}
                                    {{-- Hapus opacity-40 di sini --}}
                                    <th class="{{ $pubProps->isDisabled ? 'cursor-not-allowed' : 'cursor-pointer hover:bg-base-300' }} transition-colors"
                                        @if(!$pubProps->isDisabled) onclick="window.location='{{ $pubProps->url }}'" @endif>
                                        <div class="flex items-center gap-1">
                                            Publikasi & Bab
                                            @if($pubProps->icon === 'arrow-up-down') <x-lucide-arrow-up-down class="w-4 h-4 text-base-content/40" />
                                            @elseif($pubProps->icon === 'arrow-up') <x-lucide-arrow-up class="w-4 h-4" />
                                            @else <x-lucide-arrow-down class="w-4 h-4" /> @endif
                                        </div>
                                    </th>

                                    {{-- Kolom Judul Tabel --}}
                                    {{-- Hapus opacity-40 di sini --}}
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
                                    {{-- Hapus opacity-40 di sini --}}
                                    <th class="text-center w-48 {{ $updatedProps->isDisabled ? 'cursor-not-allowed' : 'cursor-pointer hover:bg-base-300' }} transition-colors"
                                        @if(!$updatedProps->isDisabled) onclick="window.location='{{ $updatedProps->url }}'" @endif>
                                        <div class="flex items-center justify-center gap-1">
                                            Terakhir Diperbarui
                                            @if($updatedProps->icon === 'arrow-up-down') <x-lucide-arrow-up-down class="w-4 h-4 text-base-content/40" />
                                            @elseif($updatedProps->icon === 'arrow-up') <x-lucide-arrow-up class="w-4 h-4" />
                                            @else <x-lucide-arrow-down class="w-4 h-4" /> @endif
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tables as $index => $table)
                                    <tr class="hover cursor-pointer transition-colors" onclick="window.location='{{ route('public.statistic.show', $table->id) }}'">
                                        <th class="text-center">{{ $tables->firstItem() + $index }}</th>
                                        
                                        {{-- <td class="text-center">
                                            <div class="badge badge-secondary badge-outline badge-sm">Bab {{ $table->chapter }}</div>
                                        </td> --}}
                                        {{-- <td class="text-center"> --}}
                                        <td>
                                            <div class="font-medium
                                            whitespace-nowrap">{{ $table->publication }}</div>
                                            <div class="badge badge-secondary badge-outline badge-sm mt-1">Bab {{ $table->chapter }}</div>
                                        </td>
                                        
                                        <td class="font-medium whitespace-normal">
                                            <a href="{{ route('public.statistic.show', $table->id) }}" class="hover:text-primary">
                                                {{ $table->title }}
                                            </a>
                                        </td>

                                        <td class="text-center">
                                            {{ $table->updated_at->translatedFormat('d M Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if(method_exists($tables, 'links'))
                        <div class="mt-6">
                        {{-- <div class="mt-6 flex justify-end"> --}}
                            {{-- {{ $tables->links() }} --}}
                            {{ $tables->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-layout>