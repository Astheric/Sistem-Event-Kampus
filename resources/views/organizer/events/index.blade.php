@extends('layouts.dashboard')

@section('title', 'Event Saya')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-ink">Event saya</h1>
        </div>
        <a href="{{ route('organizer.events.create') }}"
            class="btn btn-primary text-xs py-2 px-3.5 whitespace-nowrap self-start sm:self-auto">
            + Buat event baru
        </a>
    </div>

    <!-- Events List Cards -->
    <div class="space-y-4">
        @forelse($events as $event)
            @php
                $statusBadge = match($event->status) {
                    'PUBLISHED' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                    'SUBMITTED' => 'bg-amber-50 text-amber-800 border-amber-200/60',
                    'REJECTED' => 'bg-red-50 text-red-700 border-red-200/60',
                    'COMPLETED' => 'bg-slate-100 text-slate-700 border-slate-200',
                    'CANCELLED' => 'bg-red-50 text-red-700 border-red-200/60',
                    default => 'bg-slate-100 text-slate-600 border-slate-200',
                };
            @endphp

            <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-line/60">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold {{ $statusBadge }}">
                            {{ $event->status }}
                        </span>
                        <span class="inline-flex items-center rounded-md bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">
                            {{ $event->category->name }}
                        </span>
                    </div>
                    <span class="text-xs text-muted">
                        Dibuat: {{ $event->created_at->format('d M Y') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <h2 class="text-xl font-bold text-ink leading-snug">
                            <a href="{{ route('events.show', $event->slug) }}" class="hover:text-brand-700 transition">
                                {{ $event->title }}
                            </a>
                        </h2>
                        <div class="flex flex-wrap gap-4 text-xs font-medium text-muted pt-1">
                            <span class="inline-flex items-center gap-1.5"><x-icon name="calendar" class="size-4 text-slate-400" /> {{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                            <span class="inline-flex items-center gap-1.5"><x-icon name="map-pin" class="size-4 text-slate-400" /> {{ $event->venue ? $event->venue->name : 'Online' }}</span>
                            <span class="inline-flex items-center gap-1.5"><x-icon name="users" class="size-4 text-slate-400" /> Kapasitas: {{ $event->capacity }}</span>
                        </div>

                        @if($event->status === 'REJECTED' && $event->rejection_reason)
                            <div class="rounded-xl bg-red-50/70 border border-red-200/60 p-3.5 text-xs text-red-800 mt-2">
                                <span class="font-bold block mb-0.5">Catatan Revisi dari Admin:</span>
                                {{ $event->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <div class="rounded-xl bg-surface-soft/80 border border-line/60 p-4 text-xs space-y-2 flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Status Peserta</span>
                            <div class="text-2xl font-extrabold text-ink mt-1">{{ number_format($event->registrations_count) }}</div>
                            <span class="text-xs text-muted">Tiket terdaftar</span>
                        </div>
                        <div class="pt-2 border-t border-line/60 text-xs text-muted font-medium">
                            {{ $event->tickets->count() }} Tipe Tiket Dikonfigurasi
                        </div>
                    </div>
                </div>

                <!-- Action Toolbar Buttons -->
                <div class="pt-3 border-t border-line/60 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('organizer.events.tickets', $event->id) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                            <x-icon name="tag" class="size-3.5" /> Atur Tiket ({{ $event->tickets->count() }})
                        </a>

                        <a href="{{ route('organizer.participants.index', $event->id) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                            <x-icon name="users" class="size-3.5" /> Peserta ({{ $event->registrations_count }})
                        </a>

                        <a href="{{ route('organizer.checkin.scanner', $event->id) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                            <x-icon name="camera" class="size-3.5" /> Scanner QR
                        </a>

                        <a href="{{ route('organizer.certificates.index', $event->id) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                            <x-icon name="book-open" class="size-3.5" /> Sertifikat
                        </a>

                        <a href="{{ route('organizer.committee.index', $event->id) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                            <x-icon name="shield" class="size-3.5" /> Panitia
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @if($event->status === 'DRAFT' || $event->status === 'REJECTED')
                            <form action="{{ route('organizer.events.submit', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-brand-800 transition">
                                    <x-icon name="send" class="size-3.5" /> Ajukan
                                </button>
                            </form>
                        @elseif($event->status === 'PUBLISHED')
                            <form action="{{ route('organizer.events.status', $event->id) }}" method="POST" onsubmit="return confirm('Selesaikan event ini? Status event akan menjadi COMPLETED.')">
                                @csrf
                                <input type="hidden" name="status" value="COMPLETED">
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-slate-700 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-slate-800 transition">
                                    <x-icon name="flag" class="size-3.5" /> Selesaikan Event
                                </button>
                            </form>
                        @endif

                        @if(in_array($event->status, ['DRAFT', 'SUBMITTED', 'REJECTED', 'PUBLISHED']))
                            <form action="{{ route('organizer.events.status', $event->id) }}" method="POST" onsubmit="return confirm('Batalkan event ini? Aksi ini akan mengubah status menjadi CANCELLED.')">
                                @csrf
                                <input type="hidden" name="status" value="CANCELLED">
                                <button type="submit"
                                    class="inline-flex items-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 text-xs font-semibold transition">
                                    Batalkan
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('organizer.events.edit', $event->id) }}"
                            class="inline-flex items-center gap-1 rounded-lg bg-surface-soft px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                            <x-icon name="edit-2" class="size-3.5" /> Edit
                        </a>

                        <form action="{{ route('organizer.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus event ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 p-1.5 transition">
                                <x-icon name="trash-2" class="size-4" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-surface rounded-2xl border border-line/60 p-12 text-center">
                <x-icon name="calendar" class="size-10 mx-auto text-slate-300 mb-2" />
                <h3 class="font-bold text-lg text-ink">Belum Ada Event Dibuat</h3>
                <p class="text-xs text-muted mt-1">Buat agenda kegiatan kampus pertama Anda.</p>
                <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center gap-1.5 mt-4 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
                    + Buat event
                </a>
            </div>
        @endforelse
    </div>

    <div>
        {{ $events->links() }}
    </div>
</div>
@endsection
