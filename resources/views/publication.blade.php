<x-layout title="Publikasi">
    <x-hero
        title="Publikasi"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 
    space-y-6">
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
        
        @forelse($publications as $pub)
            <x-document-card 
                :title="$pub->title"
                :description="$pub->description"
                :date="$pub->created_at->translatedFormat('d F Y')"
                :coverUrl="$pub->cover_path ? asset('storage/' . $pub->cover_path) : null"
                :fileUrl="asset('storage/' . $pub->file_path)"
                buttonText="Lihat Publikasi"
            />
        @empty
            {{-- <x-empty-alert message="Belum ada data publikasi." /> --}}
            @if(request('search'))
                <x-empty-alert message="Data dengan judul '{{ request('search') }}' tidak ditemukan." />
            @else
                <x-empty-alert message="Belum ada data publikasi." />
            @endif
        @endforelse
        @if(method_exists($publications, 'links') && $publications->isNotEmpty())
            {{-- <div class="pt-4 flex justify-center"> --}}
            <div class="mt-6">
                {{-- {{ $publications->links() }} --}}
                {{ $publications->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-layout>