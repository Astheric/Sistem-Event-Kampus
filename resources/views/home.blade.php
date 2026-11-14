@extends('layouts.app')

@section('title', 'EventHub — Platform Pengelolaan Event Kampus Terpadu')

@section('content')
<div class="space-y-16 py-8">
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-7 space-y-6">
                <h1 class="text-4xl sm:text-6xl font-bold tracking-tight leading-[1.05]">
                    CREATE & MANAGE EVENTS.
                </h1>

                <p class="text-base sm:text-lg font-bold text-muted max-w-xl leading-relaxed">
                    Sistem satu pintu untuk seluruh aktivitas kampus. Temukan seminar, workshop, festival, dan kompetisi. Dapatkan tiket QR real-time dan unduh e-sertifikat terverifikasi langsung setelah hadir!
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ route('events.index') }}"
                        class="bg-brand-600 text-white hover:bg-brand-600 text-white border border-line px-6 py-4 font-bold text-base  tracking-wider shadow-card hover:-translate-y-0.5 active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                        <x-icon name="search" class="size-4" /> Jelajah Katalog Event →
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-brand-600 text-white hover:bg-brand-600 text-white-soft border border-line px-6 py-4 font-bold text-base  tracking-wider shadow-card hover:-translate-y-0.5 active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                        <x-icon name="home" class="size-4" /> Buat Event Kampus
                    </a>
                </div>

                <!-- Feature Badges -->
                <div class="flex flex-wrap items-center gap-3 pt-4 text-xs font-bold">
                    <span class="bg-surface border border-line px-3 py-1 shadow-card rounded-2xl">
                        <x-icon name="check" class="size-4" /> Anti-Double Check-in
                    </span>
                    <span class="bg-surface border border-line px-3 py-1 shadow-card rounded-2xl">
                        <x-icon name="check" class="size-4" /> E-Sertifikat Otomatis
                    </span>
                    <span class="bg-surface border border-line px-3 py-1 shadow-card rounded-2xl">
                        <x-icon name="check" class="size-4" /> Verifikasi Publik
                    </span>
                </div>
            </div>

            <!-- Hero Graphic Card (Neo-Brutalism Ticket Mockup) -->
            <div class="lg:col-span-5">
                <div class="bg-surface border border-line p-6 shadow-card relative rounded-2xl">
                    <div class="border-b border-line pb-4">
                        <h3 class="text-xl font-bold mt-1 leading-snug">National Tech Summit 2026: AI & Cyber Security Horizons</h3>
                        <p class="text-xs font-bold text-muted mt-1">Oleh: BEM Fakultas Ilmu Komputer</p>
                    </div>

                    <div class="py-4 space-y-3 text-sm border-b border-line">
                        <div class="flex justify-between">
                            <span class="text-muted">WAKTU:</span>
                            <span class="font-mono font-bold">14 Hari Lagi • 09:00 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted">LOKASI:</span>
                            <span class="font-bold">Auditorium Graha Sasana</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted">KODE TIKET:</span>
                            <span class="font-mono bg-surface px-1 border border-line font-bold">EVT-2026-TK8812</span>
                        </div>
                    </div>

                    <!-- Bottom Mockup Details with QR Frame -->
                    <div class="pt-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold  text-muted">STATUS TIKET:</span>
                            <div class="bg-surface border border-line px-2 py-0.5 text-xs font-bold  inline-block mt-0.5">
                                CONFIRMED
                            </div>
                        </div>

                        <!-- Simulated QR Code Icon Box -->
                        <div class="w-16 h-16 bg-surface border border-line p-1 flex items-center justify-center text-xs font-bold shadow-card text-center rounded-2xl">
                            QR
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Platform Stats Bar -->
    <section class="border-y border-line bg-surface py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
                    <div class="text-3xl sm:text-4xl font-bold font-mono">{{ $stats['total_events'] }}</div>
                    <div class="text-xs font-bold  mt-1">Event Terlaksana</div>
                </div>
                <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
                    <div class="text-3xl sm:text-4xl font-bold font-mono">{{ $stats['total_participants'] }}</div>
                    <div class="text-xs font-bold  mt-1">Mahasiswa Bergabung</div>
                </div>
                <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
                    <div class="text-3xl sm:text-4xl font-bold font-mono">{{ $stats['certificates_issued'] }}</div>
                    <div class="text-xs font-bold  mt-1">Sertifikat Terbit</div>
                </div>
                <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
                    <div class="text-3xl sm:text-4xl font-bold font-mono">{{ $stats['active_organizers'] }}</div>
                    <div class="text-xs font-bold  mt-1">Organisasi Kampus</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold">Temukan Minat & Bakatmu</h2>
            </div>
            <a href="{{ route('events.index') }}" class="font-bold text-sm hover:underline flex items-center gap-1">
                Lihat Semua Kategori →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('events.index', ['category' => $category->slug]) }}"
                    class="bg-brand-600 text-white border border-line p-4 shadow-card hover:bg-brand-600 text-white hover:-translate-y-1 active:translate-x-1 active:translate-y-1 active:shadow-none transition-all text-center group rounded-full">
                    <x-icon name="{{ $category->icon ?? 'map-pin' }}" class="size-8 mx-auto mb-2" />
                    <h3 class="font-bold text-sm group-hover:underline">{{ $category->name }}</h3>
                    <span class="inline-block mt-2 text-[10px] font-mono font-bold bg-danger text-white px-2 py-0.5">
                        {{ $category->events_count }} Event
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Featured Events Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold">Event Pilihan Terhangat</h2>
            </div>
            <a href="{{ route('events.index') }}" class="font-bold text-sm hover:underline flex items-center gap-1">
                Buka Katalog Lengkap →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredEvents as $event)
                <div class="bg-surface border border-line shadow-card flex flex-col justify-between hover:-translate-y-1 transition-transform rounded-2xl">
                    <div>
                        <!-- Card Header & Badges -->
                        <div class="p-4 border-b border-line bg-surface-soft flex items-center justify-between">
                            <span class="bg-surface border border-line px-2 py-0.5 text-xs font-bold ">
                                {{ $event->category->name }}
                            </span>
                            @if($event->isFree())
                                <span class="bg-surface border border-line px-2 py-0.5 text-xs font-bold  font-mono">
                                    GRATIS
                                </span>
                            @else
                                <span class="bg-surface border border-line px-2 py-0.5 text-xs font-bold  font-mono">
                                    Mulai Rp {{ number_format($event->min_price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 space-y-4">
                            <h3 class="text-xl font-bold leading-snug line-clamp-2 hover:text-ink">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <p class="text-xs font-bold text-muted line-clamp-3 leading-relaxed">
                                {{ $event->description }}
                            </p>

                            <div class="pt-2 border-t border-line space-y-2 text-sm">
                                <div class="flex items-center gap-2">
                                    <span><x-icon name="calendar" class="size-4" /></span>
                                    <span class="font-mono">{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span><x-icon name="map-pin" class="size-4" /></span>
                                    <span class="truncate">{{ $event->venue ? $event->venue->name : 'Daring / Online' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span><x-icon name="home" class="size-4" /></span>
                                    <span class="text-muted truncate">{{ $event->organization->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Button -->
                    <div class="p-4 border-t border-line bg-surface-soft">
                        <a href="{{ route('events.show', $event->slug) }}"
                            class="block w-full text-center bg-brand-600 text-white hover:bg-brand-600 text-white border border-line py-2.5 font-bold text-sm  tracking-wider shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                            Lihat Tiket & Detail →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-surface border border-line shadow-card p-6 rounded-2xl">
                    <p class="font-bold text-base">Belum ada event mendatang yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Past Events & Testimonials -->
    @if($pastEvents->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h2 class="text-3xl font-bold">Event Sukses Sebelumnya</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($pastEvents as $past)
                    <div class="bg-surface border border-line p-5 shadow-card rounded-2xl">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-danger text-white px-2 py-0.5 text-[10px] font-bold  font-mono">SELESAI</span>
                            <div class="flex text-amber-600 font-bold text-sm">
                                <x-icon name="star" class="size-4" /> {{ number_format($past->average_rating, 1) }} ({{ $past->review_count }})
                            </div>
                        </div>
                        <h4 class="font-bold text-base leading-snug line-clamp-2">{{ $past->title }}</h4>
                        @if($past->reviews->isNotEmpty())
                            <p class="mt-3 text-xs italic font-bold text-muted bg-surface-soft p-3 border border-line">
                                "{{ mb_strimwidth($past->reviews->first()->comment, 0, 100, '...') }}"
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
