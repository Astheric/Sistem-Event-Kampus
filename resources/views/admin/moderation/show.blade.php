@extends('layouts.dashboard')

@section('title', 'Tinjau Event — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-surface border border-line px-2 py-0.5 text-[10px] font-bold ">STATUS: {{ $event->status }}</span>
                <span class="bg-surface border border-line px-2 py-0.5 text-[10px] font-bold ">{{ $event->category->name }}</span>
            </div>
            <h1 class="text-3xl font-bold">{{ $event->title }}</h1>
            <p class="text-xs font-bold text-muted mt-1">Penyelenggara: {{ $event->organization->name }} ({{ $event->organization->user->email }})</p>
        </div>

        <div class="flex items-center gap-2">
            @if($event->status !== 'PUBLISHED')
                <form action="{{ route('admin.moderation.approve', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-brand-600 text-white hover:bg-brand-700 border border-line px-4 py-2 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                        <x-icon name="check" class="size-4" /> Setujui Event
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.moderation.index') }}" class="bg-surface border border-line px-3 py-2 font-bold text-xs  shadow-card rounded-2xl">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-surface border border-line p-6 sm:p-8 shadow-card space-y-6 rounded-2xl">
        <div>
            <h3 class="font-bold text-sm  text-muted mb-2">Deskripsi Kegiatan:</h3>
            <div class="p-4 bg-surface-soft border border-line font-bold text-sm text-muted leading-relaxed whitespace-pre-line">
                {{ $event->description }}
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-bold">
            <div class="bg-surface border border-line p-3">
                <span class="text-[10px]  text-muted font-bold block">Waktu Mulai</span>
                <p class="font-bold text-sm font-mono mt-0.5">{{ $event->start_time->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="bg-surface border border-line p-3">
                <span class="text-[10px]  text-muted font-bold block">Waktu Selesai</span>
                <p class="font-bold text-sm font-mono mt-0.5">{{ $event->end_time->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="bg-surface border border-line p-3">
                <span class="text-[10px]  text-muted font-bold block">Venue / Lokasi</span>
                <p class="font-bold text-sm mt-0.5">{{ $event->venue ? $event->venue->name : 'Daring (Online)' }}</p>
            </div>
        </div>

        <!-- Tiered Tickets -->
        <div>
            <h3 class="font-bold text-sm  text-muted mb-2">Konfigurasi Tipe Tiket:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($event->tickets as $ticket)
                    <div class="border border-line p-4 bg-surface space-y-1 text-xs font-bold">
                        <span class="font-bold text-sm block">{{ $ticket->name }}</span>
                        <p class="font-mono text-base font-bold">
                            {{ $ticket->price == 0 ? 'GRATIS' : 'Rp ' . number_format($ticket->price, 0, ',', '.') }}
                        </p>
                        <p class="text-muted">Kuota: {{ $ticket->quota }} tiket</p>
                        <p class="text-[10px] text-muted font-mono">
                            Jual: {{ $ticket->start_sale->format('d/m') }} - {{ $ticket->end_sale->format('d/m/Y') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
