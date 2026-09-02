{{-- resources\views\components\forms\error.blade.php --}}

<!-- s@props(['name']) -->
@props([
    'name' => 'required'
])

@error($name)
    {{-- <br>
    <span style="color: red; font-size: 12px;">{{ $message }}</span> --}}
    <p class="label text-error text-xs mt-1">{{ $message }}</p>
@enderror