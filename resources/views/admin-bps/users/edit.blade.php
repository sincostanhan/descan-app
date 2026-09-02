{{-- resources\views\admin-bps\users\edit.blade.php --}}

<x-layout-admin-bps title="Panel Admin BPS | Edit Admin">
    <x-hero
        title="Edit Admin"
    />

    <div class="max-w-2xl mx-auto px-4 lg:px-0">
        <div class="card bg-base-100 card-border shadow-lg">
            <div class="card-body">
                <form action="{{ route('admin-bps.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Nama Lengkap</legend>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', $user->name) }}"
                            class="input w-full" 
                            required />
                        <x-forms.error name="name" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Username</legend>
                        <input 
                            type="text" 
                            name="username" 
                            value="{{ old('username', $user->username) }}"
                            class="input w-full" 
                            required />
                        <x-forms.error name="username" />
                    </fieldset>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend text-base">Password Baru</legend>
                            <input 
                                type="password" 
                                name="password" 
                                class="input w-full" />
                            <p class="fieldset-label text-base-content/70">Kosongkan jika tidak ingin mengubah password.</p>
                            <x-forms.error name="password" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend text-base">Konfirmasi Password Baru</legend>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                class="input w-full" />
                        </fieldset>
                    </div>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Kelurahan</legend>
                        <select 
                            name="village_id" 
                            class="select w-full @error('village_id') select-error @enderror" 
                            required>
                            <option value="" disabled>-- Pilih Kelurahan --</option>
                            @foreach($villages as $village)
                                <option value="{{ $village->id }}" {{ (old('village_id', $user->village_id) == $village->id) ? 'selected' : '' }}>
                                    {{ $village->name }} ({{ $village->subdomain }})
                                </option>
                            @endforeach
                        </select>
                        <x-forms.error name="village_id" />
                    </fieldset>

                    <div class="card-actions justify-end mt-8 border-t pt-4">
                        <a href="{{ route('admin-bps.users.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-secondary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout-admin-bps>