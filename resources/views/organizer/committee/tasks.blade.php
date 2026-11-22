@extends('layouts.dashboard')

@section('title', 'Tugas Panitia Event')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6">
        <h1 class="text-xl sm:text-2xl font-bold text-ink">Tugas panitia</h1>
    </div>

    <!-- Assigned Events List -->
    <div class="space-y-4">
        @forelse($memberships as $membership)
            @php
                $event = $membership->event;
                $roleBadge = match($membership->committee_role) {
                    'Registration' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                    'Finance' => 'bg-amber-50 text-amber-800 border-amber-200/60',
                    default => 'bg-blue-50 text-blue-700 border-blue-200/60',
                };
            @endphp
            <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-4 transition hover:border-slate-300">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-line/60">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold {{ $roleBadge }}">
                            Divisi {{ $membership->committee_role }}
                        </span>
                        <span class="inline-flex items-center rounded-md bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">
                            {{ $event->category->name }}
                        </span>
                    </div>
                    <span class="text-xs text-muted">
                        Ditugaskan: {{ $membership->created_at->format('d M Y') }}
                    </span>
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <h2 class="text-xl font-bold text-ink leading-snug">
                            <a href="{{ route('events.show', $event->slug) }}" class="hover:text-brand-700 transition">
                                {{ $event->title }}
                            </a>
                        </h2>
                        <p class="text-xs text-muted font-medium">
                            Penyelenggara: <span class="text-ink font-semibold">{{ $event->organization->name }}</span>
                        </p>

                        <div class="flex flex-wrap gap-4 text-xs font-medium text-muted pt-1">
                            <span class="inline-flex items-center gap-1.5"><x-icon name="calendar" class="size-4 text-slate-400" /> {{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                            <span class="inline-flex items-center gap-1.5"><x-icon name="map-pin" class="size-4 text-slate-400" /> {{ $event->venue ? $event->venue->name : 'Online' }}</span>
                        </div>
                    </div>

                    <!-- Operational Action Buttons -->
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                        @if($membership->committee_role === 'Registration' || Auth::user()->isAdmin())
                            <a href="{{ route('organizer.checkin.scanner', $event->id) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition whitespace-nowrap">
                                <x-icon name="camera" class="size-3.5" /> Buka Scanner QR
                            </a>
                        @endif

                        <a href="{{ route('organizer.participants.index', $event->id) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">
                            <x-icon name="users" class="size-3.5" />
                            @if($membership->committee_role === 'Finance')
                                Verifikasi Pembayaran
                            @else
                                Data Peserta
                            @endif
                        </a>

                        @if($membership->committee_role === 'Finance' || Auth::user()->isAdmin())
                            <a href="{{ route('organizer.participants.export', $event->id) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition whitespace-nowrap">
                                <x-icon name="download" class="size-3.5" /> Ekspor CSV
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-surface rounded-2xl border border-line/60 p-12 text-center">
                <x-icon name="clipboard" class="size-10 mx-auto text-slate-300 mb-2" />
                <h3 class="font-bold text-lg text-ink">Belum ada tugas panitia</h3>
                <p class="text-xs text-muted mt-1 max-w-md mx-auto">
                    Anda belum ditugaskan sebagai panitia di event mana pun.
                </p>
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 mt-4 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
                    Buka katalog event
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
