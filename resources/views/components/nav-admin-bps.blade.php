{{-- resources\views\components\nav-admin-bps.blade.php --}}

{{-- <div class="navbar bg-base-100 shadow-sm"> --}}
<div class="navbar bg-base-100 shadow-sm
sticky top-0 z-50">
    <div class="flex-1">
        <a class="btn btn-ghost text-xl">Panel Admin BPS</a>
    </div>
    <div class="flex-none">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            {{-- <button type="submit" class="btn btn-error btn-sm text-white">Keluar</button> --}}
            <button type="submit" class="btn btn-soft btn-error btn-sm">Keluar</button>
        </form>
    </div>
</div>