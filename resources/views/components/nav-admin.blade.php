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
            <li><a href="{{ route('admin.home.edit') }}">Beranda</a></li>
            <li>
            <a>Profil</a>
            <ul class="p-2">
                <li><a href="{{ route('admin.about.edit', 1) }}">Tentang Kami</a></li>
                {{-- <li><a href="{{ route('admin.history.edit', 1) }}">Sejarah</a></li> --}}
                <li><a href="{{ route('admin.history.edit') }}">Sejarah</a></li>
                <li><a href="{{ route('admin.organization.edit', 1) }}">Organisasi</a></li>
                <li><a href="{{ route('admin.gallery.index', 1) }}">Galeri</a></li>
            </ul>
            </li>
            <li>
            <a>Data</a>
            <ul class="p-2">
                <li><a href="{{ route('admin.statistical-table.index') }}">Tabel dan Grafik</a></li>
                <li><a href="{{ route('admin.publication.index') }}">Publikasi</a></li>
                <li><a href="{{ route('admin.infographic.index') }}">Infografis</a></li>
                <li><a href="#">Metadata</a></li>
            </ul>
            </li>
            {{-- <li><a href="#">#</a></li> --}}
            <li><a href="{{ route('admin.setting.edit') }}">Pengaturan Web</a></li>
        </ul>
        </div>
        <a class="btn btn-ghost text-xl">Panel Admin</a>
        {{-- <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost text-xl">
            Panel Admin {{ $globalSetting->village_name ?? 'Kelurahan Baadia' }}
        </a> --}}
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
        <li><a href="{{ route('admin.home.edit') }}">Beranda</a></li>
        <li>
            <details>
            <summary>Profil</summary>
            <ul class="p-2 bg-base-100 w-40 z-1">
                <li><a href="{{ route('admin.about.edit', 1) }}">Tentang Kami</a></li>
                {{-- <li><a href="{{ route('admin.history.edit', 1) }}">Sejarah</a></li> --}}
                <li><a href="{{ route('admin.history.edit') }}">Sejarah</a></li>
                <li><a href="{{ route('admin.organization.edit', 1) }}">Organisasi</a></li>
                <li><a href="{{ route('admin.gallery.index', 1) }}">Galeri</a></li>
            </ul>
            </details>
        </li>
        <li>
            <details>
            <summary>Data</summary>
            <ul class="p-2 bg-base-100 w-40 z-1">
                <li><a href="{{ route('admin.statistical-table.index') }}">Tabel dan Grafik</a></li>
                <li><a href="{{ route('admin.publication.index') }}">Publikasi</a></li>
                <li><a href="{{ route('admin.infographic.index') }}">Infografis</a></li>
                <li><a href="#">Metadata</a></li>
            </ul>
            </details>
        </li>
        {{-- <li><a href="#">#</a></li> --}}
        <li><a href="{{ route('admin.setting.edit') }}">Pengaturan Web</a></li>
        </ul>
    </div>
    <div class="navbar-end">
        {{-- <a class="btn">Button</a> --}}
        {{-- <a href="{{ url('/') }}" target="_blank" class="btn btn-primary btn-sm  --}}
        <a 
            {{-- href="{{ route('home.index', 1) }}"  --}}
            href="{{ route('home.index') }}" 
            target="_blank" 
            class="btn btn-primary btn-sm 
                mr-4 text-white hidden sm:inline-flex"
        >Lihat Web Publik</a>
        <form action="{{ route('logout') }}" method="POST" class="m-0 flex items-center">
            @csrf
            <button type="submit" class="btn btn-error btn-sm text-white">
                Keluar
            </button>
        </form>
    </div>
</div>