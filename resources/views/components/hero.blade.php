{{-- resources\views\components\hero.blade.php --}}

@props(['title', 'subtitle' => null])

{{--o Centered hero --}}
{{-- <div class="hero bg-base-200 min-h-screen"> --}}
{{-- <div class="hero bg-base-200 mb-8"> --}}
<div class="hero bg-base-100">
    {{-- <div class="hero-content text-center"> --}}
    <div class="hero-content text-center py-8">
        {{-- <div class="max-w-md"> --}}
        <div class="max-w-3xl">
            {{-- <h1 class="text-5xl font-bold">{{ $title }}</h1> --}}
            <h1 class="text-3xl font-bold">{{ $title }}</h1>

            @if($subtitle)
                {{-- <p class="py-6">{{ $subtitle }}</p> --}}
                <p class="py-3">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</div>