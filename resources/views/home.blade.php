@extends('layouts.app')

@section('title', 'EventHub — Event kampus jadi mudah')

@section('content')
<div class="bg-bg">
    <section class="relative min-h-[88svh] px-6 sm:px-8">
        <div class="pt-[15vh] sm:pt-[12vh]">
            <h1 class="text-ink" style="font-stretch:150%;">
                <span class="block text-left font-extrabold uppercase leading-[0.86] tracking-[-0.05em]" style="font-size:clamp(64px,12.5vw,200px);">EVENT KAMPUS</span>
                <span class="mt-2 block text-right font-extrabold uppercase leading-[0.86] tracking-[-0.05em]" style="font-size:clamp(64px,12.5vw,200px);">JADI MUDAH</span>
            </h1>
        </div>

        <div class="absolute bottom-8 left-6 right-6 flex flex-col gap-6 sm:left-8 sm:right-8 sm:flex-row sm:items-end sm:justify-between">
            <p class="max-w-[44ch] text-[22px] leading-[1.3] text-ink">
                Temukan, daftar, dan kelola event kampus. Tiket QR dan sertifikat dalam satu platform.
            </p>
            <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center bg-ink px-8 py-4 text-lg font-semibold text-white">
                Lihat event
            </a>
        </div>
    </section>

    <section class="bg-bg px-6 py-20 sm:px-8 sm:py-28">
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-12 bg-field-1 p-7 text-field-1-ink sm:col-span-6 sm:min-h-[70vh] sm:p-8">
                <div class="text-[clamp(56px,8vw,92px)] font-extrabold leading-none">{{ $stats['total_events'] }}</div>
                <div class="mt-auto pt-24 text-lg sm:pt-40">Event terlaksana di kampus</div>
            </div>
            <div class="col-span-6 bg-field-4 p-6 text-field-4-ink sm:col-span-3 sm:mt-[14vh] sm:min-h-[44vh]">
                <div class="text-[clamp(40px,5vw,64px)] font-extrabold leading-none">{{ $stats['total_participants'] }}</div>
                <div class="pt-16 text-base sm:pt-24">Peserta bergabung</div>
            </div>
            <div class="col-span-6 bg-field-2 p-6 text-field-2-ink sm:col-span-3 sm:mt-[26vh] sm:min-h-[34vh]">
                <div class="text-[clamp(40px,5vw,64px)] font-extrabold leading-none">{{ $stats['certificates_issued'] }}</div>
                <div class="pt-12 text-base sm:pt-16">Sertifikat terbit</div>
            </div>
        </div>
        <p class="mt-8 max-w-xl text-base text-muted">Kategori aktif: {{ $categories->count() }}. Organizer: {{ $stats['active_organizers'] }}.</p>
    </section>

    <section class="bg-field-3 px-6 py-20 text-field-3-ink sm:px-8 sm:py-24">
        <h2 class="mb-10 text-[clamp(28px,3vw,40px)] font-extrabold">Event mendatang</h2>
        <div class="space-y-0">
            @forelse($featuredEvents as $event)
                <a href="{{ route('events.show', $event->slug) }}" class="grid grid-cols-12 items-baseline gap-y-1 py-4 text-[clamp(18px,2.1vw,28px)] hover:underline sm:py-3">
                    <span class="col-span-12 font-medium sm:col-span-5">{{ $event->title }}</span>
                    <span class="col-span-6 text-base sm:col-span-3 sm:text-[0.75em]">{{ $event->category->name }}</span>
                    <span class="col-span-6 text-base sm:col-span-2 sm:text-[0.75em]">{{ $event->venue ? $event->venue->name : 'Online' }}</span>
                    <span class="col-span-12 text-right text-base tabular-nums sm:col-span-2 sm:text-[0.75em]">{{ $event->start_time->format('d M Y') }}</span>
                </a>
            @empty
                <p class="py-8 text-xl">Belum ada event mendatang.</p>
            @endforelse
        </div>
        <a href="{{ route('events.index') }}" class="mt-10 inline-block bg-ink px-6 py-3 text-lg font-semibold text-white">Semua event</a>
    </section>

    <section class="bg-bg px-6 py-24 sm:px-8 sm:py-32">
        @php
            $names = collect();
            foreach ($pastEvents as $past) { $names->push($past->organization->name ?? null); }
            foreach ($categories->take(6) as $cat) { $names->push($cat->name); }
            $names = $names->filter()->unique()->take(12)->values();
        @endphp
        <p class="max-w-[20ch] text-[clamp(34px,5vw,72px)] font-extrabold leading-[1.05] text-ink">
            Dipercaya oleh {{ $names->implode(', ') }}.
        </p>
        <p class="mt-8 max-w-xl text-lg text-muted">Event selesai, ulasan peserta, dan sertifikat terverifikasi tersimpan di sistem.</p>
    </section>

    <a href="{{ route('register') }}" class="block bg-accent px-6 py-16 text-accent-ink sm:px-8 sm:py-20" style="min-height:46svh;">
        <div class="flex h-full flex-col justify-between">
            <div class="flex items-start justify-between gap-4">
                <p class="max-w-sm text-lg">Buat akun dan mulai daftar atau kelola event kampus.</p>
                <span class="text-4xl font-bold" aria-hidden="true">↗</span>
            </div>
            <div class="mt-10 font-extrabold uppercase leading-none tracking-[-0.03em]" style="font-size:clamp(72px,22vw,260px); font-stretch:150%;">DAFTAR</div>
        </div>
    </a>
</div>
@endsection
