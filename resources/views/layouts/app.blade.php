<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EventHub: Sistem Event Kampus')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-canvas text-ink antialiased text-base">
    @hasSection('plain')
        @yield('content')
    @else
    <header class="sticky top-0 z-30 bg-surface/95 backdrop-blur-md border-b border-line">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4 sm:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3 font-semibold text-ink group">
                <img src="{{ asset('images/logo.webp') }}" alt="EventHub" class="h-8 w-auto object-contain">
            </a>

            <nav class="hidden items-center gap-2 md:flex">
                <a href="{{ route('home') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium transition {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-muted hover:bg-surface-soft hover:text-ink' }}">Beranda</a>
                <a href="{{ route('events.index') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium transition {{ request()->routeIs('events.*') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-muted hover:bg-surface-soft hover:text-ink' }}">Katalog Event</a>
                <a href="{{ route('certificates.verify') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium transition {{ request()->routeIs('certificates.verify*') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-muted hover:bg-surface-soft hover:text-ink' }}">Verifikasi Sertifikat</a>
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium text-muted hover:bg-surface-soft hover:text-ink">Dashboard</a>
                    @elseif(Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium text-muted hover:bg-surface-soft hover:text-ink">Dashboard</a>
                    @else
                        <a href="{{ route('participant.tickets.index') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium text-muted hover:bg-surface-soft hover:text-ink">Tiket Saya</a>
                    @endif
                    @if(Auth::user()->eventMembers()->exists())
                        <a href="{{ route('organizer.committee.tasks') }}" class="rounded-lg px-3.5 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-50">Tugas Panitia</a>
                    @endif
                @endauth
            </nav>

            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary text-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-sm">Daftar</a>
                @else
                    <span class="hidden text-sm font-medium text-muted sm:block">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn btn-secondary text-sm">Keluar</button></form>
                @endguest
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="mx-auto mt-4 max-w-7xl px-6 sm:px-8"><div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div></div>
    @endif
    @if (session('error'))
        <div class="mx-auto mt-4 max-w-7xl px-6 sm:px-8"><div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">{{ session('error') }}</div></div>
    @endif
    @if ($errors->any())
        <div class="mx-auto mt-4 max-w-7xl px-6 sm:px-8"><div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
    @endif

    <main>@yield('content')</main>

    <footer class="mt-20 border-t border-line bg-surface py-12">
        <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-6 sm:flex-row sm:items-center sm:px-8">
            <div class="space-y-2">
                <img src="{{ asset('images/logo.webp') }}" alt="EventHub" class="h-8 w-auto object-contain">
                <p class="text-sm text-muted">
                    Sistem pendaftaran, tiket, dan verifikasi sertifikat kegiatan kampus.
                </p>
            </div>
            <nav class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-muted">
                <a href="{{ route('home') }}" class="hover:text-ink">Beranda</a>
                <a href="{{ route('events.index') }}" class="hover:text-ink">Katalog event</a>
                <a href="{{ route('certificates.verify') }}" class="hover:text-ink">Verifikasi sertifikat</a>
            </nav>
        </div>
    </footer>
    @endif
</body>
</html>
