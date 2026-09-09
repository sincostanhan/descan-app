{{-- @if ($paginator->hasPages()) --}}
    {{-- <div class="join flex justify-center mt-6"> --}}
        
        {{-- Tombol Kiri (Previous) --}}
        {{-- @if ($paginator->onFirstPage()) --}}
            {{-- <button class="join-item btn btn-disabled">«</button> --}}
        {{-- @else --}}
            {{-- <a href="{{ $paginator->previousPageUrl() }}" class="join-item btn">«</a> --}}
        {{-- @endif --}}

        {{-- Tombol Tengah (Indikator Halaman) --}}
        {{-- <button class="join-item btn pointer-events-none"> --}}
            {{-- Page {{ $paginator->currentPage() }} --}}
            {{-- {{ $paginator->currentPage() }} --}}
        {{-- </button> --}}

        {{-- Tombol Kanan (Next) --}}
        {{-- @if ($paginator->hasMorePages()) --}}
            {{-- <a href="{{ $paginator->nextPageUrl() }}" class="join-item btn">»</a> --}}
        {{-- @else --}}
            {{-- <button class="join-item btn btn-disabled">»</button> --}}
        {{-- @endif --}}
        
    {{-- </div> --}}
{{-- @endif --}}

@if ($paginator->hasPages())
    <div class="join flex justify-center mt-6">

        {{-- Tombol Kiri (Previous) --}}
        @if ($paginator->onFirstPage())
            <button class="join-item btn btn-disabled"><x-lucide-chevron-left class="w-4 h-4" /></button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="join-item btn"><x-lucide-chevron-left class="w-4 h-4" /></a>
        @endif

        {{-- Tombol Tengah (Indikator Halaman) --}}
        <button class="join-item btn pointer-events-none">
            {{ $paginator->currentPage() }}
        </button>

        {{-- Tombol Kanan (Next) --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="join-item btn"><x-lucide-chevron-right class="w-4 h-4" /></a>
        @else
            <button class="join-item btn btn-disabled"><x-lucide-chevron-right class="w-4 h-4" /></button>
        @endif

    </div>
@endif