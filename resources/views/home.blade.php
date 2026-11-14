@extends('layouts.app')

@section('title', 'EventHub — Manajemen Event Kampus')

@section('content')
<section class="mx-auto max-w-7xl px-4 pt-12 pb-10 sm:px-6 lg:px-8 lg:pt-16">
    <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_.9fr]">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">
                <x-icon name="compass" class="size-4" /> Portal Event Kampus
            </span>
            <h1 class="mt-5 max-w-2xl text-4xl font-extrabold leading-tight tracking-tight text-ink sm:text-5xl">
                Temukan, kelola, dan ikuti event kampus dalam satu platform
            </h1>
            <p class="mt-5 max-w-xl text-base leading-relaxed text-muted sm:text-lg">
                Dari publikasi event, pendaftaran, tiket, presensi, hingga sertifikat — EventHub membantu mahasiswa, dosen, dan panitia menjalankan kegiatan kampus dengan rapi.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('events.index') }}" class="btn btn-primary">Jelajahi event</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-secondary">Buat akun</a>
                @else
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Buka dashboard</a>
                    @elseif(Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="btn btn-secondary">Buka dashboard</a>
                    @else
                        <a href="{{ route('participant.dashboard') }}" class="btn btn-secondary">Tiket saya</a>
                    @endif
                @endguest
            </div>

            <dl class="mt-10 grid grid-cols-2 gap-4 max-w-xl sm:grid-cols-4">
                <div class="rounded-[16px] border border-line bg-surface p-4">
                    <dt class="text-xs font-medium text-muted">Total event</dt>
                    <dd class="mt-1 text-2xl font-bold text-ink">{{ $stats['total_events'] ?? 0 }}</dd>
                </div>
                <div class="rounded-[16px] border border-line bg-surface p-4">
                    <dt class="text-xs font-medium text-muted">Peserta</dt>
                    <dd class="mt-1 text-2xl font-bold text-ink">{{ $stats['total_participants'] ?? 0 }}</dd>
                </div>
                <div class="rounded-[16px] border border-line bg-surface p-4">
                    <dt class="text-xs font-medium text-muted">Sertifikat</dt>
                    <dd class="mt-1 text-2xl font-bold text-ink">{{ $stats['certificates_issued'] ?? 0 }}</dd>
                </div>
                <div class="rounded-[16px] border border-line bg-surface p-4">
                    <dt class="text-xs font-medium text-muted">Organizer</dt>
                    <dd class="mt-1 text-2xl font-bold text-ink">{{ $stats['active_organizers'] ?? 0 }}</dd>
                </div>
            </dl>
        </div>

        <div class="card p-6 sm:p-8">
            <h2 class="text-lg font-bold text-ink">Mulai cepat</h2>
            <p class="mt-1 text-sm text-muted">Cari event berdasarkan minat dan jadwalmu.</p>
            <form action="{{ route('events.index') }}" method="GET" class="mt-6 space-y-4">
                <div>
                    <label for="q" class="label">Kata kunci</label>
                    <input id="q" type="text" name="q" placeholder="Contoh: seminar AI, workshop desain" class="input">
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
                <button type="submit" class="btn btn-primary w-full">Cari event</button>
            </form>
            <ul class="mt-6 space-y-3 text-sm text-muted">
                <li class="flex items-start gap-2"><x-icon name="check-circle" class="mt-0.5 size-4 text-brand-600" /> Informasi jadwal, lokasi, dan kapasitas jelas.</li>
                <li class="flex items-start gap-2"><x-icon name="check-circle" class="mt-0.5 size-4 text-brand-600" /> Pendaftaran dan tiket digital terintegrasi.</li>
                <li class="flex items-start gap-2"><x-icon name="check-circle" class="mt-0.5 size-4 text-brand-600" /> Presensi dan sertifikat untuk peserta hadir.</li>
            </ul>
        </div>
    </div>
</section>

@if($featuredEvents->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-ink sm:text-3xl">Event mendatang</h2>
            <p class="mt-2 text-sm text-muted">Pilihan event yang akan segera berlangsung.</p>
        </div>
        <a href="{{ route('events.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-600">Lihat semua</a>
    </div>

    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($featuredEvents as $event)
            <a href="{{ route('events.show', $event->slug) }}" class="card group block p-5 transition hover:-translate-y-0.5">
                <div class="flex items-center justify-between gap-3">
                    <span class="badge bg-brand-50 text-brand-700">{{ $event->category->name }}</span>
                    <span class="text-xs font-medium text-muted">{{ $event->start_time->format('d M Y') }}</span>
                </div>
                <h3 class="mt-4 text-lg font-bold leading-snug text-ink group-hover:text-brand-700">{{ $event->title }}</h3>
                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-muted">{{ $event->description }}</p>
                <dl class="mt-4 space-y-2 text-sm text-muted">
                    <div class="flex items-center gap-2"><x-icon name="clock" class="size-4" /><dd>{{ $event->start_time->format('H:i') }} WIB</dd></div>
                    <div class="flex items-center gap-2"><x-icon name="map-pin" class="size-4" /><dd class="truncate">{{ $event->venue ? $event->venue->name : 'Online' }}</dd></div>
                </dl>
                <div class="mt-5 flex items-center justify-between border-t border-line pt-4">
                    <span class="text-sm font-semibold text-ink">
                        @if($event->isFree()) Gratis @else Rp {{ number_format($event->min_price, 0, ',', '.') }} @endif
                    </span>
                    <span class="text-sm font-semibold text-brand-700">Lihat detail</span>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

@if($categories->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="card-soft p-6 sm:p-8">
        <h2 class="text-xl font-bold text-ink">Kategori event</h2>
        <div class="mt-5 flex flex-wrap gap-2">
            @foreach($categories as $cat)
                <a href="{{ route('events.index', ['category' => $cat->slug]) }}" class="rounded-full border border-line bg-surface px-4 py-2 text-sm font-medium text-muted hover:border-brand-200 hover:bg-brand-50 hover:text-brand-700">{{ $cat->name }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
