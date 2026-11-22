@extends('layouts.dashboard')

@section('title', 'E-Tiket: ' . $registration->registration_code)
@section('page_title', 'E-tiket')

@section('dashboard_content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('participant.tickets.index') }}" class="text-xs font-semibold text-slate-600 hover:text-ink flex items-center gap-1.5 transition">
            ← Kembali ke Daftar Tiket
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                <x-icon name="printer" class="size-3.5" /> Cetak
            </button>
            <a href="{{ route('participant.tickets.download', $registration->id) }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-4 py-1.5 text-xs font-semibold text-white hover:bg-brand-800 transition">
                <x-icon name="download" class="size-3.5" /> Unduh PDF
            </a>
        </div>
    </div>

    <!-- Official E-Ticket Card -->
    <div id="printable-ticket" class="bg-surface rounded-2xl border border-line/80 overflow-hidden shadow-sm">
        <!-- Header Strip -->
        <div class="bg-slate-900 text-white p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <img src="{{ asset('images/logo.webp') }}" alt="EventHub" class="h-8 w-auto object-contain">
                <div class="border-l border-slate-700 pl-3">
                    <h2 class="text-sm font-bold text-white">Tiket elektronik</h2>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="sm:text-right">
                @php
                    $statusBadge = match($registration->status) {
                        'CONFIRMED' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                        'ATTENDED' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                        'PENDING' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                        default => 'bg-slate-800 text-slate-300 border-slate-700',
                    };
                @endphp
                <span class="inline-block rounded-md border px-3 py-1 text-xs font-semibold {{ $statusBadge }}">
                    {{ $registration->status }}
                </span>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <!-- Event Title & Organization -->
            <div>
                <span class="inline-flex items-center rounded-md bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700 mb-2">
                    {{ $registration->event->category->name }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-ink leading-tight">
                    {{ $registration->event->title }}
                </h1>
                <p class="text-xs text-muted mt-1 font-medium">
                    Penyelenggara: <span class="text-ink font-semibold">{{ $registration->event->organization->name }}</span>
                </p>
            </div>

            <!-- Ticket Meta Details -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 rounded-xl bg-surface-soft/60 border border-line/60 p-4 text-xs">
                <div>
                    <span class="text-xs text-muted block">Nama Peserta</span>
                    <p class="font-bold text-ink text-sm mt-0.5">{{ $registration->user->name }}</p>
                </div>
                <div>
                    <span class="text-xs text-muted block">Tipe Tiket</span>
                    <p class="font-bold text-ink text-sm mt-0.5">{{ $registration->ticket->name }}</p>
                </div>
                <div>
                    <span class="text-xs text-muted block">Waktu Mulai</span>
                    <p class="font-semibold text-ink mt-0.5">{{ $registration->event->start_time->format('d M Y, H:i') }} WIB</p>
                </div>
                <div>
                    <span class="text-xs text-muted block">Lokasi</span>
                    <p class="font-semibold text-ink mt-0.5 truncate">{{ $registration->event->venue ? $registration->event->venue->name : 'Online' }}</p>
                </div>
            </div>

            <!-- Perforation Cut Line -->
            <div class="relative py-2">
                <div class="border-t border-dashed border-line"></div>
            </div>

            <!-- Dynamic QR Code & Check-in instructions -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-2">
                <div class="space-y-3 text-center sm:text-left">
                    <span class="text-xs font-semibold text-muted uppercase tracking-wider block">Kode Registrasi Resmi</span>
                    <div class="font-mono font-extrabold text-2xl tracking-wider text-ink bg-surface-soft px-4 py-2 rounded-xl border border-line/60 inline-block">
                        {{ $registration->registration_code }}
                    </div>
                    <p class="text-xs text-muted max-w-sm">
                        Tunjukkan QR code ini ke panitia di meja registrasi pintu masuk acara untuk dipindai saat kedatangan.
                    </p>
                    @if($registration->isAttended() && $registration->checkin)
                        <div class="rounded-lg bg-emerald-50 border border-emerald-200/60 px-3 py-2 text-xs font-medium text-emerald-800 inline-flex items-center gap-1.5 mt-2">
                            <x-icon name="check-circle" class="size-4 text-emerald-600" /> Terverifikasi Hadir: {{ $registration->checkin->checked_in_at->format('d M Y H:i:s') }} WIB
                        </div>
                    @endif
                </div>

                <!-- SVG QR Code Frame -->
                <div class="p-4 bg-white rounded-2xl border border-line/60 shadow-sm flex flex-col items-center">
                    <div class="w-44 h-44 flex items-center justify-center">
                        {!! $qrCodeSvg !!}
                    </div>
                    <span class="text-[11px] font-mono font-medium text-muted mt-2">Scan for Check-in</span>
                </div>
            </div>
        </div>

        <div class="bg-surface-soft border-t border-line px-6 py-3 text-center text-xs text-muted">
            Tunjukkan kode QR kepada panitia di lokasi acara untuk presensi kehadiran.
        </div>
    </div>
</div>
@endsection
