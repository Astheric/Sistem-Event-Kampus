@extends('layouts.app')

@section('title', 'EventHub : Agenda Kegiatan Kampus')

@section('content')
<div class="space-y-16 pb-8 lg:pb-12">
    {{-- Hero Section --}}
    @php
        $heroSlides = $featuredEvents->take(5)->map(fn ($event) => [
            'image' => $event->bannerUrl(),
            'title' => $event->title,
        ])->values();
        if ($heroSlides->isEmpty()) {
            $heroSlides = collect([
                ['image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1600&q=80', 'title' => 'Kegiatan kampus'],
                ['image' => 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=1600&q=80', 'title' => 'Seminar mahasiswa'],
                ['image' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?auto=format&fit=crop&w=1600&q=80', 'title' => 'Kolaborasi kampus'],
            ]);
        }
    @endphp
    <section
        x-data="{
            current: 0,
            slides: @js($heroSlides),
            timer: null,
            start() {
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || this.slides.length < 2) return;
                this.timer = setInterval(() => { this.current = (this.current + 1) % this.slides.length }, 5000);
            },
            stop() { if (this.timer) { clearInterval(this.timer); this.timer = null; } },
            go(index) { this.current = index; this.stop(); this.start(); }
        }"
        x-init="start()"
        @mouseenter="stop()"
        @mouseleave="start()"
        class="relative isolate min-h-[78vh] overflow-hidden bg-slate-900"
    >
        <div class="absolute inset-0">
            <template x-for="(slide, index) in slides" :key="index">
                <div
                    x-show="current === index"
                    x-transition:enter="transition-opacity duration-700 ease-out"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-700 ease-in"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0"
                >
                    <img :src="slide.image" :alt="slide.title" class="h-full w-full object-cover">
                </div>
            </template>
            <div class="absolute inset-0 bg-slate-950/55"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/70 via-slate-950/45 to-slate-950/20"></div>
        </div>

        <div class="relative mx-auto flex min-h-[78vh] max-w-7xl items-center px-6 py-20 sm:px-8 lg:py-24">
            <div class="max-w-3xl">
                <h1 class="max-w-2xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl lg:leading-[1.05]">
                    Semua event kampus, satu tempat untuk mendaftar.
                </h1>

                <p class="mt-6 max-w-xl text-base leading-relaxed text-white/85 sm:text-lg">
                    Lihat jadwal, daftar online, dan simpan tiket serta sertifikat kehadiranmu.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('events.index') }}" class="btn btn-primary px-6 py-3.5 text-sm sm:text-base">
                        Lihat katalog event
                    </a>
                </div>


            </div>
        </div>

        <div class="absolute inset-x-0 bottom-6">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 sm:px-8">
                <div class="flex items-center gap-2">
                    <template x-for="(slide, index) in slides" :key="'dot-'+index">
                        <button
                            type="button"
                            class="h-2.5 w-2.5 rounded-full transition"
                            :class="current === index ? 'bg-white' : 'bg-white/40 hover:bg-white/70'"
                            :aria-label="'Slide ' + (index + 1)"
                            @click="go(index)"
                        ></button>
                    </template>
                </div>

                <div class="hidden items-center gap-2 sm:flex">
                    <button type="button" class="inline-flex size-10 items-center justify-center rounded-full border border-white/30 text-white hover:bg-white/10 transition" aria-label="Slide sebelumnya" @click="go((current - 1 + slides.length) % slides.length)">
                        <x-icon name="chevron-left" class="size-5" />
                    </button>
                    <button type="button" class="inline-flex size-10 items-center justify-center rounded-full border border-white/30 text-white hover:bg-white/10 transition" aria-label="Slide berikutnya" @click="go((current + 1) % slides.length)">
                        <x-icon name="chevron-right" class="size-5" />
                    </button>
                </div>
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
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center rounded-md bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-700">
                                    {{ $event->category->name }}
                                </span>
                                <x-audience-badge :audience="$event->audience" class="!px-2 !py-0.5 !rounded-md" />
                            </div>
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
