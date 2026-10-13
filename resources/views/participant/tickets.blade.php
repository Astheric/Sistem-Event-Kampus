@extends('layouts.dashboard')

@section('title', 'Tiket Saya — EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black">Tiket Terdaftar Saya</h1>
            <p class="text-xs font-bold text-zinc-600 mt-1">Daftar seluruh e-tiket event kampus yang telah Anda pesan.</p>
        </div>
        <a href="{{ route('events.index') }}"
            class="bg-[#FFE600] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap">
            + Cari Event Lain
        </a>
    </div>

    <!-- Status Tabs -->
    <div class="flex flex-wrap gap-2 text-xs font-black">
        <a href="{{ route('participant.tickets.index') }}"
            class="px-3 py-1.5 border-2 border-black {{ !request('status') ? 'bg-[#FFE600] shadow-[2px_2px_0px_0px_#000]' : 'bg-white hover:bg-zinc-100' }}">
            Semua (All)
        </a>
        <a href="{{ route('participant.tickets.index', ['status' => 'confirmed']) }}"
            class="px-3 py-1.5 border-2 border-black {{ request('status') === 'confirmed' ? 'bg-[#57EB74] shadow-[2px_2px_0px_0px_#000]' : 'bg-white hover:bg-zinc-100' }}">
            Aktif (CONFIRMED)
        </a>
        <a href="{{ route('participant.tickets.index', ['status' => 'pending']) }}"
            class="px-3 py-1.5 border-2 border-black {{ request('status') === 'pending' ? 'bg-[#FFE600] shadow-[2px_2px_0px_0px_#000]' : 'bg-white hover:bg-zinc-100' }}">
            Menunggu Verifikasi (PENDING)
        </a>
        <a href="{{ route('participant.tickets.index', ['status' => 'attended']) }}"
            class="px-3 py-1.5 border-2 border-black {{ request('status') === 'attended' ? 'bg-[#00F0FF] shadow-[2px_2px_0px_0px_#000]' : 'bg-white hover:bg-zinc-100' }}">
            Telah Hadir (ATTENDED)
        </a>
    </div>

    <!-- Ticket Cards List -->
    <div class="space-y-4">
        @forelse($registrations as $reg)
            @php
                $statusColors = [
                    'CONFIRMED' => 'bg-[#57EB74]',
                    'PENDING' => 'bg-[#FFE600]',
                    'ATTENDED' => 'bg-[#00F0FF]',
                    'CANCELLED' => 'bg-[#FF70A6]',
                    'COMPLETED' => 'bg-black text-white',
                ];
            @endphp

            <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase {{ $statusColors[$reg->status] ?? 'bg-zinc-100' }}">
                            {{ $reg->status }}
                        </span>
                        <span class="font-mono text-xs font-black bg-[#FFFDF8] border border-black px-2 py-0.5">
                            {{ $reg->registration_code }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-black">
                            <a href="{{ route('events.show', $reg->event->slug) }}" class="hover:underline">
                                {{ $reg->event->title }}
                            </a>
                        </h3>
                        <p class="text-xs font-bold text-zinc-500 mt-0.5">
                            Tipe Tiket: <span class="font-black text-black">{{ $reg->ticket->name }}</span>
                            @if($reg->ticket->price > 0)
                                • Rp {{ number_format($reg->ticket->price, 0, ',', '.') }}
                            @else
                                • <span class="text-[#2e9c45]">Gratis</span>
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-4 text-xs font-bold text-zinc-600">
                        <span>📅 {{ $reg->event->start_time->format('d M Y, H:i') }} WIB</span>
                        <span>📍 {{ $reg->event->venue ? $reg->event->venue->name : 'Online' }}</span>
                    </div>

                    @if($reg->isAttended() && $reg->checkin)
                        <div class="bg-[#e6fffa] border border-black p-2 text-xs font-bold">
                            ✅ Check-in tercatat pada: <span class="font-mono">{{ $reg->checkin->checked_in_at->format('d M Y H:i') }} WIB</span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row md:flex-col gap-2 min-w-[180px]">
                    <a href="{{ route('participant.tickets.show', $reg->id) }}"
                        class="text-center bg-[#FFE600] hover:bg-[#ffd700] border-2 border-black py-2 px-3 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                        🎟️ Buka E-Tiket & QR
                    </a>

                    <a href="{{ route('participant.tickets.download', $reg->id) }}"
                        class="text-center bg-white hover:bg-zinc-100 border-2 border-black py-2 px-3 font-bold text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                        📥 Unduh PDF Tiket
                    </a>

                    @if($reg->isAttended() || $reg->isCompleted())
                        <a href="{{ route('participant.certificates.index') }}"
                            class="text-center bg-[#57EB74] hover:bg-[#43d960] border-2 border-black py-2 px-3 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                            🎓 Klaim Sertifikat
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border-3 border-black p-12 text-center shadow-[4px_4px_0px_0px_#000]">
                <span class="text-4xl block mb-2">🎟️</span>
                <h3 class="font-black text-lg">Belum Ada Tiket Terdaftar</h3>
                <p class="text-xs font-bold text-zinc-500 mt-1">Anda belum memesan tiket untuk kategori ini.</p>
                <a href="{{ route('events.index') }}" class="inline-block mt-4 bg-[#FFE600] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
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
