@extends('layouts.dashboard')

@section('title', 'Tinjau Event: ' . $event->title)
@section('page_title', 'Tinjau event')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface rounded-2xl border border-line/60 p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                @php
                    $statusBadge = match($event->status) {
                        'PUBLISHED' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                        'SUBMITTED' => 'bg-amber-50 text-amber-800 border-amber-200/60',
                        'REJECTED' => 'bg-red-50 text-red-700 border-red-200/60',
                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                    };
                @endphp
                <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold {{ $statusBadge }}">
                    {{ $event->status }}
                </span>
                <span class="inline-flex items-center rounded-md bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">
                    {{ $event->category->name }}
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink">{{ $event->title }}</h1>
            <p class="text-xs text-muted mt-1">Penyelenggara: <span class="font-medium text-ink">{{ $event->organization->name }}</span> ({{ $event->organization->user->email }})</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.moderation.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                ← Kembali
            </a>

            @if($event->status !== 'PUBLISHED')
                <form action="{{ route('admin.moderation.approve', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                        <x-icon name="check" class="size-4" /> Setujui & Publikasikan
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-surface rounded-2xl border border-line/60 p-6 sm:p-8 space-y-6">
        <div>
            <h3 class="text-xs font-semibold uppercase tracking-wider text-muted mb-2">Deskripsi Kegiatan</h3>
            <div class="p-5 bg-surface-soft/60 rounded-xl border border-line/60 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                {{ $event->description }}
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-xl border border-line/60 bg-surface-soft/40 p-4">
                <span class="text-xs font-medium text-muted block">Waktu Mulai</span>
                <p class="font-bold text-sm text-ink mt-1">{{ $event->start_time->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="rounded-xl border border-line/60 bg-surface-soft/40 p-4">
                <span class="text-xs font-medium text-muted block">Waktu Selesai</span>
                <p class="font-bold text-sm text-ink mt-1">{{ $event->end_time->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="rounded-xl border border-line/60 bg-surface-soft/40 p-4">
                <span class="text-xs font-medium text-muted block">Venue / Lokasi</span>
                <p class="font-bold text-sm text-ink mt-1">{{ $event->venue ? $event->venue->name : 'Daring (Online)' }}</p>
            </div>
        </div>

        <!-- Tiered Tickets -->
        <div>
            <h3 class="text-xs font-semibold uppercase tracking-wider text-muted mb-3">Konfigurasi Tipe Tiket</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($event->tickets as $ticket)
                    <div class="rounded-xl border border-line/60 bg-surface-soft/30 p-4 space-y-1.5">
                        <span class="font-bold text-sm text-ink block">{{ $ticket->name }}</span>
                        <p class="text-base font-extrabold text-ink">
                            {{ $ticket->price == 0 ? 'GRATIS' : 'Rp ' . number_format($ticket->price, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-muted">Kuota: <span class="font-semibold text-slate-700">{{ $ticket->quota }} tiket</span></p>
                        <p class="text-xs text-muted">
                            Periode: {{ $ticket->start_sale->format('d M') }} – {{ $ticket->end_sale->format('d M Y') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
