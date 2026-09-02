{{-- resources\views\components\layout-admin-bps.blade.php --}}

@props([
    'title' => 'Panel Admin BPS'
])

{{-- @php
    $activeTheme = \App\Models\Setting::first()->theme_name ?? 'emerald';
@endphp --}}

<!DOCTYPE html>
{{-- <html lang="en" data-theme="{{ $activeTheme }}"> --}}
<html lang="en" data-theme="emerald">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
    </style>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
</head>
{{-- <body> --}}
<body class="min-h-screen flex flex-col">
    <x-nav-admin-bps />
    
    {{-- <hr> --}}

    {{-- <main class="mx-2"> --}}
    {{-- <main class="mx-2 lg:mx-8 fflex-grow"> --}}
    <main class="mx-2 lg:mx-8 grow mb-8">
        {{ $slot }}
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>