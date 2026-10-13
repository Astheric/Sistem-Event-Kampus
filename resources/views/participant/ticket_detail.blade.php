@extends('layouts.app')

@section('title', 'E-Tiket: ' . $registration->registration_code . ' — EventHub')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('participant.tickets.index') }}" class="font-black text-xs hover:underline flex items-center gap-1">
            ← Kembali ke Daftar Tiket
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-white border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                🖨️ Cetak
            </button>
            <a href="{{ route('participant.tickets.download', $registration->id) }}"
                class="bg-[#FFE600] border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                📥 Unduh PDF
            </a>
        </div>
    </div>

    <!-- Official Neo-Brutalist E-Ticket Card -->
    <div id="printable-ticket" class="bg-white border-4 border-black shadow-[10px_10px_0px_0px_#000] overflow-hidden">
        <!-- Header Strip -->
        <div class="bg-[#FFE600] border-b-4 border-black p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white border-3 border-black flex items-center justify-center font-black text-xl shadow-[2px_2px_0px_0px_#000]">
                    EH
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-zinc-800">OFFICIAL CAMPUS E-TICKET</span>
                    <h2 class="text-xl font-black">EventHub Pass</h2>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="text-right">
                <span class="text-[10px] font-black uppercase tracking-widest block text-zinc-700">STATUS TIKET:</span>
                <span class="inline-block border-2 border-black px-3 py-1 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000] {{ $registration->isAttended() ? 'bg-[#57EB74]' : ($registration->isConfirmed() ? 'bg-[#00F0FF]' : 'bg-[#FFE600]') }}">
                    {{ $registration->status }}
                </span>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <!-- Event Title & Organization -->
            <div>
                <span class="bg-black text-white px-2 py-0.5 text-[10px] font-mono font-black uppercase">
                    {{ $registration->event->category->name }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-black mt-2 leading-tight">
                    {{ $registration->event->title }}
                </h1>
                <p class="text-xs font-bold text-zinc-600 mt-1">
                    Penyelenggara: {{ $registration->event->organization->name }}
                </p>
            </div>

            <!-- Ticket Meta Details -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-zinc-50 border-3 border-black p-4 text-xs font-bold">
                <div>
                    <span class="text-[10px] text-zinc-500 uppercase font-black block">Nama Peserta</span>
                    <p class="font-black text-sm">{{ $registration->user->name }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-zinc-500 uppercase font-black block">Tipe Tiket</span>
                    <p class="font-black text-sm">{{ $registration->ticket->name }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-zinc-500 uppercase font-black block">Waktu Mulai</span>
                    <p class="font-mono font-black">{{ $registration->event->start_time->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-zinc-500 uppercase font-black block">Lokasi</span>
                    <p class="truncate">{{ $registration->event->venue ? $registration->event->venue->name : 'Online' }}</p>
                </div>
            </div>

            <!-- Perforation Cut Line -->
            <div class="relative py-2">
                <div class="border-t-3 border-dashed border-black"></div>
                <div class="absolute -left-10 top-0.5 w-8 h-8 rounded-full bg-[#FFFDF8] border-r-3 border-black"></div>
                <div class="absolute -right-10 top-0.5 w-8 h-8 rounded-full bg-[#FFFDF8] border-l-3 border-black"></div>
            </div>

            <!-- Dynamic QR Code & Check-in instructions -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-2">
                <div class="space-y-2 text-center sm:text-left">
                    <span class="text-[10px] text-zinc-500 uppercase font-black tracking-widest">KODE REGISTRASI RESMI</span>
                    <div class="font-mono font-black text-2xl tracking-wider bg-[#FFE600] border-2 border-black px-4 py-2 shadow-[3px_3px_0px_0px_#000] inline-block">
                        {{ $registration->registration_code }}
                    </div>
                    <p class="text-xs font-bold text-zinc-600 max-w-sm pt-2">
                        Tunjukkan QR code ini ke panitia di pintu masuk event untuk dipindai saat kedatangan.
                    </p>
                    @if($registration->isAttended() && $registration->checkin)
                        <div class="bg-[#57EB74] border-2 border-black p-2 text-xs font-black inline-block mt-2">
                            ✓ Terverifikasi Hadir: {{ $registration->checkin->checked_in_at->format('d M Y H:i:s') }}
                        </div>
                    @endif
                </div>

                <!-- SVG QR Code Frame -->
                <div class="p-3 bg-white border-4 border-black shadow-[6px_6px_0px_0px_#000] flex flex-col items-center">
                    <div class="w-48 h-48 flex items-center justify-center">
                        {!! $qrCodeSvg !!}
                    </div>
                    <span class="text-[10px] font-mono font-black text-zinc-500 mt-2">SCAN FOR CHECK-IN</span>
                </div>
            </div>
        </div>

        <!-- Ticket Footer Bar -->
        <div class="bg-black text-white p-3 text-center text-xs font-mono font-bold tracking-wider">
            EVENTHUB KAMPUS • VERIFIED TICKETING SYSTEM • BR-03 PROTECTED
        </div>
    </div>
</div>
@endsection
