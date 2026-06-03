@props(['perPage' => 20])

<div class="flex items-center gap-2">
    <span class="text-sm text-base-content/70">Tampilkan:</span>
    
    <select 
        class="select select-sm w-20" 
        onchange="window.location.href=this.value"
    >
        <option value="{{ request()->fullUrlWithQuery(['per_page' => 1, 'page' => null]) }}" {{ $perPage == 1 ? 'selected' : '' }}>1</option>
        <option value="{{ request()->fullUrlWithQuery(['per_page' => 10, 'page' => null]) }}" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
        <option value="{{ request()->fullUrlWithQuery(['per_page' => 20, 'page' => null]) }}" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
        <option value="{{ request()->fullUrlWithQuery(['per_page' => 50, 'page' => null]) }}" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
        <option value="{{ request()->fullUrlWithQuery(['per_page' => 100, 'page' => null]) }}" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
    </select>
    
    <span class="text-sm text-base-content/70">data per halaman</span>
</div>