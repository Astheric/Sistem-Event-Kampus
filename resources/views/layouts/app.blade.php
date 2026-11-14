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
<body class="bg-bg text-ink min-h-screen">
    <header class="relative z-20 flex items-start justify-between px-6 pt-6 text-[15px] sm:px-8">
        <a href="{{ route('home') }}" class="font-medium">EventHub</a>
        <nav class="flex flex-col items-end gap-1 sm:flex-row sm:items-center sm:gap-6">
            <a href="{{ route('events.index') }}" class="hover:underline">Event</a>
            @auth
                <a href="{{ route('participant.tickets.index') }}" class="hover:underline">Tiket</a>
            @else
                <a href="{{ route('login') }}" class="hover:underline">Masuk</a>
                <a href="{{ route('register') }}" class="hover:underline">Daftar</a>
            @endauth
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-field-4 text-field-4-ink px-6 py-10 sm:px-8">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <p class="max-w-md text-lg">Platform event kampus untuk daftar, tiket QR, dan sertifikat.</p>
            <p class="text-sm text-field-4-ink/70">EventHub</p>
        </div>
    </footer>
</body>
</html>
