{{-- resources\views\components\nav.blade.php --}}

@php
    // Cek apakah sejarah aktif
    $isHistoryActive = \App\Models\History::first()?->is_active ?? false;
@endphp

<div class="navbar bg-base-100 shadow-sm
sticky top-0 z-50">
    <div class="navbar-start">
        <div class="dropdown">
        <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /> </svg>
        </div>
        <ul
            tabindex="-1"
            class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
            <li><a href="{{ route('home.index') }}">Beranda</a></li>
            <li>
            <a>Profil</a>
            <ul class="p-2">
                {{-- <li><a href="{{ route('about.index', 1) }}">Tentang Kami</a></li> --}}
                <li><a href="{{ route('about.index') }}">Tentang Kami</a></li>
                {{-- <li><a href="{{ route('history.index', 1) }}">Sejarah</a></li> --}}
                @if($isHistoryActive)
                    <li><a href="{{ route('history.index') }}">Sejarah</a></li>
                @endif
                {{-- <li><a href="{{ route('organization.index', 1) }}">Organisasi</a></li> --}}
                <li><a href="{{ route('organization.index') }}">Organisasi</a></li>
                {{-- <li><a href="{{ route('gallery.index', 1) }}">Galeri</a></li> --}}
                <li><a href="{{ route('gallery.index') }}">Galeri</a></li>
            </ul>
            </li>
            <li>
            <a>Data</a>
            <ul class="p-2">
                <li><a href="{{ route('public.statistic.index') }}">Tabel dan Grafik</a></li>
                <li><a href="{{ route('publication.index') }}">Publikasi</a></li>
                <li><a href="{{ route('infographic.index') }}">Infografis</a></li>
                <li><a href="#">Metadata</a></li>
            </ul>
            </li>
            {{-- <li><a href="#">#</a></li> --}}
        </ul>
        </div>
        {{-- <a class="btn btn-ghost text-xl">Kelurahan Baadia</a> --}}
        <a href="{{ url('/') }}" class="btn btn-ghost text-xl 
        flex items-center gap-2">
            @if(isset($globalSetting) && $globalSetting->village_logo)
                <img src="{{ asset('storage/' . $globalSetting->village_logo) }}" alt="Logo" class="h-8 w-8 object-contain" />
            @endif
            {{ $globalSetting->village_name ?? 'Kelurahan Baadia' }}
        </a>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
        <li><a href="{{ route('home.index') }}">Beranda</a></li>
        <li>
            <details>
            <summary>Profil</summary>
            <ul class="p-2 bg-base-100 w-40 z-1">
                {{-- <li><a href="{{ route('about.index', 1) }}">Tentang Kami</a></li> --}}
                <li><a href="{{ route('about.index') }}">Tentang Kami</a></li>
                {{-- <li><a href="{{ route('history.index', 1) }}">Sejarah</a></li> --}}
                @if($isHistoryActive)
                    <li><a href="{{ route('history.index') }}">Sejarah</a></li>
                @endif
                {{-- <li><a href="{{ route('organization.index', 1) }}">Organisasi</a></li> --}}
                <li><a href="{{ route('organization.index') }}">Organisasi</a></li>
                {{-- <li><a href="{{ route('gallery.index', 1) }}">Galeri</a></li> --}}
                <li><a href="{{ route('gallery.index') }}">Galeri</a></li>
            </ul>
            </details>
        </li>
        <li>
            <details>
            <summary>Data</summary>
            <ul class="p-2 bg-base-100 w-40 z-1">
                <li><a href="{{ route('public.statistic.index') }}">Tabel dan Grafik</a></li>
                <li><a href="{{ route('publication.index') }}">Publikasi</a></li>
                <li><a href="{{ route('infographic.index') }}">Infografis</a></li>
                <li><a href="#">Metadata</a></li>
            </ul>
            </details>
        </li>
        {{-- <li><a href="#">#</a></li> --}}
        </ul>
    </div>
    <div class="navbar-end">
        {{-- <a href="{{ route('login') }}" class="btn btn-primary btn-sm text-white"> --}}
        <a href="{{ route('login') }}" class="btn btn-sm btn-outline">
            Login
        </a>
    </div>
</div>