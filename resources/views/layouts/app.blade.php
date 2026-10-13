<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EventHub') — Sistem Pengelolaan Event Kampus</title>

    <!-- Google Fonts: Space Grotesk & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;800&family=Space+Grotesk:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#FFFDF8] text-black font-['Space_Grotesk'] antialiased min-h-screen flex flex-col selection:bg-[#FFE600] selection:text-black">

    <!-- Top Running Marquee / Ticker -->
    <div class="bg-[#FFE600] border-b-2 border-black py-1 overflow-hidden select-none">
        <div class="inline-block whitespace-nowrap animate-[marquee_25s_linear_infinite] text-xs font-bold uppercase tracking-wider">
            <span>⚡ EVENTHUB KAMPUS • CREATE. MANAGE. JOIN. • QR CHECK-IN REAL-TIME • E-SERTIFIKAT TERVERIFIKASI OTOMATIS • NEO-BRUTALISM EDITION • PLATFORM RESMI EVENT MAHASISWA ⚡</span>
            <span class="ml-12">⚡ EVENTHUB KAMPUS • CREATE. MANAGE. JOIN. • QR CHECK-IN REAL-TIME • E-SERTIFIKAT TERVERIFIKASI OTOMATIS • NEO-BRUTALISM EDITION • PLATFORM RESMI EVENT MAHASISWA ⚡</span>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="bg-white border-b-4 border-black sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-12 h-12 bg-[#FFE600] border-3 border-black flex items-center justify-center font-black text-2xl shadow-[3px_3px_0px_0px_#000] group-hover:rotate-6 transition-transform">
                    EH
                </div>
                <div class="flex flex-col">
                    <span class="font-black text-2xl tracking-tight leading-none group-hover:text-[#FF6B4A] transition-colors">EventHub</span>
                    <span class="font-bold text-[10px] uppercase tracking-widest text-zinc-600">Sistem Event Kampus</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="font-bold text-base hover:bg-[#FFE600] px-3 py-1 border-2 border-transparent hover:border-black transition-all {{ request()->routeIs('home') ? 'bg-[#FFE600] border-black shadow-[2px_2px_0px_0px_#000]' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('events.index') }}" class="font-bold text-base hover:bg-[#00F0FF] px-3 py-1 border-2 border-transparent hover:border-black transition-all {{ request()->routeIs('events.*') ? 'bg-[#00F0FF] border-black shadow-[2px_2px_0px_0px_#000]' : '' }}">
                    Jelajah Event
                </a>
                <a href="{{ route('certificates.verify') }}" class="font-bold text-base hover:bg-[#57EB74] px-3 py-1 border-2 border-transparent hover:border-black transition-all {{ request()->routeIs('certificates.verify*') ? 'bg-[#57EB74] border-black shadow-[2px_2px_0px_0px_#000]' : '' }}">
                    Verifikasi Sertifikat
                </a>
            </nav>

            <!-- User Actions & Authentication -->
            <div class="flex items-center gap-3">
                @auth
                    <!-- In-App Notifications Center -->
                    <div class="relative" x-data="{ open: false }">
                        @php
                            $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
                            $recentNotifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->take(5)->get();
                        @endphp
                        <button @click="open = !open" class="relative p-2.5 bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] active:shadow-none transition-all">
                            <span class="text-xl">🔔</span>
                            @if($unreadCount > 0)
                                <span class="absolute -top-2 -right-2 bg-[#FF6B4A] text-white text-xs font-black w-5 h-5 border-2 border-black flex items-center justify-center">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-3 w-80 sm:w-96 bg-white border-3 border-black shadow-[6px_6px_0px_0px_#000] p-4 z-50">
                            <div class="flex items-center justify-between pb-2 border-b-2 border-black mb-3">
                                <span class="font-black text-sm uppercase">Pusat Notifikasi</span>
                                @if($unreadCount > 0)
                                    <form action="{{ route('notifications.read_all') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-[#FF6B4A] hover:underline">Tandai Semua Dibaca</button>
                                    </form>
                                @endif
                            </div>
                            <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                                @forelse($recentNotifications as $notif)
                                    <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left p-2.5 border-2 border-black {{ $notif->is_read ? 'bg-zinc-50' : 'bg-[#FFF9C4]' }} hover:bg-[#FFE600] transition-colors block">
                                            <p class="font-black text-xs">{{ $notif->title }}</p>
                                            <p class="text-xs text-zinc-700 mt-1 line-clamp-2">{{ $notif->message }}</p>
                                            <span class="text-[10px] font-mono text-zinc-500 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                        </button>
                                    </form>
                                @empty
                                    <p class="text-xs text-zinc-500 py-3 text-center">Belum ada notifikasi baru.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Role Badges & Menu Dropdown -->
                    <div class="relative" x-data="{ userMenu: false }">
                        <button @click="userMenu = !userMenu" class="flex items-center gap-2 bg-[#4DEEEA] border-2 border-black px-3 py-2 shadow-[3px_3px_0px_0px_#000] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all">
                            <div class="w-7 h-7 bg-white border-2 border-black flex items-center justify-center font-black text-xs uppercase">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <span class="font-bold text-sm hidden sm:inline">{{ Auth::user()->name }}</span>
                            <span class="bg-black text-white text-[10px] font-black px-1.5 py-0.5 uppercase tracking-wider">
                                {{ Auth::user()->role }}
                            </span>
                            <span class="text-xs">▼</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userMenu" @click.outside="userMenu = false" x-cloak class="absolute right-0 mt-3 w-56 bg-white border-3 border-black shadow-[6px_6px_0px_0px_#000] py-2 z-50">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 font-bold hover:bg-[#FFE600] border-b border-black text-sm">
                                    ⚙️ Dashboard Admin
                                </a>
                            @endif

                            @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
                                <a href="{{ route('organizer.dashboard') }}" class="block px-4 py-2 font-bold hover:bg-[#4DEEEA] border-b border-black text-sm">
                                    🏢 Dashboard Organizer
                                </a>
                            @endif

                            <a href="{{ route('participant.tickets.index') }}" class="block px-4 py-2 font-bold hover:bg-[#57EB74] border-b border-black text-sm">
                                🎟️ Tiket Saya
                            </a>
                            <a href="{{ route('participant.certificates.index') }}" class="block px-4 py-2 font-bold hover:bg-[#FF70A6] border-b border-black text-sm">
                                🎓 E-Sertifikat Saya
                            </a>
                            <a href="{{ route('participant.profile') }}" class="block px-4 py-2 font-bold hover:bg-[#FFE600] border-b border-black text-sm">
                                👤 Pengaturan Profil
                            </a>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 font-bold hover:bg-[#FF6B4A] hover:text-white text-sm transition-colors">
                                    🚪 Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="font-bold text-sm px-4 py-2 bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5 active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="font-bold text-sm px-4 py-2 bg-[#FFE600] border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5 active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="bg-[#57EB74] border-3 border-black p-4 mb-4 shadow-[4px_4px_0px_0px_#000] flex items-center justify-between font-bold">
                <div class="flex items-center gap-2">
                    <span class="text-xl">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="border-2 border-black bg-white px-2 py-0.5 text-xs font-black">X</button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-[#FF70A6] border-3 border-black p-4 mb-4 shadow-[4px_4px_0px_0px_#000] flex items-center justify-between font-bold">
                <div class="flex items-center gap-2">
                    <span class="text-xl">❌</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="border-2 border-black bg-white px-2 py-0.5 text-xs font-black">X</button>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-[#FFE600] border-3 border-black p-4 mb-4 shadow-[4px_4px_0px_0px_#000] flex items-center justify-between font-bold">
                <div class="flex items-center gap-2">
                    <span class="text-xl">⚠️</span>
                    <span>{{ session('warning') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="border-2 border-black bg-white px-2 py-0.5 text-xs font-black">X</button>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-[#00F0FF] border-3 border-black p-4 mb-4 shadow-[4px_4px_0px_0px_#000] flex items-center justify-between font-bold">
                <div class="flex items-center gap-2">
                    <span class="text-xl">ℹ️</span>
                    <span>{{ session('info') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="border-2 border-black bg-white px-2 py-0.5 text-xs font-black">X</button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t-4 border-black mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#FFE600] border-2 border-black flex items-center justify-center font-black text-xl shadow-[2px_2px_0px_0px_#000]">
                            EH
                        </div>
                        <span class="font-black text-2xl">EventHub Kampus</span>
                    </div>
                    <p class="text-zinc-700 max-w-md text-sm leading-relaxed">
                        Sistem pengelolaan event kampus terintegrasi: dari pembuatan event, tiket berjenjang, pendaftaran, pembayaran simulasi, QR code check-in anti-double scan, hingga e-sertifikat terverifikasi otomatis.
                    </p>
                    <div class="inline-block bg-[#FFE600] border-2 border-black px-3 py-1 font-bold text-xs shadow-[2px_2px_0px_0px_#000]">
                        ⚡ Neo-Brutalism Design System v1.1
                    </div>
                </div>

                <div>
                    <h3 class="font-black text-base uppercase mb-4 tracking-wider">Akses Cepat</h3>
                    <ul class="space-y-2 text-sm font-bold">
                        <li><a href="{{ route('events.index') }}" class="hover:underline">Katalog Event</a></li>
                        <li><a href="{{ route('certificates.verify') }}" class="hover:underline">Verifikasi Sertifikat</a></li>
                        <li><a href="{{ route('login') }}" class="hover:underline">Masuk Akun</a></li>
                        <li><a href="{{ route('register') }}" class="hover:underline">Daftar Organizer</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-black text-base uppercase mb-4 tracking-wider">Role & Fitur</h3>
                    <ul class="space-y-2 text-sm font-bold text-zinc-700">
                        <li>🎓 Peserta: Tiket QR & Sertifikat</li>
                        <li>🏢 Organizer: Tiket & QR Scanner</li>
                        <li>🛡️ Admin: Moderasi & Audit Log</li>
                        <li>📷 Scanner: Kamera Browser Real-time</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t-2 border-black flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-bold">
                <p>© 2026 EventHub — Sistem Pengelolaan Event Kampus Berbasis Web. All rights reserved.</p>
                <div class="flex gap-4">
                    <span class="bg-black text-white px-2 py-0.5 font-mono">PHP 8.4 • LARAVEL 13 • TAILWIND CSS</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
