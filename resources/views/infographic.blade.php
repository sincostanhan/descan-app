<x-layout title="Infografis">
    <x-hero
        title="Infografis"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 
    space-y-6
    {{-- mb-12 --}}
    ">
        {{-- <div class="flex justify-end">
            <x-pagination-dropdown :perPage="$perPage" />
        </div> --}}
        <div class="
            {{-- flex gap-4 mb-4 --}}
            flex gap-4 mb-8
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

        @forelse($infographics as $info)
            {{-- Logika Penentuan Cover Infografis --}}
            @php
                $isImage = \Illuminate\Support\Str::endsWith(strtolower($info->file_path), ['.jpg', '.jpeg', '.png']);
                $coverSrc = $info->cover_path ? asset('storage/' . $info->cover_path) : ($isImage ? asset('storage/' . $info->file_path) : null);
            @endphp

            <x-document-card 
                :title="$info->title"
                :description="$info->description"
                :date="$info->created_at->translatedFormat('d F Y')"
                :coverUrl="$coverSrc"
                :fileUrl="asset('storage/' . $info->file_path)"
                buttonText="Lihat Infografis"
            />
        @empty
            {{-- <x-empty-alert message="Belum ada data infografis." /> --}}
            @if(request('search'))
                <x-empty-alert message="Data dengan judul '{{ request('search') }}' tidak ditemukan." />
            @else
                <x-empty-alert message="Belum ada data infografis." />
            @endif
        @endforelse
        
        @if(method_exists($infographics, 'links') && $infographics->isNotEmpty())
            {{-- <div class="pt-4 flex justify-center"> --}}
            <div class="mt-6">
                {{-- {{ $infographics->links() }} --}}
                {{ $infographics->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-layout>