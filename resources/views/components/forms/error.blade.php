<!-- @props(['name']) -->
@props([
    'name' => 'required'
])

@error($name)
    <br>
    <span style="color: red; font-size: 12px;">{{ $message }}</span>
@enderror