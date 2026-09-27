<form action="{{ url()->current() }}" method="GET">
    {{-- Pertahankan parameter search & per_page yang sedang aktif --}}
    @foreach(request()->except(['urut', 'page']) as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <select name="urut" class="select select-sm w-56" onchange="this.form.submit()">
        <option value="">Urutkan: Terbaru ditambahkan</option>
        <option value="terbaru" @selected(request('urut') === 'terbaru')>Terakhir diperbarui (terbaru)</option>
        <option value="terlama" @selected(request('urut') === 'terlama')>Terakhir diperbarui (terlama)</option>
        <option value="judul_asc" @selected(request('urut') === 'judul_asc')>Judul A–Z</option>
        <option value="judul_desc" @selected(request('urut') === 'judul_desc')>Judul Z–A</option>
    </select>
</form>