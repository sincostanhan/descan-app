<x-layout-admin title="Publikasi">
    <x-hero
        title="Publikasi"
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

            <div class="flex flex-row justify-between items-center">
                <x-pagination-dropdown :perPage="$perPage" />

                
                <a class="btn btn-sm md:btn-md 
                {{-- btn-secondary"  --}}
                btn-secondary shrink-0" 
                href="{{ route('admin.publication.create') }}">
                    <x-lucide-plus class="w-5 h-5" /> Tambah Publikasi
                </a>
            </div>    
        </div>

        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Daftar Publikasi</h2>

                @if($publications->isEmpty())
                    {{-- <x-empty-alert message="Belum ada dokumen publikasi." /> --}}
                    @if(request('search'))
                        <x-empty-alert message="Data dengan judul '{{ request('search') }}' tidak ditemukan." />
                    @else
                        <x-empty-alert message="Belum ada dokumen publikasi." />
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

                                    $titleProps = $getSortProps('title');
                                    $updatedProps = $getSortProps('updated_at');
                                @endphp

                                <tr>
                                    {{-- <th class="w-16 text-center">No</th> --}}
                                    <th class="w-16">No</th>
                                    
                                    {{-- Kolom Judul Publikasi --}}
                                    <th class="{{ $titleProps->isDisabled ? 'cursor-not-allowed' : 'cursor-pointer hover:bg-base-300' }} transition-colors"
                                        @if(!$titleProps->isDisabled) onclick="window.location='{{ $titleProps->url }}'" @endif>
                                        <div class="flex items-center gap-1">
                                            Publikasi
                                            @if($titleProps->icon === 'arrow-up-down') <x-lucide-arrow-up-down class="w-4 h-4 text-base-content/40" />
                                            @elseif($titleProps->icon === 'arrow-up') <x-lucide-arrow-up class="w-4 h-4" />
                                            @else <x-lucide-arrow-down class="w-4 h-4" /> @endif
                                        </div>
                                    </th>

                                    <th class="text-center">File</th>

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
                                @foreach($publications as $pub)
                                    <tr>
                                        {{-- <th>{{ $loop->iteration }}</th> --}}
                                        <th>{{ $publications->firstItem() + $loop->index }}</th>

                                        {{-- <td class="font-medium">{{ $pub->title }}</td> --}}
                                        
                                        {{-- Penggabungan Cover dan Judul dengan Flexbox --}}
                                        <td>
                                            <div class="flex items-center gap-4">
                                                <div class="avatar">
                                                    <div class="w-12 h-12 
                                                    rounded border border-base-300 flex items-center justify-center bg-base-200">
                                                        {{-- Cek Cover Ekstrak --}}
                                                        @if($pub->cover_path)
                                                            <img src="{{ asset('storage/' . $pub->cover_path) }}" alt="Cover" class="object-cover" />
                                                        @else
                                                            <x-lucide-file-text class="w-6 h-6 text-base-content/40" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-medium max-w-50 md:max-w-xs text-wrap wrap-break-words">{{ $pub->title }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td
                                            class="text-center
                                            whitespace-nowrap"
                                        >
                                            <a 
                                                href="{{ asset('storage/' . $pub->file_path) }}" 
                                                target="_blank" 
                                                {{-- class="link link-primary" --}}
                                                class="btn btn-info btn-sm text-white"
                                            >
                                                {{-- Lihat PDF --}}
                                                <x-lucide-external-link class="w-4 h-4 mr-1"/> Lihat File
                                            </a>
                                        </td>
                                        <td>{{ $pub->updated_at->format('d M Y') }}</td>
                                        <td class="text-center space-x-1 
                                        whitespace-nowrap">
                                            <a href="{{ route('admin.publication.edit', $pub->id) }}" class="btn btn-warning btn-sm 
                                                text-white">Edit</a>
                                            <form action="{{ route('admin.publication.destroy', $pub->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus publikasi ini?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error btn-sm 
                                                text-white">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if(method_exists($publications, 'links'))
                        <div class="mt-6">
                        {{-- <div class="mt-6 flex justify-end"> --}}
                            {{-- {{ $publications->links() }} --}}
                            {{ $publications->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-layout-admin>