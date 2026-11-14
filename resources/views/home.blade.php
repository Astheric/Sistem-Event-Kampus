@extends('layouts.app')

@section('title', 'EventHub — Agenda Event Kampus')

@section('content')
<section class="border-b border-line">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.3fr_.7fr] lg:px-8 lg:py-20">
        <div>
            <h1 class="max-w-4xl text-[clamp(2.8rem,7vw,6.5rem)] font-black uppercase leading-[0.9] tracking-tight text-ink">
                Agenda<br>Event<br>Kampus.
            </h1>
            <p class="mt-8 max-w-xl text-lg leading-relaxed text-muted sm:text-xl">
                Temukan seminar, workshop, dan kegiatan kampus. Daftar, dapatkan tiket, hadir, dan klaim sertifikat — dari satu tempat.
            </p>
            <div class="mt-10 flex flex-wrap items-center gap-4">
                <a href="{{ route('events.index') }}" class="btn btn-primary">Lihat agenda</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-secondary">Buat akun</a>
                @endguest
            </div>
        </div>

        <div class="lg:pt-4">
            <form action="{{ route('events.index') }}" method="GET" class="card p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-muted">Cari cepat</p>
                <div class="mt-5 space-y-4">
                    <div>
                        <label for="q" class="label">Kata kunci</label>
                        <input id="q" type="text" name="q" placeholder="Seminar, workshop, lomba..." class="input">
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
                </div>
            </form>

            <dl class="mt-6 grid grid-cols-2 gap-px border border-line bg-line">
                <div class="bg-surface p-4">
                    <dt class="text-xs font-bold uppercase tracking-wide text-muted">Event</dt>
                    <dd class="mt-1 text-3xl font-black">{{ $stats['total_events'] ?? 0 }}</dd>
                </div>
                <div class="bg-surface p-4">
                    <dt class="text-xs font-bold uppercase tracking-wide text-muted">Peserta</dt>
                    <dd class="mt-1 text-3xl font-black">{{ $stats['total_participants'] ?? 0 }}</dd>
                </div>
                <div class="bg-surface p-4">
                    <dt class="text-xs font-bold uppercase tracking-wide text-muted">Sertifikat</dt>
                    <dd class="mt-1 text-3xl font-black">{{ $stats['certificates_issued'] ?? 0 }}</dd>
                </div>
                <div class="bg-surface p-4">
                    <dt class="text-xs font-bold uppercase tracking-wide text-muted">Organizer</dt>
                    <dd class="mt-1 text-3xl font-black">{{ $stats['active_organizers'] ?? 0 }}</dd>
                </div>
            </dl>
        </div>
    </div>
</section>

@if($featuredEvents->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between gap-4 border-b border-line pb-4">
        <h2 class="text-2xl font-black uppercase tracking-tight sm:text-3xl">Akan datang</h2>
        <a href="{{ route('events.index') }}" class="text-sm font-bold uppercase tracking-wide text-muted hover:text-ink">Semua event</a>
    </div>

    <div class="mt-2 divide-y divide-line border-b border-line">
        @foreach($featuredEvents as $event)
            <a href="{{ route('events.show', $event->slug) }}" class="group grid gap-3 py-6 md:grid-cols-[140px_1fr_220px_140px] md:items-start">
                <div class="text-sm font-bold uppercase tracking-wide text-muted">
                    <div class="text-2xl font-black text-ink">{{ $event->start_time->format('d') }}</div>
                    <div>{{ $event->start_time->translatedFormat('M Y') }}</div>
                    <div class="mt-1 font-medium normal-case tracking-normal">{{ $event->start_time->format('H:i') }} WIB</div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-muted">{{ $event->category->name }}</p>
                    <h3 class="mt-1 text-xl font-black leading-tight tracking-tight group-hover:text-accent sm:text-2xl">{{ $event->title }}</h3>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-muted line-clamp-2">{{ $event->description }}</p>
                </div>
                <div class="text-sm text-muted">
                    <p class="font-bold uppercase tracking-wide text-ink">Lokasi</p>
                    <p class="mt-1">{{ $event->venue ? $event->venue->name : 'Online' }}</p>
                    <p class="mt-3 font-bold uppercase tracking-wide text-ink">Penyelenggara</p>
                    <p class="mt-1">{{ $event->organization->name }}</p>
                </div>
                <div class="md:text-right">
                    <p class="text-lg font-black text-ink">@if($event->isFree()) Gratis @else Rp {{ number_format($event->min_price, 0, ',', '.') }} @endif</p>
                    <p class="mt-2 text-sm font-bold uppercase tracking-wide text-accent">Detail →</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

@if($categories->isNotEmpty())
<section class="bg-field-1 text-field-1-ink">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-black uppercase tracking-tight sm:text-3xl">Jelajah kategori</h2>
        <div class="mt-6 flex flex-wrap gap-3">
            @foreach($categories as $cat)
                <a href="{{ route('events.index', ['category' => $cat->slug]) }}" class="border border-ink/30 bg-surface/20 px-4 py-2 text-sm font-bold uppercase tracking-wide hover:bg-ink hover:text-surface">{{ $cat->name }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
