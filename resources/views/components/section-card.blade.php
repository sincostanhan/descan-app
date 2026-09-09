@props([
    'title' => null,
    'titleColor' => 'text-secondary',
    'titleSize' => 'text-2xl',
])

<div {{ $attributes->merge(['class' => 'card bg-base-100 card-border shadow-lg']) }}>
    <div class="card-body">
        @if($title)
            <h2 class="card-title {{ $titleColor }} {{ $titleSize }} mb-4 border-b pb-2">{{ $title }}</h2>
        @endif

        {{ $slot }}
    </div>
</div>