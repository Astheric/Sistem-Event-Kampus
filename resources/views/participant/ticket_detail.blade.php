@extends('layouts.app')

@section('title', 'E-Tiket: ' . $registration->registration_code . ' — EventHub')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('participant.tickets.index') }}" class="font-bold text-xs hover:underline flex items-center gap-1">
            ← Kembali ke Daftar Tiket
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-brand-600 text-white border border-line px-3 py-1.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                <x-icon name="printer" class="size-4" /> Cetak
            </button>
            <a href="{{ route('participant.tickets.download', $registration->id) }}"
                class="bg-brand-600 text-white border border-line px-3 py-1.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                <x-icon name="download" class="size-4" /> Unduh PDF
            </a>
        </div>
    </div>

    <!-- Official Neo-Brutalist E-Ticket Card -->
    <div id="printable-ticket" class="bg-surface border border-line shadow-card overflow-hidden rounded-2xl">
        <!-- Header Strip -->
        <div class="bg-surface border-b border-line p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-surface border border-line flex items-center justify-center font-bold text-xl shadow-card rounded-2xl">
                    EH
                </div>
                <div>
                    <span class="text-[10px] font-bold  tracking-widest text-muted">OFFICIAL CAMPUS E-TICKET</span>
                    <h2 class="text-xl font-bold">EventHub Pass</h2>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="text-right">
                <span class="text-[10px] font-bold  tracking-widest block text-muted">STATUS TIKET:</span>
                <span class="inline-block border border-line px-3 py-1 text-xs font-bold  shadow-card {{ $registration->isAttended() ? 'bg-surface' : ($registration->isConfirmed() ? 'bg-surface' : 'bg-surface') }} rounded-2xl">
                    {{ $registration->status }}
                </span>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <!-- Event Title & Organization -->
            <div>
                <span class="bg-danger text-white px-2 py-0.5 text-[10px] font-mono font-bold ">
                    {{ $registration->event->category->name }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold mt-2 leading-tight">
                    {{ $registration->event->title }}
                </h1>
                <p class="text-xs font-bold text-muted mt-1">
                    Penyelenggara: {{ $registration->event->organization->name }}
                </p>
            </div>

            <!-- Ticket Meta Details -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-surface-soft border border-line p-4 text-xs font-bold">
                <div>
                    <span class="text-[10px] text-muted  font-bold block">Nama Peserta</span>
                    <p class="font-bold text-sm">{{ $registration->user->name }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-muted  font-bold block">Tipe Tiket</span>
                    <p class="font-bold text-sm">{{ $registration->ticket->name }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-muted  font-bold block">Waktu Mulai</span>
                    <p class="font-mono font-bold">{{ $registration->event->start_time->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-muted  font-bold block">Lokasi</span>
                    <p class="truncate">{{ $registration->event->venue ? $registration->event->venue->name : 'Online' }}</p>
                </div>
            </div>

            <!-- Perforation Cut Line -->
            <div class="relative py-2">
                <div class="border-t border-dashed border-line"></div>
                <div class="absolute -left-10 top-0.5 w-8 h-8 rounded-full bg-surface border-r border-line"></div>
                <div class="absolute -right-10 top-0.5 w-8 h-8 rounded-full bg-surface border-l border-line"></div>
            </div>

            <!-- Dynamic QR Code & Check-in instructions -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-2">
                <div class="space-y-2 text-center sm:text-left">
                    <span class="text-[10px] text-muted  font-bold tracking-widest">KODE REGISTRASI RESMI</span>
                    <div class="font-mono font-bold text-2xl tracking-wider bg-surface border border-line px-4 py-2 shadow-card inline-block rounded-2xl">
                        {{ $registration->registration_code }}
                    </div>
                    <p class="text-xs font-bold text-muted max-w-sm pt-2">
                        Tunjukkan QR code ini ke panitia di pintu masuk event untuk dipindai saat kedatangan.
                    </p>
                    @if($registration->isAttended() && $registration->checkin)
                        <div class="bg-surface border border-line p-2 text-xs font-bold inline-block mt-2">
                            <x-icon name="check" class="size-4" /> Terverifikasi Hadir: {{ $registration->checkin->checked_in_at->format('d M Y H:i:s') }}
                        </div>
                    @endif
                </div>

                <!-- SVG QR Code Frame -->
                <div class="p-3 bg-surface border border-line shadow-card flex flex-col items-center rounded-2xl">
                    <div class="w-48 h-48 flex items-center justify-center">
                        {!! $qrCodeSvg !!}
                    </div>
                    <span class="text-[10px] font-mono font-bold text-muted mt-2">SCAN FOR CHECK-IN</span>
                </div>
            </div>
        </div>

        <!-- Ticket Footer Bar -->
        <div class="bg-danger text-white p-3 text-center text-xs font-mono font-bold tracking-wider">
            EVENTHUB KAMPUS • VERIFIED TICKETING SYSTEM • BR-03 PROTECTED
        </div>
    </div>
</div>
@endsection
