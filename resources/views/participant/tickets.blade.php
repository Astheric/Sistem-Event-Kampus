@extends('layouts.dashboard')

@section('title', 'Tiket Saya — EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl">
        <div>
            <h1 class="text-3xl font-bold">Tiket Terdaftar Saya</h1>
            <p class="text-xs font-bold text-muted mt-1">Daftar seluruh e-tiket event kampus yang telah Anda pesan.</p>
        </div>
        <a href="{{ route('events.index') }}"
            class="bg-brand-600 text-white border border-line px-4 py-2 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap rounded-full">
            + Cari Event Lain
        </a>
    </div>

    <!-- Status Tabs -->
    <div class="flex flex-wrap gap-2 text-xs font-bold">
        <a href="{{ route('participant.tickets.index') }}"
            class="px-3 py-1.5 border border-line {{ !request('status') ? 'bg-surface shadow-card' : 'bg-surface hover:bg-surface-soft' }} rounded-2xl">
            Semua (All)
        </a>
        <a href="{{ route('participant.tickets.index', ['status' => 'confirmed']) }}"
            class="px-3 py-1.5 border border-line {{ request('status') === 'confirmed' ? 'bg-surface shadow-card' : 'bg-surface hover:bg-surface-soft' }} rounded-2xl">
            Aktif (CONFIRMED)
        </a>
        <a href="{{ route('participant.tickets.index', ['status' => 'pending']) }}"
            class="px-3 py-1.5 border border-line {{ request('status') === 'pending' ? 'bg-surface shadow-card' : 'bg-surface hover:bg-surface-soft' }} rounded-2xl">
            Menunggu Verifikasi (PENDING)
        </a>
        <a href="{{ route('participant.tickets.index', ['status' => 'attended']) }}"
            class="px-3 py-1.5 border border-line {{ request('status') === 'attended' ? 'bg-surface shadow-card' : 'bg-surface hover:bg-surface-soft' }} rounded-2xl">
            Telah Hadir (ATTENDED)
        </a>
    </div>

    <!-- Ticket Cards List -->
    <div class="space-y-4">
        @forelse($registrations as $reg)
            @php
                $statusColors = [
                    'CONFIRMED' => 'bg-surface',
                    'PENDING' => 'bg-surface',
                    'ATTENDED' => 'bg-surface',
                    'CANCELLED' => 'bg-surface',
                    'COMPLETED' => 'bg-danger text-white',
                ];
            @endphp

            <div class="bg-surface border border-line p-6 shadow-card flex flex-col md:flex-row md:items-center justify-between gap-6 rounded-2xl">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="border border-line px-2.5 py-0.5 text-xs font-bold  {{ $statusColors[$reg->status] ?? 'bg-surface-soft' }}">
                            {{ $reg->status }}
                        </span>
                        <span class="font-mono text-xs font-bold bg-surface border border-line px-2 py-0.5">
                            {{ $reg->registration_code }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold">
                            <a href="{{ route('events.show', $reg->event->slug) }}" class="hover:underline">
                                {{ $reg->event->title }}
                            </a>
                        </h3>
                        <p class="text-xs font-bold text-muted mt-0.5">
                            Tipe Tiket: <span class="font-bold text-black">{{ $reg->ticket->name }}</span>
                            @if($reg->ticket->price > 0)
                                • Rp {{ number_format($reg->ticket->price, 0, ',', '.') }}
                            @else
                                • <span class="text-ink">Gratis</span>
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-4 text-xs font-bold text-muted">
                        <span><x-icon name="calendar" class="size-4" /> {{ $reg->event->start_time->format('d M Y, H:i') }} WIB</span>
                        <span><x-icon name="map-pin" class="size-4" /> {{ $reg->event->venue ? $reg->event->venue->name : 'Online' }}</span>
                    </div>

                    @if($reg->isAttended() && $reg->checkin)
                        <div class="bg-surface border border-line p-2 text-xs font-bold">
                            <x-icon name="check-circle" class="size-4" /> Check-in tercatat pada: <span class="font-mono">{{ $reg->checkin->checked_in_at->format('d M Y H:i') }} WIB</span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row md:flex-col gap-2 min-w-[180px]">
                    <a href="{{ route('participant.tickets.show', $reg->id) }}"
                        class="text-center bg-brand-600 text-white hover:bg-brand-600 text-white border border-line py-2 px-3 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                        <x-icon name="tag" class="size-4" /> Buka E-Tiket & QR
                    </a>

                    <a href="{{ route('participant.tickets.download', $reg->id) }}"
                        class="text-center bg-brand-600 text-white hover:bg-brand-600 text-white-soft border border-line py-2 px-3 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                        <x-icon name="download" class="size-4" /> Unduh PDF Tiket
                    </a>

                    @if($reg->isAttended() || $reg->isCompleted())
                        <a href="{{ route('participant.certificates.index') }}"
                            class="text-center bg-brand-600 text-white hover:bg-brand-600 text-white border border-line py-2 px-3 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                            <x-icon name="book-open" class="size-4" /> Klaim Sertifikat
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-surface border border-line p-12 text-center shadow-card rounded-2xl">
                <span class="text-4xl block mb-2"><x-icon name="tag" class="size-4" /></span>
                <h3 class="font-bold text-lg">Belum Ada Tiket Terdaftar</h3>
                <p class="text-xs font-bold text-muted mt-1">Anda belum memesan tiket untuk kategori ini.</p>
                <a href="{{ route('events.index') }}" class="inline-block mt-4 bg-surface border border-line px-4 py-2 font-bold text-xs  shadow-card rounded-2xl">
                    Jelajah Event Sekarang
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div>
        {{ $registrations->links() }}
    </div>
</div>
@endsection
