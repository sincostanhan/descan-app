<x-layout title="Metadata Statistik">
    <x-hero
        title="Metadata Statistik"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 space-y-6">
        <div class="flex gap-4 mb-8 flex-col sm:flex-row sm:justify-between sm:items-center">
            <form action="{{ url()->current() }}" method="GET" class="relative w-full sm:w-80">
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

            {{-- <div class="flex justify-end"> --}}
            <div class="flex justify-end gap-2">
                <form action="{{ url()->current() }}" method="GET">
                    @foreach(request()->except(['tahun', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <select name="tahun" class="select select-sm md:select-md w-36" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunOptions as $tahun)
                            <option value="{{ $tahun }}" @selected((string) request('tahun') === (string) $tahun)>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </form>

                <x-pagination-dropdown :perPage="$perPage" />
            </div>
        </div>

        @forelse($metadataStatistiks as $item)
            @php
                $isImage = \Illuminate\Support\Str::endsWith(strtolower($item->file_path), ['.jpg', '.jpeg', '.png']);
                $coverSrc = $item->cover_path ? asset('storage/' . $item->cover_path) : ($isImage ? asset('storage/' . $item->file_path) : null);
            @endphp

            {{-- description sengaja tidak dikirim -> paragraf deskripsi otomatis tidak tampil (lihat perubahan document-card.blade.php) --}}
            <x-document-card
                :title="$item->title"
                :date="$item->created_at->translatedFormat('d F Y')"
                :coverUrl="$coverSrc"
                :fileUrl="asset('storage/' . $item->file_path)"
                :downloadUrl="route('metadata-statistik.download', $item->id)"
                buttonText="Lihat Metadata"
            />
        @empty
            {{-- @if(request('search')) --}}
            @if(request('search') && request('tahun'))
                <x-empty-alert message="Data dengan judul '{{ request('search') }}' pada tahun {{ request('tahun') }} tidak ditemukan." />
            @elseif(request('search'))
                <x-empty-alert message="Data dengan judul '{{ request('search') }}' tidak ditemukan." />
            @elseif(request('tahun'))
                <x-empty-alert message="Belum ada metadata statistik untuk tahun {{ request('tahun') }}." />
            @else
                <x-empty-alert message="Belum ada data metadata statistik." />
            @endif
        @endforelse

        @if(method_exists($metadataStatistiks, 'links') && $metadataStatistiks->isNotEmpty())
            <div class="mt-6">
                {{ $metadataStatistiks->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-layout>