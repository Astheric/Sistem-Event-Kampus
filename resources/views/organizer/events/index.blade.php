@extends('layouts.dashboard')

@section('title', 'Event Saya — Organizer EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl">
        <div>
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                Koleksi Event
            </span>
            <h1 class="text-3xl font-bold mt-2">Daftar Event Saya</h1>
            <p class="text-xs font-bold text-muted mt-1">Kelola publikasi, tipe tiket, data kehadiran, dan panitia event.</p>
        </div>
        <a href="{{ route('organizer.events.create') }}"
            class="bg-brand-600 text-white hover:bg-brand-600 text-white border border-line px-5 py-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap rounded-full">
            + Tambah Event Baru
        </a>
    </div>

    <!-- Events List Cards -->
    <div class="space-y-4">
        @forelse($events as $event)
            @php
                $statusColors = [
                    'DRAFT' => 'bg-surface-soft',
                    'SUBMITTED' => 'bg-surface',
                    'UNDER REVIEW' => 'bg-surface text-white',
                    'APPROVED' => 'bg-surface',
                    'PUBLISHED' => 'bg-surface',
                    'REJECTED' => 'bg-surface',
                    'COMPLETED' => 'bg-danger text-white',
                ];
            @endphp

            <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-line pb-3">
                    <div class="flex items-center gap-2">
                        <span class="border border-line px-2.5 py-0.5 text-xs font-bold  {{ $statusColors[$event->status] ?? 'bg-surface-soft' }}">
                            {{ $event->status }}
                        </span>
                        <span class="bg-surface border border-line px-2 py-0.5 text-[11px] font-bold ">
                            {{ $event->category->name }}
                        </span>
                    </div>
                    <span class="text-xs font-bold text-muted font-mono">
                        Dibuat: {{ $event->created_at->format('d M Y') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <h2 class="text-xl font-bold leading-snug">
                            <a href="{{ route('events.show', $event->slug) }}" class="hover:underline">
                                {{ $event->title }}
                            </a>
                        </h2>
                        <div class="flex flex-wrap gap-4 text-xs font-bold text-muted">
                            <span><x-icon name="calendar" class="size-4" /> {{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                            <span><x-icon name="map-pin" class="size-4" /> {{ $event->venue ? $event->venue->name : 'Online' }}</span>
                            <span><x-icon name="users" class="size-4" /> Kapasitas: {{ $event->capacity }}</span>
                        </div>

                        @if($event->status === 'REJECTED' && $event->rejection_reason)
                            <div class="bg-surface border border-line p-3 text-xs font-bold mt-2">
                                <span class="font-bold  block">Catatan Revisi dari Admin:</span>
                                {{ $event->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <div class="bg-surface-soft border border-line p-3 text-xs font-bold space-y-2 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] text-muted  font-bold block">Status Peserta</span>
                            <div class="text-2xl font-bold font-mono mt-1">{{ $event->registrations_count }}</div>
                            <span class="text-muted">Tiket terdaftar</span>
                        </div>
                        <div class="pt-2 border-t border-line text-[11px]">
                            {{ $event->tickets->count() }} Tipe Tiket Dikonfigurasi
                        </div>
                    </div>
                </div>

                <!-- Action Toolbar Buttons -->
                <div class="pt-3 border-t border-dashed border-line flex flex-wrap items-center justify-between gap-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('organizer.events.tickets', $event->id) }}"
                            class="bg-brand-600 text-white border border-line px-3 py-1.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                            <x-icon name="tag" class="size-4" /> Atur Tiket ({{ $event->tickets->count() }})
                        </a>

                        <a href="{{ route('organizer.participants.index', $event->id) }}"
                            class="bg-brand-600 text-white border border-line px-3 py-1.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                            <x-icon name="users" class="size-4" /> Peserta ({{ $event->registrations_count }})
                        </a>

                        <a href="{{ route('organizer.checkin.scanner', $event->id) }}"
                            class="bg-brand-600 text-white border border-line px-3 py-1.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                            <x-icon name="camera" class="size-4" /> Scanner QR
                        </a>

                        <a href="{{ route('organizer.certificates.index', $event->id) }}"
                            class="bg-brand-600 text-white border border-line px-3 py-1.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                            <x-icon name="book-open" class="size-4" /> Sertifikat
                        </a>

                        <a href="{{ route('organizer.committee.index', $event->id) }}"
                            class="bg-brand-600 text-white border border-line px-3 py-1.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                            <x-icon name="shield" class="size-4" /> Panitia
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @if($event->status === 'DRAFT' || $event->status === 'REJECTED')
                            <form action="{{ route('organizer.events.submit', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-brand-600 text-white bg-danger text-white hover:opacity-90 border border-line px-3 py-1.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                                    <x-icon name="send" class="size-4" /> Ajukan
                                </button>
                            </form>
                        @elseif($event->status === 'APPROVED')
                            <form action="{{ route('organizer.events.status', $event->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="PUBLISHED">
                                <button type="submit"
                                    class="bg-brand-600 text-white text-black hover:bg-green-400 border border-line px-3 py-1.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                                    <x-icon name="bell" class="size-4" /> Publikasikan
                                </button>
                            </form>
                        @elseif($event->status === 'PUBLISHED')
                            <form action="{{ route('organizer.events.status', $event->id) }}" method="POST" onsubmit="return confirm('Selesaikan event ini? Status event akan menjadi COMPLETED.')">
                                @csrf
                                <input type="hidden" name="status" value="COMPLETED">
                                <button type="submit"
                                    class="bg-brand-600 text-white hover:bg-brand-700 border border-line px-3 py-1.5 font-bold text-xs shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                                    <x-icon name="flag" class="size-4" /> Selesaikan Event
                                </button>
                            </form>
                        @endif

                        @if(in_array($event->status, ['DRAFT', 'SUBMITTED', 'APPROVED', 'PUBLISHED']))
                            <form action="{{ route('organizer.events.status', $event->id) }}" method="POST" onsubmit="return confirm('Batalkan event ini? Aksi ini akan mengubah status menjadi CANCELLED.')">
                                @csrf
                                <input type="hidden" name="status" value="CANCELLED">
                                <button type="submit"
                                    class="bg-brand-600 text-white text-red-600 hover:bg-red-50 border border-line px-2.5 py-1.5 font-bold text-xs  shadow-card rounded-full">
                                    Batalkan
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('organizer.events.edit', $event->id) }}"
                            class="bg-surface-soft hover:bg-surface-soft border border-line px-3 py-1.5 font-bold text-xs  shadow-card rounded-2xl">
                            <x-icon name="edit-2" class="size-4" /> Edit
                        </a>

                        <form action="{{ route('organizer.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus event ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-brand-600 text-white hover:bg-red-600 border border-line px-2.5 py-1.5 font-bold text-xs  shadow-card rounded-full">
                                <x-icon name="trash-2" class="size-4" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-surface border border-line p-12 text-center shadow-card rounded-2xl">
                <span class="text-4xl block mb-2"><x-icon name="calendar" class="size-4" /></span>
                <h3 class="font-bold text-lg">Belum Ada Event Dibuat</h3>
                <p class="text-xs font-bold text-muted mt-1">Mulai buat event kampus pertama Anda sekarang.</p>
                <a href="{{ route('organizer.events.create') }}" class="inline-block mt-4 bg-surface border border-line px-4 py-2 font-bold text-xs  shadow-card rounded-2xl">
                    + Buat Event Sekarang
                </a>
            </div>
        @endforelse
    </div>

    <div>
        {{ $events->links() }}
    </div>
</div>
@endsection
