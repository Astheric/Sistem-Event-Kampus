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

    <div class="relative z-10 flex min-h-screen">
        <aside class="hidden lg:flex w-60 flex-col bg-surface/90 backdrop-blur border-r border-line px-4 py-6">
            <div class="flex items-center gap-2 px-2 mb-6">
                <x-icon name="calendar" class="size-6 text-brand-600" />
                <span class="font-bold text-lg">EventHub</span>
            </div>
            <nav class="flex-1 space-y-1">
                @foreach ($navigation ?? [] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium {{ request()->routeIs($item['active']) ? 'bg-brand-100 text-brand-600' : 'text-muted hover:text-ink hover:bg-surface-soft' }}">
                        <x-icon name="{{ $item['icon'] }}" class="size-5" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-muted hover:text-ink hover:bg-surface-soft w-full">
                    <x-icon name="log-out" class="size-5" />
                    Keluar
                </button>
            </form>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="h-18 flex items-center justify-between px-6 bg-surface/70 backdrop-blur border-b border-line">
                <h1 class="text-xl font-bold">@yield('title', 'Dashboard')</h1>
                <div class="flex items-center gap-3">
                    <x-icon name="bell" class="size-5 text-muted" />
                    <span class="text-sm font-medium">{{ Auth::user()->roleLabel() }}</span>
                </div>
            </header>

            <main class="flex-1 p-6">
                @if (session('success'))
                    <div class="mb-4 rounded-xl bg-green-50 text-green-700 px-4 py-3 text-sm">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-xl bg-red-50 text-red-700 px-4 py-3 text-sm">{{ session('error') }}</div>
                @endif
                @yield('content')
                @yield('dashboard_content')
            </main>
        </div>
    </div>
</body>
</html>
