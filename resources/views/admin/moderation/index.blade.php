@extends('layouts.dashboard')

@section('title', 'Moderasi Event')

@section('dashboard_content')
<div class="space-y-6" x-data="{ rejectModal: false, activeEventId: null, activeEventTitle: '' }">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6">
        <h1 class="text-xl sm:text-2xl font-bold text-ink">Moderasi pengajuan event</h1>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.moderation.index', ['status' => 'SUBMITTED']) }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === 'SUBMITTED' ? 'bg-brand-700 text-white' : 'bg-surface border border-line text-slate-600 hover:bg-surface-soft hover:text-ink' }}">
            Menunggu tinjauan ({{ $counts['submitted'] }})
        </a>
        <a href="{{ route('admin.moderation.index', ['status' => 'PUBLISHED']) }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === 'PUBLISHED' ? 'bg-brand-700 text-white' : 'bg-surface border border-line text-slate-600 hover:bg-surface-soft hover:text-ink' }}">
            Disetujui ({{ $counts['published'] }})
        </a>
        <a href="{{ route('admin.moderation.index', ['status' => 'REJECTED']) }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === 'REJECTED' ? 'bg-brand-700 text-white' : 'bg-surface border border-line text-slate-600 hover:bg-surface-soft hover:text-ink' }}">
            Ditolak ({{ $counts['rejected'] }})
        </a>
        <a href="{{ route('admin.moderation.index', ['status' => 'ALL']) }}"
            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === 'ALL' ? 'bg-brand-700 text-white' : 'bg-surface border border-line text-slate-600 hover:bg-surface-soft hover:text-ink' }}">
            Semua ({{ $counts['all'] }})
        </a>
    </div>

    <!-- Events Moderation Cards -->
    <div class="space-y-4">
        @forelse($events as $event)
            @php
                $statusBadge = match($event->status) {
                    'PUBLISHED' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                    'SUBMITTED' => 'bg-amber-50 text-amber-800 border-amber-200/60',
                    'REJECTED' => 'bg-red-50 text-red-700 border-red-200/60',
                    default => 'bg-slate-100 text-slate-700 border-slate-200',
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
                        Diajukan: {{ $event->updated_at->format('d M Y, H:i') }} WIB
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <h2 class="text-xl font-bold text-ink leading-snug">{{ $event->title }}</h2>
                        <p class="text-sm text-muted line-clamp-2 leading-relaxed">
                            {{ $event->description }}
                        </p>

                        <div class="pt-2 flex flex-wrap gap-4 text-xs font-medium text-muted">
                            <span class="inline-flex items-center gap-1.5"><x-icon name="home" class="size-4 text-slate-400" /> {{ $event->organization->name }}</span>
                            <span class="inline-flex items-center gap-1.5"><x-icon name="calendar" class="size-4 text-slate-400" /> {{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                            <span class="inline-flex items-center gap-1.5"><x-icon name="map-pin" class="size-4 text-slate-400" /> {{ $event->venue ? $event->venue->name : 'Online' }}</span>
                        </div>

                        @if($event->status === 'REJECTED' && $event->rejection_reason)
                            <div class="rounded-xl bg-red-50/70 border border-red-200/60 p-3.5 text-xs text-red-800 mt-2">
                                <span class="font-bold block mb-0.5">Alasan Penolakan / Catatan Revisi:</span>
                                {{ $event->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <div class="rounded-xl bg-surface-soft/80 border border-line/60 p-4 text-xs space-y-2">
                        <span class="text-xs font-semibold text-slate-600 block uppercase tracking-wider">Rincian Tiket</span>
                        <div class="space-y-1.5">
                            @foreach($event->tickets as $t)
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-muted">{{ $t->name }}:</span>
                                    <span class="font-semibold text-ink">
                                        {{ $t->price == 0 ? 'Gratis' : 'Rp ' . number_format($t->price, 0, ',', '.') }} ({{ $t->quota }} kuota)
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="pt-3 border-t border-line/60 flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('admin.moderation.show', $event->id) }}"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                        <x-icon name="search" class="size-3.5" /> Pratinjau Detail Event
                    </a>

                    <div class="flex items-center gap-2">
                        @if($event->status !== 'PUBLISHED')
                            <form action="{{ route('admin.moderation.approve', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                                    <x-icon name="check" class="size-3.5" /> Setujui & Publikasikan
                                </button>
                            </form>
                        @endif

                        @if($event->status !== 'REJECTED')
                            <button type="button"
                                @click="activeEventId = {{ $event->id }}; activeEventTitle = '{{ addslashes($event->title) }}'; rejectModal = true;"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 text-red-700 border border-red-200/60 hover:bg-red-100 px-4 py-2 text-xs font-semibold transition">
                                <x-icon name="x" class="size-3.5" /> Tolak / Perlu Revisi
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-surface rounded-2xl border border-line/60 p-12 text-center">
                <x-icon name="shield" class="size-10 mx-auto text-slate-300 mb-2" />
                <h3 class="font-bold text-lg text-ink">Tidak Ada Event dalam Kategori Ini</h3>
                <p class="text-xs text-muted mt-1">Semua pengajuan telah dimoderasi dengan rapi.</p>
            </div>
        @endforelse
    </div>

    <div>{{ $events->links() }}</div>

    <!-- Reject Reason Modal -->
    <div x-show="rejectModal" x-cloak class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.outside="rejectModal = false" class="bg-surface rounded-2xl border border-line/60 p-6 sm:p-8 max-w-lg w-full shadow-xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-line/60">
                <h3 class="font-bold text-lg text-ink">Tolak / Minta Revisi Event</h3>
                <button @click="rejectModal = false" class="text-slate-400 hover:text-ink"><x-icon name="x" class="size-5" /></button>
            </div>

            <p class="text-xs text-muted">
                Berikan catatan revisi yang jelas untuk event <span class="font-semibold text-ink" x-text="activeEventTitle"></span> agar penyelenggara dapat memperbaikinya.
            </p>

            <form :action="'/admin/moderation/' + activeEventId + '/reject'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="rejection_reason" class="block font-semibold text-xs text-ink mb-1">Catatan / Alasan Penolakan *</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                        placeholder="Contoh: Deskripsi kegiatan belum mencantumkan target peserta, harap lengkapi rundown acara dan poster resmi..."
                        class="w-full rounded-xl border border-line/80 bg-surface-soft/40 p-3 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="rejectModal = false"
                        class="rounded-lg bg-surface-soft px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-red-600 px-5 py-2 text-xs font-semibold text-white hover:bg-red-700 transition">
                        Kirim Penolakan & Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
