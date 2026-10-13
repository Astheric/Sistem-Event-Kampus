@extends('layouts.dashboard')

@section('title', 'Event Saya — Organizer EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="bg-[#FFE600] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                Koleksi Event
            </span>
            <h1 class="text-3xl font-black mt-2">Daftar Event Saya</h1>
            <p class="text-xs font-bold text-zinc-600 mt-1">Kelola publikasi, tipe tiket, data kehadiran, dan panitia event.</p>
        </div>
        <a href="{{ route('organizer.events.create') }}"
            class="bg-[#FFE600] hover:bg-[#ffd700] border-3 border-black px-5 py-2.5 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap">
            + Tambah Event Baru
        </a>
    </div>

    <!-- Events List Cards -->
    <div class="space-y-4">
        @forelse($events as $event)
            @php
                $statusColors = [
                    'DRAFT' => 'bg-zinc-200',
                    'SUBMITTED' => 'bg-[#FFE600]',
                    'UNDER REVIEW' => 'bg-[#FF6B4A] text-white',
                    'APPROVED' => 'bg-[#4DEEEA]',
                    'PUBLISHED' => 'bg-[#57EB74]',
                    'REJECTED' => 'bg-[#FF70A6]',
                    'COMPLETED' => 'bg-black text-white',
                ];
            @endphp

            <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b-2 border-black pb-3">
                    <div class="flex items-center gap-2">
                        <span class="border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase {{ $statusColors[$event->status] ?? 'bg-zinc-100' }}">
                            {{ $event->status }}
                        </span>
                        <span class="bg-[#00F0FF] border border-black px-2 py-0.5 text-[11px] font-black uppercase">
                            {{ $event->category->name }}
                        </span>
                    </div>
                    <span class="text-xs font-bold text-zinc-500 font-mono">
                        Dibuat: {{ $event->created_at->format('d M Y') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <h2 class="text-xl font-black leading-snug">
                            <a href="{{ route('events.show', $event->slug) }}" class="hover:underline">
                                {{ $event->title }}
                            </a>
                        </h2>
                        <div class="flex flex-wrap gap-4 text-xs font-bold text-zinc-600">
                            <span>📅 {{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                            <span>📍 {{ $event->venue ? $event->venue->name : 'Online' }}</span>
                            <span>👥 Kapasitas: {{ $event->capacity }}</span>
                        </div>

                        @if($event->status === 'REJECTED' && $event->rejection_reason)
                            <div class="bg-[#FF70A6] border-2 border-black p-3 text-xs font-bold mt-2">
                                <span class="font-black uppercase block">Catatan Revisi dari Admin:</span>
                                {{ $event->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <div class="bg-zinc-50 border-2 border-black p-3 text-xs font-bold space-y-2 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] text-zinc-500 uppercase font-black block">Status Peserta</span>
                            <div class="text-2xl font-black font-mono mt-1">{{ $event->registrations_count }}</div>
                            <span class="text-zinc-500">Tiket terdaftar</span>
                        </div>
                        <div class="pt-2 border-t border-black text-[11px]">
                            {{ $event->tickets->count() }} Tipe Tiket Dikonfigurasi
                        </div>
                    </div>
                </div>

                <!-- Action Toolbar Buttons -->
                <div class="pt-3 border-t-2 border-dashed border-black flex flex-wrap items-center justify-between gap-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('organizer.events.tickets', $event->id) }}"
                            class="bg-[#FFE600] border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                            🎟️ Atur Tiket ({{ $event->tickets->count() }})
                        </a>

                        <a href="{{ route('organizer.participants.index', $event->id) }}"
                            class="bg-[#4DEEEA] border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                            👥 Peserta ({{ $event->registrations_count }})
                        </a>

                        <a href="{{ route('organizer.checkin.scanner', $event->id) }}"
                            class="bg-[#57EB74] border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                            📷 Scanner QR
                        </a>

                        <a href="{{ route('organizer.certificates.index', $event->id) }}"
                            class="bg-[#FF70A6] border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                            🎓 Sertifikat
                        </a>

                        <a href="{{ route('organizer.committee.index', $event->id) }}"
                            class="bg-white border-2 border-black px-3 py-1.5 font-bold text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                            🛡️ Panitia
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @if($event->status === 'DRAFT' || $event->status === 'REJECTED')
                            <form action="{{ route('organizer.events.submit', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-black text-white hover:bg-zinc-800 border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                                    🚀 Ajukan
                                </button>
                            </form>
                        @elseif($event->status === 'APPROVED')
                            <form action="{{ route('organizer.events.status', $event->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="PUBLISHED">
                                <button type="submit"
                                    class="bg-[#57EB74] text-black hover:bg-green-400 border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                                    📢 Publikasikan
                                </button>
                            </form>
                        @elseif($event->status === 'PUBLISHED')
                            <form action="{{ route('organizer.events.status', $event->id) }}" method="POST" onsubmit="return confirm('Selesaikan event ini? Status event akan menjadi COMPLETED.')">
                                @csrf
                                <input type="hidden" name="status" value="COMPLETED">
                                <button type="submit"
                                    class="bg-zinc-800 text-white hover:bg-black border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                                    🏁 Selesaikan Event
                                </button>
                            </form>
                        @endif

                        @if(in_array($event->status, ['DRAFT', 'SUBMITTED', 'APPROVED', 'PUBLISHED']))
                            <form action="{{ route('organizer.events.status', $event->id) }}" method="POST" onsubmit="return confirm('Batalkan event ini? Aksi ini akan mengubah status menjadi CANCELLED.')">
                                @csrf
                                <input type="hidden" name="status" value="CANCELLED">
                                <button type="submit"
                                    class="bg-white text-red-600 hover:bg-red-50 border-2 border-black px-2.5 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
                                    Batalkan
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('organizer.events.edit', $event->id) }}"
                            class="bg-zinc-100 hover:bg-zinc-200 border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
                            ✏️ Edit
                        </a>

                        <form action="{{ route('organizer.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus event ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-[#FF6B4A] text-white hover:bg-red-600 border-2 border-black px-2.5 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border-3 border-black p-12 text-center shadow-[4px_4px_0px_0px_#000]">
                <span class="text-4xl block mb-2">📅</span>
                <h3 class="font-black text-lg">Belum Ada Event Dibuat</h3>
                <p class="text-xs font-bold text-zinc-500 mt-1">Mulai buat event kampus pertama Anda sekarang.</p>
                <a href="{{ route('organizer.events.create') }}" class="inline-block mt-4 bg-[#FFE600] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
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
