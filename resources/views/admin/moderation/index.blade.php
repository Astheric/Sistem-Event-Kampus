@extends('layouts.dashboard')

@section('title', 'Moderasi Event — Admin EventHub')

@section('dashboard_content')
<div class="space-y-6" x-data="{ rejectModal: false, activeEventId: null, activeEventTitle: '' }">
    <!-- Header -->
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000]">
        <span class="bg-[#FF6B4A] text-white border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
            Persetujuan Event (FR-19)
        </span>
        <h1 class="text-3xl font-black mt-2">Moderasi Pengajuan Event Kampus</h1>
        <p class="text-xs font-bold text-zinc-600 mt-1">
            Verifikasi kelayakan event yang diajukan oleh BEM, Himpunan, atau UKM sebelum dipublikasikan ke katalog umum.
        </p>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex flex-wrap gap-2 text-xs font-black">
        <a href="{{ route('admin.moderation.index', ['status' => 'SUBMITTED']) }}"
            class="px-3.5 py-2 border-2 border-black {{ $status === 'SUBMITTED' ? 'bg-[#FFE600] shadow-[3px_3px_0px_0px_#000]' : 'bg-white hover:bg-zinc-100' }}">
            Menunggu Tinjauan ({{ $counts['submitted'] }})
        </a>
        <a href="{{ route('admin.moderation.index', ['status' => 'PUBLISHED']) }}"
            class="px-3.5 py-2 border-2 border-black {{ $status === 'PUBLISHED' ? 'bg-[#57EB74] shadow-[3px_3px_0px_0px_#000]' : 'bg-white hover:bg-zinc-100' }}">
            Disetujui / Aktif ({{ $counts['published'] }})
        </a>
        <a href="{{ route('admin.moderation.index', ['status' => 'REJECTED']) }}"
            class="px-3.5 py-2 border-2 border-black {{ $status === 'REJECTED' ? 'bg-[#FF70A6] shadow-[3px_3px_0px_0px_#000]' : 'bg-white hover:bg-zinc-100' }}">
            Ditolak / Revisi ({{ $counts['rejected'] }})
        </a>
        <a href="{{ route('admin.moderation.index', ['status' => 'ALL']) }}"
            class="px-3.5 py-2 border-2 border-black {{ $status === 'ALL' ? 'bg-black text-white shadow-[3px_3px_0px_0px_#000]' : 'bg-white hover:bg-zinc-100' }}">
            Semua ({{ $counts['all'] }})
        </a>
    </div>

    <!-- Events Moderation Cards -->
    <div class="space-y-4">
        @forelse($events as $event)
            <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b-2 border-black pb-3">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 border border-black text-[10px] font-black uppercase {{ $event->status === 'PUBLISHED' ? 'bg-[#57EB74]' : ($event->status === 'SUBMITTED' ? 'bg-[#FFE600]' : 'bg-[#FF70A6]') }}">
                            {{ $event->status }}
                        </span>
                        <span class="bg-[#00F0FF] border border-black px-2 py-0.5 text-[10px] font-black uppercase">
                            {{ $event->category->name }}
                        </span>
                    </div>
                    <span class="text-xs font-mono font-bold text-zinc-500">
                        Diajukan: {{ $event->updated_at->format('d M Y, H:i') }} WIB
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <h2 class="text-xl font-black leading-snug">{{ $event->title }}</h2>
                        <p class="text-xs font-bold text-zinc-600 line-clamp-2 leading-relaxed">
                            {{ $event->description }}
                        </p>

                        <div class="pt-2 flex flex-wrap gap-4 text-xs font-bold text-zinc-600">
                            <span>🏢 {{ $event->organization->name }}</span>
                            <span>📅 {{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                            <span>📍 {{ $event->venue ? $event->venue->name : 'Online' }}</span>
                        </div>

                        @if($event->status === 'REJECTED' && $event->rejection_reason)
                            <div class="bg-[#FF70A6] border-2 border-black p-3 text-xs font-bold mt-2">
                                <span class="font-black uppercase block">Alasan Penolakan:</span>
                                {{ $event->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <div class="bg-[#FFFDF8] border-2 border-black p-4 text-xs font-bold space-y-2">
                        <span class="text-[10px] font-black uppercase text-zinc-500 block">Rincian Tiket</span>
                        <div class="space-y-1">
                            @foreach($event->tickets as $t)
                                <div class="flex justify-between">
                                    <span>{{ $t->name }}:</span>
                                    <span class="font-mono font-black">
                                        {{ $t->price == 0 ? 'Gratis' : 'Rp ' . number_format($t->price, 0, ',', '.') }} ({{ $t->quota }} kuota)
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="pt-3 border-t-2 border-dashed border-black flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('admin.moderation.show', $event->id) }}"
                        class="bg-[#00F0FF] border-2 border-black px-3 py-1.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                        🔍 Pratinjau Detail Event
                    </a>

                    <div class="flex items-center gap-2">
                        @if($event->status !== 'PUBLISHED')
                            <form action="{{ route('admin.moderation.approve', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-[#57EB74] hover:bg-[#43d960] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                                    ✓ Setujui & Publikasikan
                                </button>
                            </form>
                        @endif

                        @if($event->status !== 'REJECTED')
                            <button type="button"
                                @click="activeEventId = {{ $event->id }}; activeEventTitle = '{{ addslashes($event->title) }}'; rejectModal = true;"
                                class="bg-[#FF70A6] hover:bg-pink-400 border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                                ✕ Tolak / Perlu Revisi
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border-3 border-black p-12 text-center shadow-[4px_4px_0px_0px_#000]">
                <span class="text-4xl block mb-2">🛡️</span>
                <h3 class="font-black text-lg">Tidak Ada Event dalam Kategori Ini</h3>
                <p class="text-xs font-bold text-zinc-500 mt-1">Semua pengajuan telah dimoderasi dengan rapi.</p>
            </div>
        @endforelse
    </div>

    <div>{{ $events->links() }}</div>

    <!-- Reject Reason Modal (FR-19) -->
    <div x-show="rejectModal" x-cloak class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div @click.outside="rejectModal = false" class="bg-white border-4 border-black p-6 sm:p-8 max-w-lg w-full shadow-[10px_10px_0px_0px_#000] space-y-4">
            <div class="flex items-center justify-between border-b-3 border-black pb-3">
                <h3 class="font-black text-lg">Tolak / Minta Revisi Event</h3>
                <button @click="rejectModal = false" class="border-2 border-black px-2 py-0.5 font-black text-xs">✕</button>
            </div>

            <p class="text-xs font-bold text-zinc-700">
                Berikan catatan revisi yang jelas untuk event <span class="font-black" x-text="activeEventTitle"></span> agar penyelenggara dapat memperbaikinya.
            </p>

            <form :action="'/admin/moderation/' + activeEventId + '/reject'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="rejection_reason" class="block font-black text-xs uppercase mb-1">Catatan / Alasan Penolakan *</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                        placeholder="Contoh: Deskripsi kegiatan belum mencantumkan target peserta, harap lengkapi rundown acara dan poster resmi..."
                        class="w-full bg-[#FFFDF8] border-2 border-black p-3 font-bold text-xs focus:outline-none focus:bg-[#FFE600]"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="rejectModal = false"
                        class="bg-white border-2 border-black px-4 py-2 font-bold text-xs uppercase">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-[#FF6B4A] hover:bg-red-600 text-white border-2 border-black px-5 py-2 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                        Kirim Penolakan & Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
