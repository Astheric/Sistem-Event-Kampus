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
    <div class="page-blob" style="top:-120px;left:-80px;width:360px;height:360px;background:#d9c8ff;opacity:.45;"></div>
    <div class="page-blob" style="top:35%;right:-120px;width:420px;height:420px;background:#b9f5d8;opacity:.35;"></div>

    <div class="relative z-10 flex min-h-screen flex-col">
        <header class="sticky top-0 z-40 border-b border-line/80 bg-surface/80 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold text-ink">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-brand-600 text-white">
                        <x-icon name="calendar" class="size-5" />
                    </span>
                    <span class="text-lg tracking-tight">EventHub</span>
                </a>

                <nav class="hidden items-center gap-1 md:flex">
                    <a href="{{ route('events.index') }}" class="rounded-full px-4 py-2 text-sm font-medium {{ request()->routeIs('events.*') ? 'bg-brand-50 text-brand-700' : 'text-muted hover:bg-surface-soft hover:text-ink' }}">Jelajah Event</a>
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="rounded-full px-4 py-2 text-sm font-medium text-muted hover:bg-surface-soft hover:text-ink">Dashboard Admin</a>
                        @elseif(Auth::user()->isOrganizer())
                            <a href="{{ route('organizer.dashboard') }}" class="rounded-full px-4 py-2 text-sm font-medium text-muted hover:bg-surface-soft hover:text-ink">Dashboard Organizer</a>
                        @else
                            <a href="{{ route('participant.dashboard') }}" class="rounded-full px-4 py-2 text-sm font-medium text-muted hover:bg-surface-soft hover:text-ink">Tiket Saya</a>
                        @endif
                    @endauth
                </nav>

                <div class="flex items-center gap-2">
                    @guest
                        <a href="{{ route('login') }}" class="hidden rounded-full px-4 py-2 text-sm font-semibold text-ink hover:bg-surface-soft sm:inline-flex">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Daftar</a>
                    @else
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-semibold leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-muted">{{ Auth::user()->roleLabel() }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-full border border-line bg-surface px-4 py-2 text-sm font-semibold text-ink hover:bg-surface-soft">Keluar</button>
                        </form>
                    @endguest
                </div>
            </div>
        </header>

        @if (session('success'))
            <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-[14px] border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-[14px] border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">{{ session('error') }}</div>
            </div>
        @endif
        @if ($errors->any())
            <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-[14px] border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <main class="flex-1">@yield('content')</main>

        <footer class="mt-16 border-t border-line bg-surface">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-3 lg:px-8">
                <div>
                    <div class="flex items-center gap-2.5 font-bold">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-brand-600 text-white"><x-icon name="calendar" class="size-5" /></span>
                        <span class="text-lg">EventHub</span>
                    </div>
                    <p class="mt-3 max-w-sm text-sm leading-relaxed text-muted">Platform manajemen event kampus untuk publikasi, pendaftaran, tiket, presensi, dan sertifikat dalam satu tempat.</p>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-ink">Navigasi</h2>
                    <ul class="mt-3 space-y-2 text-sm text-muted">
                        <li><a href="{{ route('home') }}" class="hover:text-ink">Beranda</a></li>
                        <li><a href="{{ route('events.index') }}" class="hover:text-ink">Jelajah Event</a></li>
                    </ul>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-ink">Akses</h2>
                    <ul class="mt-3 space-y-2 text-sm text-muted">
                        <li><a href="{{ route('login') }}" class="hover:text-ink">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-ink">Daftar</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-line py-4 text-center text-xs text-muted">&copy; {{ date('Y') }} EventHub Kampus.</div>
        </footer>
    </div>
</body>
</html>
