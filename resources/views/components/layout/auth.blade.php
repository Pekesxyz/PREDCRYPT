<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Autentikasi' }} | PREDCRYPT</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-grid bg-radial-glow px-4">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8 animate-fade-in-up">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                <img src="{{ asset('images/logo.svg') }}" alt="PREDCRYPT" class="w-10 h-10">
                <span class="text-2xl font-bold gradient-text">PREDCRYPT</span>
            </a>
        </div>

        {{-- Auth Card --}}
        <div class="glass-card-static p-8 animate-fade-in-up delay-100">
            {{ $slot }}
        </div>
    </div>
</body>
</html>

