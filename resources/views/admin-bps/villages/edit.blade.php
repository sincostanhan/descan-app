<x-layout-admin-bps title="Panel Admin BPS | Edit Kelurahan">
    <x-hero
        title="Edit Kelurahan"
    />

    <div class="max-w-2xl mx-auto px-4 lg:px-0">
        <div class="card bg-base-100 card-border shadow-lg">
            <div class="card-body">
                <div class="alert alert-warning shadow-sm mb-4">
                    <x-lucide-alert-triangle class="w-5 h-5" />
                    <span class="text-sm">Mengubah Subdomain akan mengubah URL akses website & panel Admin Kelurahan ini. Pastikan Admin Kelurahan diberi tahu.</span>
                </div>

                <form action="{{ route('admin-bps.villages.update', $village) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Nama Kelurahan</legend>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $village->name) }}"
                            class="input w-full"
                            required />
                        <x-forms.error name="name" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Subdomain</legend>
                        <input
                            type="text"
                            name="subdomain"
                            value="{{ old('subdomain', $village->subdomain) }}"
                            class="input w-full"
                            required />
                        <x-forms.error name="subdomain" />
                    </fieldset>

                    <div class="card-actions justify-end mt-8 border-t pt-4">
                        <a href="{{ route('admin-bps.villages.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-secondary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout-admin-bps>