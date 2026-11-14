<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EventHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas text-ink min-h-screen">
    <div class="page-blob" style="top:-120px;left:-80px;width:360px;height:360px;background:#D9C8FF;opacity:.5;"></div>
    <div class="page-blob" style="top:40%;left:50%;width:420px;height:420px;background:#B9F5D8;opacity:.4;"></div>

    <header class="relative z-10 flex items-center justify-between px-6 py-4 bg-surface/70 backdrop-blur border-b border-line">
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg">
            <x-icon name="calendar" class="size-6 text-brand-600" />
            EventHub
        </a>
        <nav class="flex items-center gap-4 text-sm font-medium">
            <a href="{{ route('events.index') }}" class="text-muted hover:text-ink">Jelajahi event</a>
            @auth
                <a href="{{ route('participant.tickets.index') }}" class="text-muted hover:text-ink">Tiket saya</a>
            @else
                <a href="{{ route('login') }}" class="text-muted hover:text-ink">Masuk</a>
            @endauth
        </nav>
    </header>

    <main class="relative z-10 p-6">
        @yield('content')
    </main>
</body>
</html>
