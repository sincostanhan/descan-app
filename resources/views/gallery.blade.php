<x-layout title="Galeri Kelurahan">
    <h1>Galeri Kegiatan Kelurahan Baadia</h1>
    <hr>

    @if($galleries->isEmpty())
        <p>Belum ada kegiatan di galeri.</p>
    @else
        @foreach($galleries as $gallery)
            <div style="margin-bottom: 40px; border: 1px solid #ccc; padding: 15px;">
                <h2>{{ $gallery->nama_kegiatan }}</h2>
                <p>Tanggal: {{ $gallery->created_at->format('d M Y') }}</p>
                
                <div>
                    @foreach($gallery->photos as $photo)
                        {{-- <img src="{{ asset('storage/' . $photo->foto_path) }}" alt="Foto {{ $gallery->nama_kegiatan }}" width="200"> --}}
                        <img src="{{ asset('storage/app/public/' . $photo->foto_path) }}" alt="Foto {{ $gallery->nama_kegiatan }}" width="200">
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</x-layout>