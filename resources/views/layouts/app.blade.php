<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EventHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas text-ink min-h-screen antialiased">
    <div class="page-blob" style="top:-120px;left:-80px;width:360px;height:360px;background:#D9C8FF;opacity:.45;"></div>
    <div class="page-blob" style="top:35%;left:55%;width:420px;height:420px;background:#B9F5D8;opacity:.35;"></div>

    <header class="sticky top-0 z-20 border-b border-line/80 bg-surface/75 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-lg tracking-tight">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-100 text-brand-600">
                    <x-icon name="calendar" class="size-5" />
                </span>
                EventHub
            </a>
            <nav class="flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('events.index') }}" class="text-muted transition hover:text-ink">Jelajahi event</a>
                @auth
                    <a href="{{ route('participant.tickets.index') }}" class="text-muted transition hover:text-ink">Tiket saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-muted transition hover:text-ink">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-muted transition hover:text-ink">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-brand-600 px-4 py-2 text-white transition hover:bg-brand-700">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="relative z-10">
        @yield('content')
    </main>
</body>
</html>
