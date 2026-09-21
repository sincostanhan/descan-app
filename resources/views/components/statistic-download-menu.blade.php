{{-- resources\views\components\statistic-download-menu.blade.php --}}

@props(['statistic', 'size' => 'btn-sm'])

<div class="dropdown dropdown-end" onclick="event.stopPropagation()">
    <div tabindex="0" role="button" class="btn {{ $size }} btn-outline btn-secondary">
        <x-lucide-download class="w-4 h-4 mr-1" /> Unduh
    </div>
    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-10 w-44 p-2 shadow border border-base-200">
        <li><a href="{{ route('public.statistic.download', [$statistic->id, 'xlsx']) }}">Excel (.xlsx)</a></li>
        <li><a href="{{ route('public.statistic.download', [$statistic->id, 'csv']) }}">CSV (.csv)</a></li>
        <li><a href="{{ route('public.statistic.download', [$statistic->id, 'json']) }}">JSON (.json)</a></li>
    </ul>
</div>