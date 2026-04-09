<x-layout title="Galeri Kelurahan">
    <div class="hero bg-base-100">
        <div class="hero-content text-center py-8">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold">Galeri Kelurahan Baadia</h1>
                <p class="py-6">
                    Dokumentasi berbagai acara, kegiatan, dan momen penting yang telah dilaksanakan di kelurahan kami.
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 lg:px-0 
    {{-- py-12 md:py-16  --}}
    {{-- space-y-20 --}}
    {{-- space-y-6 --}}
    space-y-16
    ">  
        @forelse($galleries as $gallery)
            <div class="space-y-6">
                <div class="text-center md:text-left border-b border-base-300 pb-4">
                    <h2 class="text-3xl font-bold text-secondary">{{ $gallery->nama_kegiatan }}</h2>
                    <p class="text-base-content/60 mt-2 text-sm flex items-center justify-center md:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{-- Dipublikasikan pada: {{ $gallery->created_at->translatedFormat('d F Y') }} --}}
                        Dipublikasikan pada: {{ $gallery->created_at->format('d M Y') }}
                    </p>
                </div>

                @if($gallery->photos->isEmpty())
                    <div class="bg-base-200/50 rounded-box py-12 text-center">
                        <p class="text-base-content/60 italic">Belum ada foto dokumentasi untuk kegiatan ini.</p>
                    </div>
                @else
                    <div class="flex justify-center w-full">
                        <div class="carousel carousel-center bg-neutral rounded-box max-w-full space-x-4 p-4 w-fit shadow-xl">
                            @foreach($gallery->photos as $photo)
                                <div class="carousel-item rounded-box overflow-hidden">
                                    <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                    {{-- <img src="{{ Storage::url($photo->foto_path) }}"  --}}
                                         alt="Foto {{ $gallery->nama_kegiatan }}"
                                         {{-- class="h-72 md:h-96 object-cover hover:scale-105 transition-transform duration-500 cursor-pointer" /> --}}
                                         class="h-40 md:h-96 object-cover hover:scale-105 transition-transform duration-500 cursor-pointer" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div role="alert" class="alert alert-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Belum ada kegiatan di galeri.</span>
            </div>
        @endforelse
    </div>
</x-layout>