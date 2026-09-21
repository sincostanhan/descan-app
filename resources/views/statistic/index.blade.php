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

        {{-- <div class="card bg-base-100 card-border shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary text-xl mb-4 border-b pb-2">Daftar Tabel Statistik</h2> --}}
        <x-section-card title="Daftar Tabel Statistik" title-size="text-xl">

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
                                <tr>
                                    <th class="transition-colors">
                                        <button type="button"
                                            class="w-full flex items-center gap-1 text-left {{ $titleProps->isDisabled ? 'cursor-not-allowed text-base-content/50' : 'cursor-pointer hover:bg-base-300' }}"
                                            @if($titleProps->isDisabled) disabled @endif
                                            @if(!$titleProps->isDisabled) onclick="window.location='{{ $titleProps->url }}'" @endif>
                                            Judul Tabel
                                            @if($titleProps->icon === 'arrow-up-down') <x-lucide-arrow-up-down class="w-4 h-4 text-base-content/40" />
                                            @elseif($titleProps->icon === 'arrow-up') <x-lucide-arrow-up class="w-4 h-4" />
                                            @else <x-lucide-arrow-down class="w-4 h-4" /> @endif
                                        </button>
                                    </th>
                                    <th>Sumber Data</th>
                                    <th class="text-center">Grafik</th>
                                    <th class="text-center">Unduh</th>
                                    <th class="text-center w-48 transition-colors">
                                        <button type="button"
                                            class="w-full flex items-center justify-center gap-1 {{ $updatedProps->isDisabled ? 'cursor-not-allowed text-base-content/50' : 'cursor-pointer hover:bg-base-300' }}"
                                            @if($updatedProps->isDisabled) disabled @endif
                                            @if(!$updatedProps->isDisabled) onclick="window.location='{{ $updatedProps->url }}'" @endif>
                                            Terakhir Diperbarui
                                            @if($updatedProps->icon === 'arrow-up-down') <x-lucide-arrow-up-down class="w-4 h-4 text-base-content/40" />
                                            @elseif($updatedProps->icon === 'arrow-up') <x-lucide-arrow-up class="w-4 h-4" />
                                            @else <x-lucide-arrow-down class="w-4 h-4" /> @endif
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tables as $table)
                                    <tr class="hover cursor-pointer transition-colors" onclick="window.location='{{ route('public.statistic.show', $table->id) }}'">
                                        <td class="font-medium whitespace-normal">
                                            {{ $table->title ?? $table->template->title }}
                                        </td>
                                        <td class="text-sm text-base-content/70">
                                            {{ $table->source ?: '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if($table->chart)
                                                <div class="badge badge-soft badge-success whitespace-nowrap">Ada</div>
                                            @else
                                                <div class="badge badge-outline whitespace-nowrap">Belum Ada</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                           <x-statistic-download-menu :statistic="$table" />
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
            {{-- </div>
        </div> --}}
        </x-section-card>
    </div>
</x-layout>