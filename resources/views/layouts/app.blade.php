<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EventHub — Manajemen Event Kampus')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chivo:wght@400;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-canvas text-ink antialiased">
    <header class="border-b border-line bg-canvas">
        <div class="mx-auto flex max-w-7xl items-end justify-between gap-6 px-4 py-5 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="block">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-muted">Kampus</p>
                <p class="text-2xl font-black uppercase leading-none tracking-tight sm:text-3xl">EventHub</p>
            </a>

            <nav class="hidden items-center gap-6 md:flex">
                <a href="{{ route('events.index') }}" class="text-sm font-bold uppercase tracking-wide {{ request()->routeIs('events.*') ? 'text-ink underline decoration-2 underline-offset-4' : 'text-muted hover:text-ink' }}">Agenda</a>
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold uppercase tracking-wide text-muted hover:text-ink">Admin</a>
                    @elseif(Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="text-sm font-bold uppercase tracking-wide text-muted hover:text-ink">Organizer</a>
                    @else
                        <a href="{{ route('participant.dashboard') }}" class="text-sm font-bold uppercase tracking-wide text-muted hover:text-ink">Tiket Saya</a>
                    @endif
                @endauth
            </nav>

            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-bold uppercase tracking-wide text-muted hover:text-ink">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary py-2.5">Daftar</a>
                @else
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-bold leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs uppercase tracking-wide text-muted">{{ Auth::user()->roleLabel() }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary py-2.5">Keluar</button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="border-b border-line bg-success-soft">
            <div class="mx-auto max-w-7xl px-4 py-3 text-sm font-medium text-success-ink sm:px-6 lg:px-8">{{ session('success') }}</div>
        </div>
    @endif
    @if (session('error'))
        <div class="border-b border-line bg-danger-soft">
            <div class="mx-auto max-w-7xl px-4 py-3 text-sm font-medium text-danger-ink sm:px-6 lg:px-8">{{ session('error') }}</div>
        </div>
    @endif
    @if ($errors->any())
        <div class="border-b border-line bg-danger-soft">
            <div class="mx-auto max-w-7xl px-4 py-3 text-sm text-danger-ink sm:px-6 lg:px-8">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main>@yield('content')</main>

    <footer class="mt-20 border-t border-line bg-ink text-field-4-ink">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1.2fr_.8fr_.8fr] lg:px-8">
            <div>
                <p class="text-4xl font-black uppercase leading-none tracking-tight sm:text-5xl">EventHub</p>
                <p class="mt-4 max-w-md text-base leading-relaxed text-field-4-ink/80">Sistem manajemen event kampus: agenda, pendaftaran, tiket, presensi, dan sertifikat.</p>
            </div>
            <div>
                <h2 class="text-xs font-bold uppercase tracking-[0.18em] text-field-4-ink/70">Navigasi</h2>
                <ul class="mt-4 space-y-2 text-sm font-bold uppercase tracking-wide">
                    <li><a href="{{ route('home') }}" class="hover:text-accent">Beranda</a></li>
                    <li><a href="{{ route('events.index') }}" class="hover:text-accent">Agenda</a></li>
                </ul>
            </div>
            <div>
                <h2 class="text-xs font-bold uppercase tracking-[0.18em] text-field-4-ink/70">Akun</h2>
                <ul class="mt-4 space-y-2 text-sm font-bold uppercase tracking-wide">
                    <li><a href="{{ route('login') }}" class="hover:text-accent">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-accent">Daftar</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
