@props(['publications'])

@if($publications->isNotEmpty())
    <form action="{{ url()->current() }}" method="GET" {{ $attributes->merge(['class' => 'w-full sm:w-96']) }}>
        {{-- Pertahankan search/per_page/sort, reset ke halaman 1 --}}
        @foreach(request()->except(['publikasi', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <select name="publikasi" class="select select-sm md:select-md w-full" onchange="this.form.submit()">
            <option value="">Semua Publikasi</option>
            <option value="tanpa" @selected(request('publikasi') === 'tanpa')>Tanpa Publikasi</option>
            @foreach($publications as $publication)
                <option value="{{ $publication->id }}" @selected(request('publikasi') === (string) $publication->id)>
                    {{ $publication->title }}
                </option>
            @endforeach
        </select>
    </form>
@endif