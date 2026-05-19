<x-layout title="Galeri Kelurahan">
    <x-hero
        title="Galeri Kelurahan"
        subtitle="Dokumentasi berbagai acara, kegiatan, dan momen penting yang telah dilaksanakan di kelurahan kami."
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 
    {{-- py-12 md:py-16  --}}
    {{-- space-y-20 --}}
    {{-- space-y-6 --}}
    space-y-16
    ">  
        @forelse($galleries as $gallery)
            <div class="space-y-6">
                <div class="text-center md:text-left border-b 
                {{-- border-base-300  --}}
                {{-- border-secondary  --}}
                {{-- border-neutral-content  --}}
                border-base-content/60
                pb-4">
                    <h2 class="text-3xl text-secondary
                    {{-- font-bold --}}
                    font-semibold
                    {{-- ">{{ $gallery->nama_kegiatan }}</h2> --}}
                    ">{{ $gallery->judul }}</h2>
                    {{-- <h2 class="text-2xl font-bold text-secondary">{{ $gallery->nama_kegiatan }}</h2> --}}
                    <p class="text-base-content/60 mt-2 
                    text-sm 
                    flex items-center justify-center md:justify-start">
                        {{-- <x-lucide-calendar class="w-5 h-5 text-secondary" /> --}}
                        <x-lucide-calendar class="w-4 h-4 mr-1" />
                        {{-- Dipublikasikan pada: {{ $gallery->created_at->format('d M Y') }} --}}
                        {{-- Dipublikasikan pada: {{ $gallery->created_at->translatedFormat('d F Y') }} --}}
                        Dipublikasikan pada {{ $gallery->created_at->translatedFormat('d F Y') }}
                    </p>
                </div>

                @if($gallery->photos->isEmpty())
                    {{-- <div role="alert" class="alert alert-info alert-soft  --}}
                    <div role="alert" class="alert alert-warning alert-soft 
                    h-20 flex items-center justify-center">
                        {{-- <span>Belum ada foto.</span> --}}
                        <span class="italic justify-center">Belum ada foto.</span>
                    </div>
                @else
                    <div class="flex justify-center w-full">
                        <div class="carousel carousel-center 
                        {{-- bg-neutral  --}}
                        bg-base-200
                        rounded-box max-w-full space-x-4 p-4 
                        w-fit 
                        shadow-xl">
                            @foreach($gallery->photos as $photo)
                                {{-- <div class="carousel-item"> --}}
                                <div class="carousel-item rounded-box overflow-hidden">
                                    <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                    {{-- <img src="{{ Storage::url($photo->foto_path) }}"  --}}
                                         {{-- alt="Foto {{ $gallery->nama_kegiatan }}" --}}
                                         alt="Foto {{ $gallery->judul }}"
                                         {{-- class="h-72 md:h-96 object-cover hover:scale-105 transition-transform duration-500 cursor-pointer" /> --}}
                                         class="h-40 md:h-96 
                                         object-cover 
                                         hover:scale-105 transition-transform duration-500 
                                         {{-- cursor-pointer --}}
                                         " />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <x-empty-alert message="Belum ada kegiatan di galeri." />
        @endforelse
    </div>
</x-layout>