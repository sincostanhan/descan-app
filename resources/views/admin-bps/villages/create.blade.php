<x-layout-admin-bps title="Panel Admin BPS | Tambah Kelurahan">
    <x-hero
        title="Tambah Kelurahan"
    />

    <div class="max-w-2xl mx-auto px-4 lg:px-0">
        <div class="card bg-base-100 card-border shadow-lg">
            <div class="card-body">
                <div class="alert alert-info shadow-sm mb-4">
                    <x-lucide-info class="w-5 h-5" />
                    <span class="text-sm">Setelah Kelurahan dibuat, daftarkan Admin Kelurahan-nya lewat menu <strong>Admin Kelurahan</strong>.</span>
                </div>

                <form action="{{ route('admin-bps.villages.store') }}" method="POST">
                    @csrf

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Nama Kelurahan</legend>
                        <input
                            type="text"
                            id="villageName"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Wale"
                            class="input w-full"
                            required />
                        <x-forms.error name="name" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Subdomain</legend>
                        <input
                            type="text"
                            id="villageSubdomain"
                            name="subdomain"
                            value="{{ old('subdomain') }}"
                            placeholder="wale"
                            class="input w-full"
                            autocomplete="off" />
                        <p class="fieldset-label text-base-content/70">
                            {{-- Kosongkan untuk generate otomatis dari Nama Kelurahan. Ini menentukan URL: <code>&#123;subdomain&#125;.descan.scthan.tech</code> --}}
                            Kosongkan untuk generate otomatis dari Nama Kelurahan. Ini menentukan URL: <code>descan.scthan.tech/&#123;subdomain&#125;</code>
                        </p>
                        <x-forms.error name="subdomain" />
                    </fieldset>

                    <div class="card-actions justify-end mt-8 border-t pt-4">
                        <a href="{{ route('admin-bps.villages.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-secondary">Simpan Kelurahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-slug subdomain dari Nama Kelurahan, HANYA selama field subdomain belum disentuh manual.
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('villageName');
            const subdomainInput = document.getElementById('villageSubdomain');
            let subdomainTouched = false;

            subdomainInput.addEventListener('input', () => { subdomainTouched = true; });

            nameInput.addEventListener('input', function () {
                if (subdomainTouched) return;
                subdomainInput.value = this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-');
            });
        });
    </script>
</x-layout-admin-bps>