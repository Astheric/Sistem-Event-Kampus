@extends('layouts.app')

@section('title', 'EventHub — Manajemen Event Kampus')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    <div class="grid items-center gap-10 lg:grid-cols-2">
        <div>
            <p class="text-sm font-semibold text-brand-700">Portal Event Kampus</p>
            <h1 class="mt-3 max-w-xl text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">Kelola dan ikuti event kampus dengan lebih mudah</h1>
            <p class="mt-5 max-w-lg text-lg leading-relaxed text-muted">Temukan agenda kegiatan, daftar sebagai peserta, kelola tiket dan kehadiran, hingga terbitkan sertifikat dalam satu platform.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('events.index') }}" class="btn btn-primary">Jelajahi event</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-secondary">Buat akun</a>
                @endguest
            </div>
        </div>
        <div class="card p-6 sm:p-8">
            <h2 class="text-lg font-bold text-ink">Cari event</h2>
            <p class="mt-1 text-sm text-muted">Filter berdasarkan kata kunci dan kategori.</p>
            <form action="{{ route('events.index') }}" method="GET" class="mt-6 space-y-4">
                <div>
                    <label for="q" class="label">Kata kunci</label>
                    <input id="q" type="text" name="q" placeholder="Contoh: seminar, workshop, kompetisi" class="input">
                </div>
                <div>
                    <label for="category" class="label">Kategori</label>
                    <select id="category" name="category" class="input">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-full">Cari</button>
            </form>
        </div>
    </div>

    <dl class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="card-soft p-5">
            <dt class="text-sm text-muted">Total event</dt>
            <dd class="mt-1 text-2xl font-bold text-ink">{{ $stats['total_events'] ?? 0 }}</dd>
        </div>
        <div class="card-soft p-5">
            <dt class="text-sm text-muted">Peserta terdaftar</dt>
            <dd class="mt-1 text-2xl font-bold text-ink">{{ $stats['total_participants'] ?? 0 }}</dd>
        </div>
        <div class="card-soft p-5">
            <dt class="text-sm text-muted">Sertifikat terbit</dt>
            <dd class="mt-1 text-2xl font-bold text-ink">{{ $stats['certificates_issued'] ?? 0 }}</dd>
        </div>
        <div class="card-soft p-5">
            <dt class="text-sm text-muted">Organizer aktif</dt>
            <dd class="mt-1 text-2xl font-bold text-ink">{{ $stats['active_organizers'] ?? 0 }}</dd>
        </div>
    </dl>
</section>

@if($featuredEvents->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-ink">Event mendatang</h2>
            <p class="mt-1 text-sm text-muted">Agenda kegiatan kampus yang akan segera berlangsung.</p>
        </div>
        <a href="{{ route('events.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-600">Lihat semua</a>
    </div>

    <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($featuredEvents as $event)
            <a href="{{ route('events.show', $event->slug) }}" class="card block p-5 transition hover:shadow-md">
                <div class="flex items-center justify-between gap-3">
                    <span class="badge border border-brand-100 bg-brand-50 text-brand-700">{{ $event->category->name }}</span>
                    <span class="text-xs font-medium text-muted">{{ $event->start_time->format('d M Y') }}</span>
                </div>
                <h3 class="mt-4 text-lg font-bold leading-snug text-ink">{{ $event->title }}</h3>
                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-muted">{{ $event->description }}</p>
                <dl class="mt-4 space-y-2 text-sm text-muted">
                    <div class="flex items-center gap-2"><x-icon name="clock" class="size-4" /><dd>{{ $event->start_time->format('H:i') }} WIB</dd></div>
                    <div class="flex items-center gap-2"><x-icon name="map-pin" class="size-4" /><dd class="truncate">{{ $event->venue ? $event->venue->name : 'Online' }}</dd></div>
                </dl>
                <div class="mt-5 flex items-center justify-between border-t border-line pt-4">
                    <span class="text-sm font-semibold text-ink">@if($event->isFree()) Gratis @else Rp {{ number_format($event->min_price, 0, ',', '.') }} @endif</span>
                    <span class="text-sm font-semibold text-brand-700">Detail</span>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

@if($categories->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="card-soft p-6 sm:p-8">
        <h2 class="text-lg font-bold text-ink">Kategori</h2>
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($categories as $cat)
                <a href="{{ route('events.index', ['category' => $cat->slug]) }}" class="rounded-full border border-line bg-surface px-4 py-2 text-sm font-medium text-muted hover:border-brand-200 hover:bg-brand-50 hover:text-brand-700">{{ $cat->name }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
