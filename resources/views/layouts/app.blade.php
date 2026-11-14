<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EventHub — Sistem Manajemen Event Kampus')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-canvas text-ink antialiased">
    @hasSection('plain')
        @if (session('success'))
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8"><div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div></div>
        @endif
        @if (session('error'))
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8"><div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div></div>
        @endif
        @yield('content')
    @else
    <header class="border-b border-line bg-surface">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3 font-bold text-ink">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-brand-700 text-white"><x-icon name="calendar" class="size-5" /></span>
                <div class="leading-tight">
                    <p class="text-base font-bold">EventHub</p>
                    <p class="text-xs font-medium text-muted">Sistem Event Kampus</p>
                </div>
            </a>

            <nav class="hidden items-center gap-1 md:flex">
                <a href="{{ route('events.index') }}" class="rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('events.*') ? 'bg-brand-50 text-brand-700' : 'text-muted hover:bg-surface-soft hover:text-ink' }}">Jelajah Event</a>
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-muted hover:bg-surface-soft hover:text-ink">Dashboard</a>
                    @elseif(Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-muted hover:bg-surface-soft hover:text-ink">Dashboard</a>
                    @else
                        <a href="{{ route('participant.tickets.index') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-muted hover:bg-surface-soft hover:text-ink">Tiket Saya</a>
                    @endif
                @endauth
            </nav>

            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @else
                    <span class="hidden text-sm text-muted sm:block">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn btn-secondary">Keluar</button></form>
                @endguest
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8"><div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div></div>
    @endif
    @if (session('error'))
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8"><div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div></div>
    @endif
    @if ($errors->any())
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8"><div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
    @endif

    <main>@yield('content')</main>

    <footer class="mt-16 border-t border-line bg-surface">
        <div class="mx-auto max-w-7xl px-4 py-8 text-sm text-muted sm:px-6 lg:px-8">
            <p class="font-semibold text-ink">EventHub — Sistem Manajemen Event Kampus</p>
            <p class="mt-1">Publikasi event, pendaftaran, tiket, presensi, dan sertifikat terintegrasi.</p>
        </div>
    </footer>
    @endif
</body>
</html>
