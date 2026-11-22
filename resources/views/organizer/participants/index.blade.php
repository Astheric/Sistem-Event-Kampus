@extends('layouts.dashboard')

@section('title', 'Peserta: ' . $event->title)
@section('page_title', 'Peserta')

@section('dashboard_content')
<div class="space-y-6" x-data="{ modalOpen: false, activePayment: null, activeRegistration: null }">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-ink">Peserta event</h1>
            <p class="text-xs sm:text-sm text-muted mt-0.5">{{ $event->title }}</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('organizer.participants.export', $event->id) }}"
                class="btn btn-secondary text-xs py-2 px-3.5">
                <x-icon name="download" class="size-3.5" /> Ekspor CSV
            </a>
            <a href="{{ Auth::user()->isOrganizer() || Auth::user()->isAdmin() ? route('organizer.events.index') : route('organizer.committee.tasks') }}" class="btn btn-secondary text-xs py-2 px-3.5">
                Kembali
            </a>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted block">Total Pendaftar</span>
            <div class="text-2xl font-extrabold text-ink mt-1.5">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-semibold text-emerald-700 block">Terkonfirmasi</span>
            <div class="text-2xl font-extrabold text-emerald-600 mt-1.5">{{ number_format($stats['confirmed']) }}</div>
        </div>
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-semibold text-amber-800 block">Menunggu Bayar</span>
            <div class="text-2xl font-extrabold text-amber-700 mt-1.5">{{ number_format($stats['pending']) }}</div>
        </div>
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-semibold text-brand-700 block">Telah Check-in</span>
            <div class="text-2xl font-extrabold text-brand-700 mt-1.5">{{ number_format($stats['attended']) }}</div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <form action="{{ route('organizer.participants.index', $event->id) }}" method="GET"
        class="bg-surface rounded-2xl border border-line/60 p-4 flex flex-wrap items-center gap-3">
        <div class="flex-grow min-w-[200px]">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, kode registrasi..."
                class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">
        </div>

        <select name="status" class="rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm text-slate-700 focus:border-brand-700 focus:bg-surface focus:outline-none">
            <option value="">Semua Status</option>
            <option value="CONFIRMED" {{ request('status') === 'CONFIRMED' ? 'selected' : '' }}>CONFIRMED (Aktif)</option>
            <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>PENDING (Menunggu)</option>
            <option value="ATTENDED" {{ request('status') === 'ATTENDED' ? 'selected' : '' }}>ATTENDED (Hadir)</option>
            <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>CANCELLED (Batal)</option>
        </select>

        <select name="ticket_id" class="rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm text-slate-700 focus:border-brand-700 focus:bg-surface focus:outline-none">
            <option value="">Semua Tipe Tiket</option>
            @foreach($tickets as $t)
                <option value="{{ $t->id }}" {{ request('ticket_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
            <x-icon name="search" class="size-3.5" /> Filter
        </button>
    </form>

    <!-- Table -->
    <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-soft text-xs font-semibold text-muted uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-lg">Kode</th>
                        <th class="py-3 px-4">Nama Peserta</th>
                        <th class="py-3 px-4">Tipe Tiket</th>
                        <th class="py-3 px-4">Biaya</th>
                        <th class="py-3 px-4">Status Tiket</th>
                        <th class="py-3 px-4">Pembayaran</th>
                        <th class="py-3 px-4">Kehadiran</th>
                        <th class="py-3 px-4 text-center rounded-r-lg">Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/60">
                    @forelse($registrations as $reg)
                        @php
                            $statusBadge = match($reg->status) {
                                'CONFIRMED' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                'ATTENDED' => 'bg-brand-50 text-brand-700 border-brand-200/60',
                                'PENDING' => 'bg-amber-50 text-amber-800 border-amber-200/60',
                                'CANCELLED' => 'bg-red-50 text-red-700 border-red-200/60',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-surface-soft/60 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-semibold text-xs text-ink">{{ $reg->registration_code }}</td>
                            <td class="py-3.5 px-4">
                                <p class="font-semibold text-ink text-sm">{{ $reg->user->name }}</p>
                                <span class="text-xs text-muted font-mono block">{{ $reg->user->email }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-700">{{ $reg->ticket->name }}</td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-ink">
                                @if($reg->ticket->price == 0)
                                    <span class="text-emerald-700">Gratis</span>
                                @else
                                    Rp {{ number_format($reg->ticket->price, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold {{ $statusBadge }}">
                                    {{ $reg->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                @if($reg->payment)
                                    <span class="block font-medium text-slate-700">{{ $reg->payment->payment_method }}</span>
                                    <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-semibold {{ $reg->payment->isVerified() ? 'bg-emerald-50 text-emerald-700' : ($reg->payment->isPending() ? 'bg-amber-50 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $reg->payment->status }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                @if($reg->checkin)
                                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-700"><x-icon name="check" class="size-3.5" /> Hadir</span>
                                    <span class="text-xs text-muted block">{{ $reg->checkin->checked_in_at->format('H:i') }} WIB</span>
                                @else
                                    <span class="text-muted">Belum Check-in</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($reg->payment && $reg->payment->isPending())
                                    <button @click="activePayment = {{ json_encode($reg->payment) }}; activeRegistration = {{ json_encode($reg) }}; modalOpen = true;"
                                        class="inline-flex items-center gap-1 rounded-lg bg-amber-500 hover:bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white transition">
                                        Verifikasi <x-icon name="search" class="size-3" />
                                    </button>
                                @else
                                    <span class="text-muted text-xs">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-muted">Tidak ada peserta yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $registrations->links() }}</div>
    </div>

    <!-- Verification Modal -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.outside="modalOpen = false" class="bg-surface rounded-2xl border border-line/60 p-6 sm:p-8 max-w-lg w-full shadow-xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-line/60">
                <h3 class="font-bold text-lg text-ink">Verifikasi pembayaran</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-ink"><x-icon name="x" class="size-5" /></button>
            </div>

            <div class="rounded-xl border border-line/60 bg-surface-soft/50 p-4 text-xs space-y-2">
                <p class="text-slate-600">Kode Tiket: <span class="font-mono font-semibold text-ink" x-text="activeRegistration?.registration_code"></span></p>
                <p class="text-slate-600">Metode: <span class="font-semibold text-ink" x-text="activePayment?.payment_method"></span></p>
                <p class="text-slate-600">Nominal: <span class="font-bold text-ink" x-text="'Rp ' + Number(activePayment?.amount || 0).toLocaleString('id-ID')"></span></p>
                <div x-show="activePayment?.proof_file" class="pt-2 border-t border-line/40">
                    <span class="block text-muted text-xs mb-1">Bukti Transfer:</span>
                    <a :href="'/storage/' + activePayment?.proof_file" target="_blank" class="inline-flex items-center gap-1 font-semibold text-brand-700 hover:underline">
                        Lihat Gambar Bukti Transfer ↗
                    </a>
                </div>
            </div>

            <form :action="'/organizer/payments/' + (activePayment?.id || 0) + '/verify'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-semibold text-xs text-ink mb-1">Catatan Panitia (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Nominal sesuai / bukti transfer valid..."
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 p-2.5 text-xs focus:border-brand-700 focus:bg-surface focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="submit" name="action" value="reject"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-red-50 text-red-700 border border-red-200/60 hover:bg-red-100 p-2.5 text-xs font-semibold transition">
                        <x-icon name="x" class="size-3.5" /> Tolak Pembayaran
                    </button>

                    <button type="submit" name="action" value="approve"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white p-2.5 text-xs font-semibold transition">
                        <x-icon name="check" class="size-3.5" /> Setujui (Konfirmasi)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
