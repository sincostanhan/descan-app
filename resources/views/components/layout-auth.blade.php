@props([
    'title' => 'Login'
])

{{-- @php
    $activeTheme = \App\Models\Setting::first()->theme_name ?? 'emerald';
@endphp --}}

<!DOCTYPE html>
{{-- <html lang="id" data-theme="{{ $activeTheme }}"> --}}
<html lang="id" data-theme="emerald">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />

    {{-- <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"> --}}
</head>
<body class="min-h-screen flex items-center justify-center
    {{-- bg-base-200 antialiased"> --}}
    bg-base-200">
    
    <main class="w-full max-w-md p-4">
        {{ $slot }}
    </main>

</body>
</html>