@extends('layouts.app')

@section('title', 'EventHub — Platform Pengelolaan Event Kampus Terpadu')

@section('content')
<div class="mx-auto max-w-6xl space-y-20 px-6 py-12">
    <section class="grid items-center gap-12 lg:grid-cols-[1.1fr_.9fr]">
        <div class="space-y-7">
            <p class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-3 py-1 text-sm font-medium text-brand-700">
                <x-icon name="calendar" class="size-4" />
                Platform event kampus
            </p>
            <h1 class="max-w-xl text-4xl font-semibold leading-[1.1] tracking-tight text-ink sm:text-5xl lg:text-6xl">
                Kelola dan ikuti event kampus dalam satu tempat.
            </h1>
            <p class="max-w-lg text-lg leading-relaxed text-muted">
                Temukan seminar, workshop, dan kompetisi. Daftar, dapatkan tiket QR, check-in cepat, lalu unduh sertifikat setelah hadir.
            </p>
            <div class="flex flex-wrap items-center gap-3 pt-1">
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-6 py-3 font-semibold text-white transition hover:bg-brand-700">
                    Jelajahi event
                    
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-6 py-3 font-semibold text-ink transition hover:bg-surface-soft">
                    Buat event
                </a>
            </div>
            <div class="flex flex-wrap gap-x-6 gap-y-2 pt-2 text-sm text-muted">
                <span class="inline-flex items-center gap-2"><x-icon name="check-circle" class="size-4 text-brand-600" />Tiket QR real-time</span>
                <span class="inline-flex items-center gap-2"><x-icon name="check-circle" class="size-4 text-brand-600" />Sertifikat otomatis</span>
                <span class="inline-flex items-center gap-2"><x-icon name="check-circle" class="size-4 text-brand-600" />Verifikasi publik</span>
            </div>
        </div>

        <div class="rounded-[28px] border border-line bg-surface p-6 shadow-card">
            <div class="flex items-start justify-between gap-4 border-b border-line pb-5">
                <div>
                    <p class="text-sm font-medium text-brand-600">Contoh tiket</p>
                    <h2 class="mt-1 text-xl font-semibold leading-snug text-ink">National Tech Summit 2026</h2>
                    <p class="mt-1 text-sm text-muted">BEM Fakultas Ilmu Komputer</p>
                </div>
                <span class="rounded-full bg-mint-soft px-3 py-1 text-xs font-semibold text-mint-ink">Terkonfirmasi</span>
            </div>
            <dl class="grid gap-4 py-5 text-sm">
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-muted">Waktu</dt>
                    <dd class="font-medium text-ink">14 hari lagi, 09.00 WIB</dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-muted">Lokasi</dt>
                    <dd class="font-medium text-ink">Auditorium Graha Sasana</dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-muted">Kode tiket</dt>
                    <dd class="rounded-lg bg-surface-soft px-2.5 py-1 font-mono text-xs font-medium text-ink">EVT-2026-TK8812</dd>
                </div>
            </dl>
            <div class="flex items-center justify-between border-t border-line pt-5">
                <p class="text-sm text-muted">Tunjukkan QR ini saat check-in.</p>
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-ink text-xs font-semibold text-white">QR</div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-2 gap-4 rounded-[28px] bg-surface p-6 shadow-card md:grid-cols-4 md:gap-6">
        <div>
            <div class="text-3xl font-semibold tracking-tight text-ink">{{ $stats['total_events'] }}</div>
            <div class="mt-1 text-sm text-muted">Event terlaksana</div>
        </div>
        <div>
            <div class="text-3xl font-semibold tracking-tight text-ink">{{ $stats['total_participants'] }}</div>
            <div class="mt-1 text-sm text-muted">Mahasiswa bergabung</div>
        </div>
        <div>
            <div class="text-3xl font-semibold tracking-tight text-ink">{{ $stats['certificates_issued'] }}</div>
            <div class="mt-1 text-sm text-muted">Sertifikat terbit</div>
        </div>
        <div>
            <div class="text-3xl font-semibold tracking-tight text-ink">{{ $stats['active_organizers'] }}</div>
            <div class="mt-1 text-sm text-muted">Organisasi kampus</div>
        </div>
    </section>

    <section class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-ink">Kategori</h2>
                <p class="mt-1 text-sm text-muted">Pilih minat untuk menemukan event yang relevan.</p>
            </div>
            <a href="{{ route('events.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Lihat semua</a>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @foreach($categories as $category)
                <a href="{{ route('events.index', ['category' => $category->slug]) }}"
                    class="group rounded-2xl border border-line bg-surface p-4 text-center transition hover:-translate-y-0.5 hover:shadow-card">
                    <span class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <x-icon name="{{ $category->icon ?? 'map-pin' }}" class="size-5" />
                    </span>
                    <h3 class="text-sm font-semibold text-ink">{{ $category->name }}</h3>
                    <p class="mt-1 text-xs text-muted">{{ $category->events_count }} event</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-ink">Event mendatang</h2>
                <p class="mt-1 text-sm text-muted">Pilih event, daftar, dan simpan tiketmu.</p>
            </div>
            <a href="{{ route('events.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Lihat semua</a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($featuredEvents as $event)
                <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-surface transition hover:-translate-y-0.5 hover:shadow-card">
                    <div class="p-5">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <span class="rounded-full bg-surface-soft px-3 py-1 text-xs font-medium text-muted">{{ $event->category->name }}</span>
                            @if($event->isFree())
                                <span class="rounded-full bg-mint-soft px-3 py-1 text-xs font-semibold text-mint-ink">Gratis</span>
                            @else
                                <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">Mulai Rp {{ number_format($event->min_price, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <h3 class="text-lg font-semibold leading-snug text-ink">
                            <a href="{{ route('events.show', $event->slug) }}" class="hover:text-brand-700">{{ $event->title }}</a>
                        </h3>
                        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-muted">{{ $event->description }}</p>

                        <div class="mt-5 space-y-2 text-sm text-muted">
                            <div class="flex items-center gap-2">
                                <x-icon name="calendar" class="size-4" />
                                <span>{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-icon name="map-pin" class="size-4" />
                                <span class="truncate">{{ $event->venue ? $event->venue->name : 'Daring / Online' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-icon name="users" class="size-4" />
                                <span class="truncate">{{ $event->organization->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto border-t border-line p-4">
                        <a href="{{ route('events.show', $event->slug) }}" class="inline-flex w-full items-center justify-center rounded-full bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">
                            Lihat detail
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-line bg-surface p-10 text-center">
                    <p class="font-medium text-ink">Belum ada event mendatang yang dipublikasikan.</p>
                    <p class="mt-1 text-sm text-muted">Cek kembali nanti atau jelajahi arsip event.</p>
                </div>
            @endforelse
        </div>
    </section>

    @if($pastEvents->isNotEmpty())
        <section class="space-y-6">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-ink">Event sebelumnya</h2>
                <p class="mt-1 text-sm text-muted">Ringkasan dan ulasan dari peserta.</p>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($pastEvents as $past)
                    <div class="rounded-2xl border border-line bg-surface p-5">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <span class="rounded-full bg-surface-soft px-3 py-1 text-xs font-medium text-muted">Selesai</span>
                            <div class="inline-flex items-center gap-1 text-sm font-medium text-amber-700">
                                <x-icon name="star" class="size-4" />
                                {{ number_format($past->average_rating, 1) }} ({{ $past->review_count }})
                            </div>
                        </div>
                        <h4 class="font-semibold leading-snug text-ink">{{ $past->title }}</h4>
                        @if($past->reviews->isNotEmpty())
                            <p class="mt-3 rounded-xl bg-surface-soft p-3 text-sm leading-relaxed text-muted">
                                "{{ mb_strimwidth($past->reviews->first()->comment, 0, 120, '...') }}"
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
