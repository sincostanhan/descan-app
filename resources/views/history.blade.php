<x-layout title="Sejarah">
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
                    <x-empty-alert message="Data sejarah kelurahan belum tersedia." />
                @endif
            </div>
        </div>
    </div>
</x-layout>