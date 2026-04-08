@if(session('success'))
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
        <strong>Sukses!</strong> {{ session('success') }}
    </div>
@endif