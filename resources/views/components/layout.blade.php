{{-- resources\views\components\layout.blade.php --}}

@props([
    'title' => ''
])

@php
    $activeTheme = \App\Models\Setting::first()->theme_name ?? 'emerald';

    if (isset($currentVillage)) {
        $nama = trim($currentVillage->name);
        
        if (str_contains(strtolower($nama), 'kelurahan')) {
            $displayTitle = ucwords($nama);
        } else {
            $displayTitle = 'Kelurahan ' . ucwords($nama);
        }
    } else {
        $displayTitle = 'Kelurahan Cantik';
    }
    
    // Format untuk Publik
    $pageTitle = $title ? "{$displayTitle} | {$title}" : $displayTitle;
@endphp

<!DOCTYPE html>
{{-- <html lang="en" data-theme="emerald"> --}}
<html lang="id" data-theme="{{ $activeTheme }}">
{{-- <html lang="en" data-theme="dark"> --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{ $title }}</title> --}}
    <title>{{ $pageTitle }}</title>
    <style>
    </style>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    {{-- <script src="https://unpkg.com/lucide@latest"></script> --}}
</head>
{{-- <body> --}}
<body class="min-h-screen flex flex-col">
    <x-nav />
    {{-- <nav>
    </nav> --}}
    
    {{-- <hr> --}}

    {{-- <main class="mx-2"> --}}
    {{-- <main class="mx-2 lg:mx-8 fflex-grow"> --}}
    <main class="mx-2 lg:mx-8 grow  mb-8">
        {{ $slot }}
    </main>

    {{-- <hr> --}}

    {{-- <footer class="footer sm:footer-horizontal footer-center bg-base-300 text-base-content p-4"> --}}
    {{-- <footer class="footer sm:footer-horizontal footer-center bg-base-100 text-base-content p-4
    drop-shadow
    mt-8">
        <aside>
            <p>Hak Cipta © 2025 Kelurahan Baadia<br>Semua Hak Dilindungi</p>
        </aside>
    </footer> --}}
    <x-footer />
</body>
</html>