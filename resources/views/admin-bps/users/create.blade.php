<x-layout-admin-bps title="Tambah Admin Kelurahan">
    <x-hero
        title="Tambah Admin"
    />

    <div class="max-w-2xl mx-auto px-4 lg:px-0">
        <div class="card bg-base-100 card-border shadow-lg">
            <div class="card-body">
                <form action="{{ route('admin-bps.users.store') }}" method="POST">
                    @csrf

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Nama Lengkap</legend>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            {{-- placeholder="Contoh: "  --}}
                            class="input w-full" 
                            required />
                        <x-forms.error name="name" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Username</legend>
                        <input 
                            type="text" 
                            name="username" 
                            value="{{ old('username') }}" 
                            {{-- placeholder="admin_baadia"  --}}
                            class="input w-full" 
                            required />
                        <p class="label text-base-content/70">Gunakan huruf kecil, angka, dan underscore saja.</p>
                        <x-forms.error name="username" />
                    </fieldset>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend text-base">Password</legend>
                            <input 
                                type="password" 
                                name="password" 
                                class="input w-full" 
                                required />
                            <x-forms.error name="password" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend text-base">Konfirmasi Password</legend>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                class="input w-full" 
                                required />
                        </fieldset>
                    </div>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Kelurahan</legend>
                        <select 
                            name="village_id" 
                            class="select w-full @error('village_id') select-error @enderror" 
                            required>
                            <option value="" disabled selected>-- Pilih Kelurahan --</option>
                            @foreach($villages as $village)
                                <option value="{{ $village->id }}" {{ old('village_id') == $village->id ? 'selected' : '' }}>
                                    {{ $village->name }} ({{ $village->subdomain }})
                                </option>
                            @endforeach
                        </select>
                        <x-forms.error name="village_id" />
                    </fieldset>

                    <div class="card-actions justify-end mt-8 border-t pt-4">
                        <a href="{{ route('admin-bps.users.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-secondary">Simpan Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout-admin-bps>