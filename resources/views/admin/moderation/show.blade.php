@extends('layouts.dashboard')

@section('title', 'Tinjau Event — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-[#FFE600] border border-black px-2 py-0.5 text-[10px] font-black uppercase">STATUS: {{ $event->status }}</span>
                <span class="bg-[#00F0FF] border border-black px-2 py-0.5 text-[10px] font-black uppercase">{{ $event->category->name }}</span>
            </div>
            <h1 class="text-3xl font-black">{{ $event->title }}</h1>
            <p class="text-xs font-bold text-zinc-600 mt-1">Penyelenggara: {{ $event->organization->name }} ({{ $event->organization->user->email }})</p>
        </div>

        <div class="flex items-center gap-2">
            @if($event->status !== 'PUBLISHED')
                <form action="{{ route('admin.moderation.approve', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-[#57EB74] hover:bg-[#43d960] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                        ✓ Setujui Event
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.moderation.index') }}" class="bg-white border-2 border-black px-3 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-white border-4 border-black p-6 sm:p-8 shadow-[6px_6px_0px_0px_#000] space-y-6">
        <div>
            <h3 class="font-black text-sm uppercase text-zinc-500 mb-2">Deskripsi Kegiatan:</h3>
            <div class="p-4 bg-zinc-50 border-2 border-black font-bold text-sm text-zinc-800 leading-relaxed whitespace-pre-line">
                {{ $event->description }}
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-bold">
            <div class="bg-[#FFFDF8] border-2 border-black p-3">
                <span class="text-[10px] uppercase text-zinc-500 font-black block">Waktu Mulai</span>
                <p class="font-black text-sm font-mono mt-0.5">{{ $event->start_time->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="bg-[#FFFDF8] border-2 border-black p-3">
                <span class="text-[10px] uppercase text-zinc-500 font-black block">Waktu Selesai</span>
                <p class="font-black text-sm font-mono mt-0.5">{{ $event->end_time->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="bg-[#FFFDF8] border-2 border-black p-3">
                <span class="text-[10px] uppercase text-zinc-500 font-black block">Venue / Lokasi</span>
                <p class="font-black text-sm mt-0.5">{{ $event->venue ? $event->venue->name : 'Daring (Online)' }}</p>
            </div>
        </div>

        <!-- Tiered Tickets -->
        <div>
            <h3 class="font-black text-sm uppercase text-zinc-500 mb-2">Konfigurasi Tipe Tiket:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($event->tickets as $ticket)
                    <div class="border-2 border-black p-4 bg-[#FFFDF8] space-y-1 text-xs font-bold">
                        <span class="font-black text-sm block">{{ $ticket->name }}</span>
                        <p class="font-mono text-base font-black">
                            {{ $ticket->price == 0 ? 'GRATIS' : 'Rp ' . number_format($ticket->price, 0, ',', '.') }}
                        </p>
                        <p class="text-zinc-500">Kuota: {{ $ticket->quota }} tiket</p>
                        <p class="text-[10px] text-zinc-500 font-mono">
                            Jual: {{ $ticket->start_sale->format('d/m') }} - {{ $ticket->end_sale->format('d/m/Y') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
