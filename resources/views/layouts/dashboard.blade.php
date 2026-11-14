<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EventHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=TikTok+Sans:wdth,wght@75..150,300..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg text-ink antialiased">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 flex-col border-r border-line bg-field-4 text-field-4-ink lg:flex">
            <div class="flex items-center gap-2 px-5 py-6">
                <x-icon name="calendar" class="size-6 text-accent" />
                <span class="text-lg font-extrabold tracking-tight">EventHub</span>
            </div>
            <nav class="flex-1 space-y-1 px-3">
                @foreach ($navigation ?? [] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-3 text-sm font-semibold {{ request()->routeIs($item['active']) ? 'bg-accent text-accent-ink' : 'text-field-4-ink hover:bg-white/10' }}">
                        <x-icon name="{{ $item['icon'] }}" class="size-5" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-white/20 p-3">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 px-3 py-3 text-sm font-semibold text-field-4-ink hover:bg-white/10">
                    <x-icon name="log-out" class="size-5" />
                    Keluar
                </button>
            </form>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex items-center justify-between border-b border-line bg-bg px-6 py-5">
                <h1 class="text-xl font-extrabold">@yield('title', 'Dashboard')</h1>
                <div class="flex items-center gap-3 text-sm font-medium">
                    <x-icon name="bell" class="size-5 text-muted" />
                    <span>{{ Auth::user()->roleLabel() }}</span>
                </div>
            </header>

            <main class="flex-1 p-6">
                @if (session('success'))
                    <div class="mb-4 bg-field-2 px-4 py-3 text-sm font-medium text-field-2-ink">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 bg-accent px-4 py-3 text-sm font-medium text-accent-ink">{{ session('error') }}</div>
                @endif
                @yield('content')
                @yield('dashboard_content')
            </main>
        </div>
    </div>
</body>
</html>
