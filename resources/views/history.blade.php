<x-layout title="Sejarah Kelurahan">
    <div class="hero bg-base-100">
        <div class="hero-content text-center py-8">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold">Sejarah Kelurahan Baadia</h1>
                
                @if($history && $history->penulis)
                    <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-4 text-sm">
                        <div class="badge
                        text-base-content/70
                        px-4 py-4">
                            <x-lucide-user class="w-5 h-5 text-secondary" />
                            <span>Oleh: <span class="text-base-content
                                font-medium">{{ $history->penulis }}</span></span>
                        </div>
                        <div class="badge
                        text-base-content/70
                        px-4 py-4">
                            <x-lucide-calendar class="w-5 h-5 text-secondary" />
                            <span>Update: <span class="text-base-content 
                                font-medium">{{ $history->updated_at->format('d M Y') }}</span></span>
                                {{-- font-medium">{{ $history->updated_at->format('Y-m-d') }}</span></span> --}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 lg:px-0">
        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                @if($history)
                    {{-- <p>a</p> --}}
                    <div class="text-base-content/80 text-justify text-sm md:text-base leading-relaxed space-y-4">
                        {!! nl2br(e($history->konten)) !!}
                    </div>
                @else
                    <div role="alert" class="alert alert-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Data sejarah kelurahan belum tersedia.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>