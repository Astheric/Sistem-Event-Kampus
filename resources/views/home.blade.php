@extends('layouts.app')

@section('title', 'EventHub : Agenda Kegiatan Kampus')

@section('content')
<div class="space-y-16 py-8 lg:py-12">
    {{-- Hero Section --}}
    <section class="mx-auto max-w-7xl px-6 sm:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-12">
            <div class="lg:col-span-7 space-y-6">
                <h1 class="text-3xl font-extrabold tracking-tight text-ink sm:text-5xl lg:text-6xl leading-tight">
                    Jadwal dan pendaftaran kegiatan kampus
                </h1>

                <p class="max-w-xl text-base sm:text-lg text-muted leading-relaxed">
                    Lihat jadwal seminar, lokakarya, dan kegiatan mahasiswa. Daftar online, dapatkan tiket, dan unduh sertifikat kehadiran.
                </p>

                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <a href="{{ route('events.index') }}" class="btn btn-primary px-6 py-3 text-sm">
                        Lihat daftar event
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-secondary px-6 py-3 text-sm">
                        Daftar sebagai penyelenggara
                    </a>
                </div>

                {{-- Key Statistics --}}
                <div class="pt-6 grid grid-cols-3 gap-4 border-t border-line">
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">
                            {{ $stats['total_events'] ?? 0 }}
                        </div>
                        <p class="text-xs sm:text-sm text-muted mt-0.5">Event terdaftar</p>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">
                            {{ $stats['total_participants'] ?? 0 }}
                        </div>
                        <p class="text-xs sm:text-sm text-muted mt-0.5">Pendaftar aktif</p>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">
                            {{ $stats['certificates_issued'] ?? 0 }}
                        </div>
                        <p class="text-xs sm:text-sm text-muted mt-0.5">Sertifikat terbit</p>
                    </div>
                </div>
            </div>

            {{-- Spotlight Event --}}
            <div class="lg:col-span-5">
                @php $spotlight = $featuredEvents->first(); @endphp
                @if($spotlight)
                    <div class="rounded-2xl border border-line bg-surface p-6 sm:p-7 space-y-4">
                        <img src="{{ $spotlight->bannerUrl() }}" alt="{{ $spotlight->title }}" class="aspect-[16/9] w-full rounded-xl object-cover bg-slate-100" loading="lazy">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center rounded-md bg-brand-50 px-2.5 py-0.5 text-xs font-semibold text-brand-700">
                                {{ $spotlight->category->name }}
                            </span>
                            @if($spotlight->isFree())
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                    Gratis
                                </span>
                            @else
                                <span class="text-xs font-bold text-ink">
                                    Rp {{ number_format($spotlight->min_price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <h2 class="text-xl font-bold leading-snug text-ink">
                                <a href="{{ route('events.show', $spotlight->slug) }}" class="hover:text-brand-700">
                                    {{ $spotlight->title }}
                                </a>
                            </h2>
                            <p class="mt-1 text-sm text-muted">
                                {{ $spotlight->organization->name }}
                            </p>
                        </div>

                        <p class="line-clamp-3 text-sm text-muted leading-relaxed">
                            {{ $spotlight->description }}
                        </p>

                        <div class="pt-2 border-t border-line space-y-1.5 text-xs text-muted">
                            <div class="flex items-center gap-2">
                                <x-icon name="calendar" class="size-3.5 text-slate-400" />
                                <span>{{ $spotlight->start_time->translatedFormat('l, d F Y') }} · {{ $spotlight->start_time->format('H:i') }} WIB</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-icon name="map-pin" class="size-3.5 text-slate-400" />
                                <span>{{ $spotlight->venue ? $spotlight->venue->name : 'Daring / Online' }}</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('events.show', $spotlight->slug) }}" class="btn btn-primary w-full text-xs py-2.5">
                                Detail dan pendaftaran
                            </a>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-line bg-surface p-8 text-center space-y-3">
                        <x-icon name="calendar" class="size-10 mx-auto text-slate-300" />
                        <h2 class="text-base font-bold text-ink">Belum ada event dipublikasikan</h2>
                        <p class="text-xs text-muted">Cek katalog untuk melihat jadwal kegiatan.</p>
                        <a href="{{ route('events.index') }}" class="btn btn-secondary text-xs">
                            Buka katalog event
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Categories Strip --}}
    @if($categories->isNotEmpty())
    <section class="mx-auto max-w-7xl px-6 sm:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-xl font-bold text-ink">Kategori event</h2>
            </div>
            <a href="{{ route('events.index') }}" class="text-xs font-semibold text-brand-700 hover:text-brand-800">
                Lihat semua kategori ({{ $categories->sum('events_count') }})
            </a>
        </div>

        <div class="flex flex-wrap gap-2.5">
            @foreach($categories as $category)
                <a href="{{ route('events.index', ['category' => $category->slug]) }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-line bg-surface px-4 py-2.5 text-sm font-semibold text-ink hover:bg-surface-soft hover:border-slate-300 transition-colors">
                    <x-icon name="{{ $category->icon ?? 'tag' }}" class="size-4 text-brand-700" />
                    <span>{{ $category->name }}</span>
                    <span class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-muted font-normal">
                        {{ $category->events_count }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Featured Events --}}
    @if($featuredEvents->isNotEmpty())
    <section class="mx-auto max-w-7xl px-6 sm:px-8">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-ink">Event yang sedang dibuka</h2>
                <p class="mt-1 text-sm text-muted">Pilih kegiatan dan daftar sebelum kuota penuh.</p>
            </div>
            <a href="{{ route('events.index') }}" class="text-xs font-semibold text-brand-700 hover:text-brand-800">
                Lihat semua event
            </a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($featuredEvents as $event)
                <div class="rounded-2xl border border-line bg-surface p-6 flex flex-col justify-between space-y-4 hover:border-slate-300 transition-colors">
                    <img src="{{ $event->bannerUrl() }}" alt="{{ $event->title }}" class="w-full aspect-[16/9] rounded-xl object-cover bg-slate-100" loading="lazy">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center rounded-md bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-700">
                                {{ $event->category->name }}
                            </span>
                            @if($event->isFree())
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700">
                                    Gratis
                                </span>
                            @else
                                <span class="text-xs font-bold text-ink">
                                    Rp {{ number_format($event->min_price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold leading-snug text-ink">
                            <a href="{{ route('events.show', $event->slug) }}" class="hover:text-brand-700">
                                {{ $event->title }}
                            </a>
                        </h3>

                        <p class="line-clamp-2 text-xs leading-relaxed text-muted">
                            {{ $event->description }}
                        </p>

                        <div class="pt-2 border-t border-line space-y-1.5 text-xs text-muted">
                            <div class="flex items-center gap-2">
                                <x-icon name="calendar" class="size-3.5 text-slate-400" />
                                <span>{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-icon name="map-pin" class="size-3.5 text-slate-400" />
                                <span class="truncate">{{ $event->venue ? $event->venue->name : 'Daring / Online' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-icon name="users" class="size-3.5 text-slate-400" />
                                <span class="truncate">{{ $event->organization->name }}</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <x-capacity-meter :capacity="$event->capacity()" />
                        </div>
                    </div>

                    <div class="pt-3 border-t border-line">
                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary w-full text-xs py-2.5">
                            Lihat detail event
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Bottom Banner --}}
    <section class="mx-auto max-w-7xl px-6 sm:px-8">
        <div class="overflow-hidden rounded-2xl border border-line bg-surface">
            <div class="grid lg:grid-cols-12">
                <div class="flex flex-col justify-center gap-6 px-6 py-8 sm:px-10 sm:py-10 lg:col-span-6">
                    <div class="max-w-xl space-y-4">
                        <h2 class="text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                            Punya agenda kegiatan di kampus?
                        </h2>
                        <p class="text-sm leading-relaxed text-muted sm:text-base">
                            Daftarkan unit kegiatan mahasiswa, himpunan, atau fakultas Anda untuk mempublikasikan event, mengelola tiket, dan mencatat kehadiran peserta.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="btn btn-primary px-6 py-3 text-base">
                            Daftar sebagai organizer
                        </a>
                    </div>
                </div>

                <figure class="relative min-h-[240px] lg:col-span-6 lg:min-h-full">
                    <img
                        src="{{ asset('images/cta-organizer.jpg') }}"
                        alt="Mahasiswa berdiskusi dalam pertemuan di kampus"
                        class="h-full w-full object-cover"
                        loading="lazy"
                        decoding="async"
                        width="1920"
                        height="1440"
                    >

                </figure>
            </div>
        </div>
    </section>
</div>
@endsection
