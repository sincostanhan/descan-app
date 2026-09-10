{{-- resources\views\components\nav-admin-bps.blade.php --}}

{{-- <div class="navbar bg-base-100 shadow-sm"> --}}
{{-- <div class="navbar bg-base-100 shadow-sm
sticky top-0 z-50">
    <div class="flex-1">
        <a class="btn btn-ghost text-xl">Panel Admin BPS</a>
    </div>
    <div class="flex-none">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            {{-- <button type="submit" class="btn btn-error btn-sm text-white">Keluar</button> --}
            <button type="submit" class="btn btn-soft btn-error btn-sm">Keluar</button>
        </form>
    </div>
</div> --}}


{{-- resources\views\components\nav-admin.blade.php --}}
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
            <li><a href="{{ route('admin-bps.users.index') }}">Users</a></li>
            <li><a href="{{ route('admin-bps.statistic-templates.index') }}">Template Tabel</a></li>
        </ul>
        </div>
        <a class="btn btn-ghost text-xl">Panel Admin BPS</a>
        {{-- <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost text-xl">
            Panel Admin {{ $globalSetting->village_name ?? 'Kelurahan Baadia' }}
        </a> --}}
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">        
            <li><a href="{{ route('admin-bps.users.index') }}">Users</a></li>
            <li><a href="{{ route('admin-bps.statistic-templates.index') }}">Template Tabel</a></li>
        </ul>
    </div>
    <div class="navbar-end">
        <form action="{{ route('logout') }}" method="POST" class="m-0 flex items-center">
            @csrf
            {{-- <button type="submit" class="btn btn-error btn-sm text-white"> --}}
            <button type="submit" class="btn btn-soft btn-error btn-sm">
                Keluar
            </button>
        </form>
    </div>
</div>