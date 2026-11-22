<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') : EventHub</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            aside, header, .no-print { display: none !important; }
            main { padding: 0 !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-canvas text-ink antialiased" style="font-family:'Plus Jakarta Sans',sans-serif"
      x-data="{ sidebarOpen: false, notifOpen: false }">

    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/40 lg:hidden"
         @click="sidebarOpen = false"
         style="display:none"></div>

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 flex w-64 h-screen flex-col bg-surface border-r border-line
                       transition-transform duration-200 ease-in-out
                       lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            {{-- Logo --}}
            <div class="flex items-center justify-between gap-2.5 px-5 py-4 border-b border-line">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.webp') }}" alt="EventHub" class="h-8 w-auto object-contain">
                </a>
                {{-- Close button (mobile only) --}}
                <button class="lg:hidden p-1 rounded-md text-muted hover:text-ink" @click="sidebarOpen = false">
                    <x-icon name="x" class="size-5" />
                </button>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                @foreach ($navigation ?? [] as $item)
                    <a href="{{ route($item['route']) }}"
                       @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors
                              {{ request()->routeIs($item['active'])
                                 ? 'bg-brand-50 text-brand-700'
                                 : 'text-slate-600 hover:bg-surface-soft hover:text-ink' }}">
                        <x-icon name="{{ $item['icon'] }}" class="size-4 flex-shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-line p-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                        <x-icon name="log-out" class="size-4 text-red-500" />
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main column --}}
        <div class="flex min-w-0 flex-1 flex-col lg:pl-64">
            {{-- Top bar --}}
            <header class="sticky top-0 z-30 flex items-center justify-between bg-surface/95 backdrop-blur-md border-b border-line px-4 py-3 lg:px-6 lg:py-4">
                <div class="flex items-center gap-3">
                    {{-- Hamburger (mobile only) --}}
                    <button class="lg:hidden p-1.5 rounded-lg text-muted hover:text-ink hover:bg-surface-soft transition"
                            @click="sidebarOpen = true">
                        <x-icon name="menu" class="size-5" />
                    </button>
                </div>

                <div class="flex items-center gap-2 lg:gap-3">
                    {{-- Notification bell --}}
                    @php
                        $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                        $recentNotifs = \App\Models\Notification::where('user_id', Auth::id())->latest()->limit(5)->get();
                    @endphp
                    <div class="relative" @click.outside="notifOpen = false">
                        <button @click="notifOpen = !notifOpen"
                                class="relative p-1.5 rounded-lg text-muted hover:text-ink hover:bg-surface-soft transition">
                            <x-icon name="bell" class="size-5" />
                            @if($unreadCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 flex size-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white leading-none">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Notification dropdown --}}
                        <div x-show="notifOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                             class="absolute right-0 top-full mt-2 w-80 origin-top-right rounded-xl border border-line bg-surface shadow-lg z-50"
                             style="display:none">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-line">
                                <h3 class="text-sm font-bold text-ink">Notifikasi</h3>
                                @if($unreadCount > 0)
                                    <form method="POST" action="{{ route('notifications.read_all') }}">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold text-brand-700 hover:text-brand-800">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <ul class="divide-y divide-line max-h-72 overflow-y-auto">
                                @forelse($recentNotifs as $notif)
                                    <li class="{{ $notif->is_read ? '' : 'bg-brand-50/40' }}">
                                        <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-3 hover:bg-surface-soft transition">
                                                <p class="text-sm font-semibold text-ink leading-snug">{{ $notif->title }}</p>
                                                <p class="mt-0.5 text-xs text-muted line-clamp-2">{{ $notif->message }}</p>
                                                <p class="mt-1 text-[10px] text-muted/70">{{ $notif->created_at->diffForHumans() }}</p>
                                            </button>
                                        </form>
                                    </li>
                                @empty
                                    <li class="px-4 py-6 text-center text-sm text-muted">Tidak ada notifikasi.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    @php $user = Auth::user(); @endphp
                    <div class="flex items-center gap-3 pl-2">
                        <div class="hidden sm:block text-right leading-tight">
                            <p class="text-sm font-semibold text-ink">{{ $user->name }}</p>
                            <p class="text-xs text-muted">{{ $user->roleLabel() }}</p>
                        </div>
                        <img
                            src="https://api.dicebear.com/9.x/big-smile/svg?seed={{ urlencode($user->name) }}&backgroundColor=c7d2fe"
                            alt="{{ $user->name }}"
                            class="size-9 rounded-full bg-brand-100 object-cover"
                            width="36"
                            height="36"
                        >
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-4 lg:p-8">
                @if (session('success'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                        <x-icon name="check-circle" class="size-5 flex-shrink-0 mt-0.5" />
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        <x-icon name="alert-circle" class="size-5 flex-shrink-0 mt-0.5" />
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('info'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl bg-blue-50 px-4 py-3 text-sm font-medium text-blue-800">
                        <x-icon name="bell" class="size-5 flex-shrink-0 mt-0.5" />
                        {{ session('info') }}
                    </div>
                @endif

                @yield('content')
                @yield('dashboard_content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
