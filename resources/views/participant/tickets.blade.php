@extends('layouts.dashboard')

@section('title', 'Tiket Saya')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-ink">Tiket saya</h1>
        </div>
        <a href="{{ route('events.index') }}"
            class="btn btn-primary text-xs py-2 px-3.5 whitespace-nowrap self-start sm:self-auto">
            Cari event
        </a>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="{{ route('participant.tickets.index') }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ !request('status') ? 'bg-brand-700 text-white' : 'bg-surface border border-line text-slate-600 hover:bg-surface-soft hover:text-ink' }}">
            Semua
        </a>
        <a href="{{ route('participant.tickets.index', ['status' => 'confirmed']) }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request('status') === 'confirmed' ? 'bg-brand-700 text-white' : 'bg-surface border border-line text-slate-600 hover:bg-surface-soft hover:text-ink' }}">
            Aktif
        </a>
        <a href="{{ route('participant.tickets.index', ['status' => 'pending']) }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request('status') === 'pending' ? 'bg-brand-700 text-white' : 'bg-surface border border-line text-slate-600 hover:bg-surface-soft hover:text-ink' }}">
            Menunggu verifikasi
        </a>
        <a href="{{ route('participant.tickets.index', ['status' => 'attended']) }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request('status') === 'attended' ? 'bg-brand-700 text-white' : 'bg-surface border border-line text-slate-600 hover:bg-surface-soft hover:text-ink' }}">
            Hadir
        </a>
    </div>

    <!-- Ticket Cards List -->
    <div class="space-y-4">
        @forelse($registrations as $reg)
            @php
                $statusBadge = match($reg->status) {
                    'CONFIRMED' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                    'ATTENDED' => 'bg-brand-50 text-brand-700 border-brand-200/60',
                    'PENDING' => 'bg-amber-50 text-amber-800 border-amber-200/60',
                    'CANCELLED' => 'bg-red-50 text-red-700 border-red-200/60',
                    'COMPLETED' => 'bg-slate-100 text-slate-700 border-slate-200',
                    default => 'bg-slate-100 text-slate-600 border-slate-200',
                };
            @endphp

            <div class="bg-surface rounded-2xl border border-line/60 p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 transition hover:border-slate-300">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold {{ $statusBadge }}">
                            {{ $reg->status }}
                        </span>
                        <span class="font-mono text-xs font-semibold bg-surface-soft px-2.5 py-0.5 rounded text-slate-700">
                            {{ $reg->registration_code }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-ink">
                            <a href="{{ route('events.show', $reg->event->slug) }}" class="hover:text-brand-700 transition">
                                {{ $reg->event->title }}
                            </a>
                        </h3>
                        <p class="text-xs text-muted mt-1 font-medium">
                            Tipe Tiket: <span class="font-bold text-ink">{{ $reg->ticket->name }}</span>
                            @if($reg->ticket->price > 0)
                                • Rp {{ number_format($reg->ticket->price, 0, ',', '.') }}
                            @else
                                • <span class="text-emerald-700 font-semibold">Gratis</span>
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-4 text-xs text-muted font-medium">
                        <span class="inline-flex items-center gap-1.5"><x-icon name="calendar" class="size-4 text-slate-400" /> {{ $reg->event->start_time->format('d M Y, H:i') }} WIB</span>
                        <span class="inline-flex items-center gap-1.5"><x-icon name="map-pin" class="size-4 text-slate-400" /> {{ $reg->event->venue ? $reg->event->venue->name : 'Online' }}</span>
                    </div>

                    @if($reg->isAttended() && $reg->checkin)
                        <div class="rounded-lg bg-emerald-50/70 border border-emerald-200/60 px-3 py-2 text-xs font-medium text-emerald-800 flex items-center gap-2">
                            <x-icon name="check-circle" class="size-4 text-emerald-600" /> Check-in tercatat pada: <span class="font-mono font-semibold">{{ $reg->checkin->checked_in_at->format('d M Y H:i') }} WIB</span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row md:flex-col gap-2 min-w-[190px]">
                    <a href="{{ route('participant.tickets.show', $reg->id) }}"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
                        <x-icon name="tag" class="size-3.5" /> Buka E-Tiket & QR
                    </a>

                    <a href="{{ route('participant.tickets.download', $reg->id) }}"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-surface-soft px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                        <x-icon name="download" class="size-3.5" /> Unduh PDF Tiket
                    </a>

                    @if($reg->isAttended() || $reg->isCompleted())
                        <a href="{{ route('participant.certificates.index') }}"
                            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/60 hover:bg-emerald-100 px-4 py-2 text-xs font-semibold transition">
                            <x-icon name="book-open" class="size-3.5" /> Klaim Sertifikat
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-surface rounded-2xl border border-line/60 p-12 text-center">
                <x-icon name="tag" class="size-10 mx-auto text-slate-300 mb-2" />
                <h3 class="font-bold text-lg text-ink">Belum Ada Tiket Terdaftar</h3>
                <p class="text-xs text-muted mt-1">Anda belum memesan tiket untuk kategori ini.</p>
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 mt-4 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
                    Buka katalog event
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
