<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EventHub — Manajemen Event Kampus')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-canvas text-ink antialiased">
    <header class="sticky top-0 z-40 border-b border-line bg-surface/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold text-ink">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-brand-600 text-white">
                    <x-icon name="calendar" class="size-5" />
                </span>
                <span class="text-lg tracking-tight">EventHub</span>
            </a>

            <nav class="hidden items-center gap-1 md:flex">
                <a href="{{ route('events.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('events.*') ? 'bg-brand-50 text-brand-700' : 'text-muted hover:bg-surface-soft hover:text-ink' }}">Jelajah Event</a>
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted hover:bg-surface-soft hover:text-ink">Dashboard</a>
                    @elseif(Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted hover:bg-surface-soft hover:text-ink">Dashboard</a>
                    @else
                        <a href="{{ route('participant.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted hover:bg-surface-soft hover:text-ink">Tiket Saya</a>
                    @endif
                @endauth
            </nav>

            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="hidden rounded-lg px-3 py-2 text-sm font-semibold text-muted hover:bg-surface-soft hover:text-ink sm:inline-flex">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @else
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-semibold leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-muted">{{ Auth::user()->roleLabel() }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Keluar</button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
        </div>
    @endif
    @if (session('error'))
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">{{ session('error') }}</div>
        </div>
    @endif
    @if ($errors->any())
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main>@yield('content')</main>

    <footer class="mt-16 border-t border-line bg-surface">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <div class="flex items-center gap-2.5 font-bold">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-brand-600 text-white"><x-icon name="calendar" class="size-5" /></span>
                    <span class="text-lg">EventHub</span>
                </div>
                <p class="mt-3 max-w-sm text-sm leading-relaxed text-muted">Platform manajemen event kampus untuk publikasi, pendaftaran, tiket, presensi, dan sertifikat.</p>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-ink">Navigasi</h2>
                <ul class="mt-3 space-y-2 text-sm text-muted">
                    <li><a href="{{ route('home') }}" class="hover:text-ink">Beranda</a></li>
                    <li><a href="{{ route('events.index') }}" class="hover:text-ink">Jelajah Event</a></li>
                </ul>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-ink">Akun</h2>
                <ul class="mt-3 space-y-2 text-sm text-muted">
                    <li><a href="{{ route('login') }}" class="hover:text-ink">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-ink">Daftar</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
