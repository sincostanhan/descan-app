{{-- resources\views\admin-bps\users\index.blade.php --}}

<x-layout-admin-bps>
    <x-hero
        title="Admin Kelurahan"
        subtitle="Daftar akun pengelola website kelurahan cantik"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <div class="flex justify-end mb-6">
            <a class="btn btn-sm md:btn-md 
            btn-secondary" 
            href="{{ route('admin-bps.users.create') }}">
                <x-lucide-plus class="w-5 h-5" /> Tambah Admin
            </a>
        </div>

        <div class="card bg-base-100 
        card-border
        shadow-lg">
            @if($admins->isEmpty())
                <x-empty-alert message="Belum ada admin kelurahan yang terdaftar." />
            @else
                <div class="overflow-x-auto
                rounded-box border-base-200
                border">
                    <table class="table table-zebra 
                    w-full">
                        <thead class="bg-base-200/50 text-base-content 
                        text-sm">
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Kelurahan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>    
                            @forelse($admins as $admin)
                                <tr>
                                    <td class="font-medium max-w-50 md:max-w-xs text-wrap wrap-break-words">{{ $admin->name }}</td>
                                    <td>{{ $admin->username }}</td>
                                    <td>
                                        @if($admin->village)
                                            {{-- <div class="badge badge-primary badge-outline">{{ $admin->village->name }}</div> --}}
                                            {{-- <div class="badge badge-soft badge-primary badge-outline">{{ $admin->village->name }}</div> --}}
                                            <div class="badge badge-outline
                                            whitespace-nowrap">{{ $admin->village->name }}</div>
                                        {{-- @else --}}
                                            {{-- <span class="text-base-content/50 italic text-sm">Belum ditugaskan</span> --}}
                                        @endif
                                    </td>
                                    <td class="flex justify-center gap-2">
                                        {{-- <a href="{{ route('admin-bps.users.edit', $admin) }}" class="btn btn-warning btn-sm  --}}
                                        <a href="{{ route('admin-bps.users.edit', $admin) }}" class="btn btn-soft btn-warning btn-sm 
                                            {{-- text-white">Edit</a> --}}
                                            ">Edit</a>
                                        {{-- <form action="{{ route('admin-bps.users.destroy', $admin) }}" method="POST"  --}}
                                        {{-- onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?')" --}}
                                        {{-- class="inline-block"> --}}
                                            {{-- @csrf --}}
                                            {{-- @method('DELETE') --}}
                                            {{-- <button type="submit" class="btn btn-error btn-sm  --}}
                                            {{-- <button type="submit" class="btn btn-soft btn-error btn-sm  --}}
                                            {{-- text-white">Hapus</button> --}}
                                            {{-- ">Hapus</button> --}}
                                        {{-- </form> --}}
                                        <form id="form-delete-admin-{{ $admin->id }}" action="{{ route('admin-bps.users.destroy', $admin) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" onclick="document.getElementById('modal_confirm_delete_admin_{{ $admin->id }}').showModal()" class="btn btn-soft btn-error btn-sm">
                                            Hapus
                                        </button>

                                        <dialog id="modal_confirm_delete_admin_{{ $admin->id }}" class="modal">
                                            <div class="modal-box">
                                                <div class="flex flex-col items-center text-center">
                                                    <x-lucide-triangle-alert class="w-14 h-14 text-error mb-4" />
                                                    <h3 class="font-bold text-xl text-base-content">Konfirmasi Hapus</h3>
                                                    <p class="py-4 text-base-content/80">Apakah Anda yakin ingin menghapus admin ini?</p>
                                                </div>
                                                <div class="modal-action justify-center">
                                                    <form method="dialog">
                                                        <button class="btn btn-ghost">Batal</button>
                                                    </form>
                                                    <button type="submit" form="form-delete-admin-{{ $admin->id }}" class="btn btn-error">
                                                        Ya, Hapus
                                                    </button>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop">
                                                <button>close</button>
                                            </form>
                                        </dialog>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layout-admin-bps>