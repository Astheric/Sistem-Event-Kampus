@extends('layouts.dashboard')

@section('title', 'Moderasi Event — Admin EventHub')

@section('dashboard_content')
<div class="space-y-6" x-data="{ rejectModal: false, activeEventId: null, activeEventTitle: '' }">
    <!-- Header -->
    <div class="bg-surface border border-line p-6 shadow-card rounded-2xl">
        <span class="bg-surface text-white border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
            Persetujuan Event (FR-19)
        </span>
        <h1 class="text-3xl font-bold mt-2">Moderasi Pengajuan Event Kampus</h1>
        <p class="text-xs font-bold text-muted mt-1">
            Verifikasi kelayakan event yang diajukan oleh BEM, Himpunan, atau UKM sebelum dipublikasikan ke katalog umum.
        </p>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex flex-wrap gap-2 text-xs font-bold">
        <a href="{{ route('admin.moderation.index', ['status' => 'SUBMITTED']) }}"
            class="px-3.5 py-2 border border-line {{ $status === 'SUBMITTED' ? 'bg-surface shadow-card' : 'bg-surface hover:bg-surface-soft' }} rounded-2xl">
            Menunggu Tinjauan ({{ $counts['submitted'] }})
        </a>
        <a href="{{ route('admin.moderation.index', ['status' => 'PUBLISHED']) }}"
            class="px-3.5 py-2 border border-line {{ $status === 'PUBLISHED' ? 'bg-surface shadow-card' : 'bg-surface hover:bg-surface-soft' }} rounded-2xl">
            Disetujui / Aktif ({{ $counts['published'] }})
        </a>
        <a href="{{ route('admin.moderation.index', ['status' => 'REJECTED']) }}"
            class="px-3.5 py-2 border border-line {{ $status === 'REJECTED' ? 'bg-surface shadow-card' : 'bg-surface hover:bg-surface-soft' }} rounded-2xl">
            Ditolak / Revisi ({{ $counts['rejected'] }})
        </a>
        <a href="{{ route('admin.moderation.index', ['status' => 'ALL']) }}"
            class="px-3.5 py-2 border border-line {{ $status === 'ALL' ? 'bg-danger text-white shadow-card' : 'bg-surface hover:bg-surface-soft' }} rounded-2xl">
            Semua ({{ $counts['all'] }})
        </a>
    </div>

    <!-- Events Moderation Cards -->
    <div class="space-y-4">
        @forelse($events as $event)
            <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-line pb-3">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 border border-line text-[10px] font-bold  {{ $event->status === 'PUBLISHED' ? 'bg-surface' : ($event->status === 'SUBMITTED' ? 'bg-surface' : 'bg-surface') }}">
                            {{ $event->status }}
                        </span>
                        <span class="bg-surface border border-line px-2 py-0.5 text-[10px] font-bold ">
                            {{ $event->category->name }}
                        </span>
                    </div>
                    <span class="text-xs font-mono font-bold text-muted">
                        Diajukan: {{ $event->updated_at->format('d M Y, H:i') }} WIB
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <h2 class="text-xl font-bold leading-snug">{{ $event->title }}</h2>
                        <p class="text-xs font-bold text-muted line-clamp-2 leading-relaxed">
                            {{ $event->description }}
                        </p>

                        <div class="pt-2 flex flex-wrap gap-4 text-xs font-bold text-muted">
                            <span><x-icon name="home" class="size-4" /> {{ $event->organization->name }}</span>
                            <span><x-icon name="calendar" class="size-4" /> {{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                            <span><x-icon name="map-pin" class="size-4" /> {{ $event->venue ? $event->venue->name : 'Online' }}</span>
                        </div>

                        @if($event->status === 'REJECTED' && $event->rejection_reason)
                            <div class="bg-surface border border-line p-3 text-xs font-bold mt-2">
                                <span class="font-bold  block">Alasan Penolakan:</span>
                                {{ $event->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <div class="bg-surface border border-line p-4 text-xs font-bold space-y-2">
                        <span class="text-[10px] font-bold  text-muted block">Rincian Tiket</span>
                        <div class="space-y-1">
                            @foreach($event->tickets as $t)
                                <div class="flex justify-between">
                                    <span>{{ $t->name }}:</span>
                                    <span class="font-mono font-bold">
                                        {{ $t->price == 0 ? 'Gratis' : 'Rp ' . number_format($t->price, 0, ',', '.') }} ({{ $t->quota }} kuota)
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="pt-3 border-t border-dashed border-line flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('admin.moderation.show', $event->id) }}"
                        class="bg-brand-600 text-white border border-line px-3 py-1.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                        <x-icon name="search" class="size-4" /> Pratinjau Detail Event
                    </a>

                    <div class="flex items-center gap-2">
                        @if($event->status !== 'PUBLISHED')
                            <form action="{{ route('admin.moderation.approve', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-brand-600 text-white hover:bg-brand-700 border border-line px-4 py-2 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                                    <x-icon name="check" class="size-4" /> Setujui & Publikasikan
                                </button>
                            </form>
                        @endif

                        @if($event->status !== 'REJECTED')
                            <button type="button"
                                @click="activeEventId = {{ $event->id }}; activeEventTitle = '{{ addslashes($event->title) }}'; rejectModal = true;"
                                class="bg-brand-600 text-white hover:bg-pink-400 border border-line px-4 py-2 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                                <x-icon name="x" class="size-4" /> Tolak / Perlu Revisi
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-surface border border-line p-12 text-center shadow-card rounded-2xl">
                <span class="text-4xl block mb-2"><x-icon name="shield" class="size-4" /></span>
                <h3 class="font-bold text-lg">Tidak Ada Event dalam Kategori Ini</h3>
                <p class="text-xs font-bold text-muted mt-1">Semua pengajuan telah dimoderasi dengan rapi.</p>
            </div>
        @endforelse
    </div>

    <div>{{ $events->links() }}</div>

    <!-- Reject Reason Modal (FR-19) -->
    <div x-show="rejectModal" x-cloak class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div @click.outside="rejectModal = false" class="bg-surface border border-line p-6 sm:p-8 max-w-lg w-full shadow-card space-y-4 rounded-2xl">
            <div class="flex items-center justify-between border-b border-line pb-3">
                <h3 class="font-bold text-lg">Tolak / Minta Revisi Event</h3>
                <button @click="rejectModal = false" class="border border-line px-2 py-0.5 font-bold text-xs"><x-icon name="x" class="size-4" /></button>
            </div>

            <p class="text-xs font-bold text-muted">
                Berikan catatan revisi yang jelas untuk event <span class="font-bold" x-text="activeEventTitle"></span> agar penyelenggara dapat memperbaikinya.
            </p>

            <form :action="'/admin/moderation/' + activeEventId + '/reject'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="rejection_reason" class="block font-bold text-xs  mb-1">Catatan / Alasan Penolakan *</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                        placeholder="Contoh: Deskripsi kegiatan belum mencantumkan target peserta, harap lengkapi rundown acara dan poster resmi..."
                        class="w-full bg-surface border border-line p-3 font-bold text-xs focus:outline-none focus:bg-surface"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="rejectModal = false"
                        class="bg-surface border border-line px-4 py-2 font-bold text-xs ">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-brand-600 text-white hover:bg-red-600 text-white border border-line px-5 py-2 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                        Kirim Penolakan & Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
