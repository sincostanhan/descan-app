@php
    $node = $node ?? [];
    $children = $node['children'] ?? [];
@endphp
<div class="header-node" data-axis="{{ $axis }}">
    <div class="node-row flex gap-2 items-start bg-base-200/40 p-3 rounded-box">
        <input type="hidden" class="node-id" value="{{ $node['id'] ?? '' }}">

        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-2">
            <input
                type="text"
                class="input input-sm node-label"
                placeholder="Label (misal: {{ $axis === 'row' ? 'RT 01' : 'Laki-laki' }})"
                value="{{ $node['label'] ?? '' }}">

            @if($axis === 'row')
                <input
                    type="text"
                    class="input input-sm node-rt-value"
                    placeholder="Nilai RT (opsional, untuk Dashboard Peta)"
                    value="{{ $node['rt_value'] ?? '' }}">
            @else
                <select class="select select-sm node-data-type">
                    <option value="numeric" {{ ($node['data_type'] ?? '') === 'numeric' ? 'selected' : '' }}>Hanya Angka</option>
                    <option value="text" {{ ($node['data_type'] ?? '') === 'text' ? 'selected' : '' }}>Hanya Teks</option>
                    <option value="both" {{ ($node['data_type'] ?? '') === 'both' ? 'selected' : '' }}>Angka & Teks</option>
                </select>
            @endif
        </div>

        <div class="flex gap-1 shrink-0">
            <button type="button" class="btn btn-xs btn-outline btn-add-child" title="Tambah sub-level di bawah baris/kolom ini">Sub</button>
            <button type="button" class="btn btn-xs btn-outline btn-add-sibling" title="Tambah baris/kolom sejajar">+</button>
            {{-- <button type="button" class="btn btn-xs btn-soft btn-error btn-remove-node" title="Hapus">✕</button> --}}
            <button type="button" class="btn btn-xs btn-soft btn-error btn-remove-node" title="Hapus">
                <x-lucide-x class="w-3.5 h-3.5" />
            </button>
        </div>
    </div>

    <div class="node-children ml-6 mt-2 space-y-2 border-l-2 border-base-300 pl-4">
        @foreach($children as $childNode)
            @include('admin-bps.statistic-templates.partials._header-node', ['node' => $childNode, 'axis' => $axis])
        @endforeach
    </div>
</div>