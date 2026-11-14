@extends('layouts.dashboard')

@section('title', 'Manajemen Peserta — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6" x-data="{ modalOpen: false, activePayment: null, activeRegistration: null }">
    <!-- Header -->
    <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl">
        <div>
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                Data Peserta & Transaksi
            </span>
            <h1 class="text-3xl font-bold mt-2">Daftar Peserta Event</h1>
            <p class="text-xs font-bold text-muted mt-1">{{ $event->title }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('organizer.participants.export', $event->id) }}"
                class="bg-brand-600 text-white hover:bg-brand-600 text-white border border-line px-4 py-2 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                <x-icon name="download" class="size-4" /> Ekspor Data CSV
            </a>
            <a href="{{ route('organizer.events.index') }}" class="bg-surface border border-line px-3 py-2 font-bold text-xs  shadow-card rounded-2xl">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
        <div class="bg-surface border border-line p-3 shadow-card rounded-2xl">
            <span class="text-[10px]  font-bold text-muted">Total Pendaftar</span>
            <div class="text-2xl font-bold font-mono mt-0.5">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-surface border border-line p-3 shadow-card rounded-2xl">
            <span class="text-[10px]  font-bold">Terkonfirmasi</span>
            <div class="text-2xl font-bold font-mono mt-0.5">{{ $stats['confirmed'] }}</div>
        </div>
        <div class="bg-surface border border-line p-3 shadow-card rounded-2xl">
            <span class="text-[10px]  font-bold">Menunggu Bayar</span>
            <div class="text-2xl font-bold font-mono mt-0.5">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-surface border border-line p-3 shadow-card rounded-2xl">
            <span class="text-[10px]  font-bold">Telah Check-in</span>
            <div class="text-2xl font-bold font-mono mt-0.5">{{ $stats['attended'] }}</div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <form action="{{ route('organizer.participants.index', $event->id) }}" method="GET"
        class="bg-surface border border-line p-4 shadow-card flex flex-wrap items-center gap-3 rounded-2xl">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, kode..."
            class="bg-surface border border-line p-2 font-bold text-xs flex-grow focus:outline-none focus:bg-surface">

        <select name="status" class="bg-surface border border-line p-2 font-bold text-xs focus:outline-none">
            <option value="">Semua Status</option>
            <option value="CONFIRMED" {{ request('status') === 'CONFIRMED' ? 'selected' : '' }}>CONFIRMED (Aktif)</option>
            <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>PENDING (Menunggu)</option>
            <option value="ATTENDED" {{ request('status') === 'ATTENDED' ? 'selected' : '' }}>ATTENDED (Hadir)</option>
            <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>CANCELLED (Batal)</option>
        </select>

        <select name="ticket_id" class="bg-surface border border-line p-2 font-bold text-xs focus:outline-none">
            <option value="">Semua Tipe Tiket</option>
            @foreach($tickets as $t)
                <option value="{{ $t->id }}" {{ request('ticket_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-brand-600 text-white border border-line px-4 py-2 font-bold text-xs  shadow-card rounded-full">
            Filter
        </button>
    </form>

    <!-- Table -->
    <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-danger text-white font-mono  text-[10px]">
                        <th class="p-3 border border-line">Kode</th>
                        <th class="p-3 border border-line">Nama Peserta</th>
                        <th class="p-3 border border-line">Tipe Tiket</th>
                        <th class="p-3 border border-line">Biaya</th>
                        <th class="p-3 border border-line">Status Tiket</th>
                        <th class="p-3 border border-line">Pembayaran</th>
                        <th class="p-3 border border-line">Kehadiran</th>
                        <th class="p-3 border border-line text-center">Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-surface-soft border-b border-line">
                            <td class="p-3 border border-line font-mono font-bold">{{ $reg->registration_code }}</td>
                            <td class="p-3 border border-line">
                                <p class="font-bold text-sm">{{ $reg->user->name }}</p>
                                <span class="text-muted font-mono text-[10px]">{{ $reg->user->email }}</span>
                            </td>
                            <td class="p-3 border border-line">{{ $reg->ticket->name }}</td>
                            <td class="p-3 border border-line font-mono">
                                @if($reg->ticket->price == 0)
                                    <span class="text-ink">Gratis</span>
                                @else
                                    Rp {{ number_format($reg->ticket->price, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="p-3 border border-line">
                                <span class="px-2 py-0.5 border border-line text-[10px] font-bold  {{ $reg->status === 'CONFIRMED' ? 'bg-surface' : ($reg->status === 'ATTENDED' ? 'bg-surface' : ($reg->status === 'PENDING' ? 'bg-surface' : 'bg-surface')) }}">
                                    {{ $reg->status }}
                                </span>
                            </td>
                            <td class="p-3 border border-line">
                                @if($reg->payment)
                                    <span class="block text-[10px] font-bold">{{ $reg->payment->payment_method }}</span>
                                    <span class="px-1.5 py-0.2 border border-line text-[9px] font-bold  {{ $reg->payment->isVerified() ? 'bg-surface' : ($reg->payment->isPending() ? 'bg-surface' : 'bg-surface') }}">
                                        {{ $reg->payment->status }}
                                    </span>
                                @else
                                    <span class="text-muted font-mono">-</span>
                                @endif
                            </td>
                            <td class="p-3 border border-line">
                                @if($reg->checkin)
                                    <span class="text-ink font-bold"><x-icon name="check" class="size-4" /> Hadir</span>
                                    <span class="text-[10px] text-muted font-mono block">{{ $reg->checkin->checked_in_at->format('H:i') }} WIB</span>
                                @else
                                    <span class="text-muted">Belum Check-in</span>
                                @endif
                            </td>
                            <td class="p-3 border border-line text-center">
                                @if($reg->payment && $reg->payment->isPending())
                                    <button @click="activePayment = {{ json_encode($reg->payment) }}; activeRegistration = {{ json_encode($reg) }}; modalOpen = true;"
                                        class="bg-brand-600 text-white border border-line px-2.5 py-1 font-bold text-[10px]  shadow-card active:translate-x-[1px] active:translate-y-[1px] rounded-full">
                                        Verifikasi <x-icon name="search" class="size-4" />
                                    </button>
                                @else
                                    <span class="text-muted text-[10px] font-mono">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-muted">Tidak ada peserta yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $registrations->links() }}</div>
    </div>

    <!-- Verification Modal (Neo-Brutalism Pop-up) -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div @click.outside="modalOpen = false" class="bg-surface border border-line p-6 sm:p-8 max-w-lg w-full shadow-card space-y-4 rounded-2xl">
            <div class="flex items-center justify-between border-b border-line pb-3">
                <h3 class="font-bold text-lg">Verifikasi Pembayaran Simulasi</h3>
                <button @click="modalOpen = false" class="border border-line px-2 py-0.5 font-bold text-xs"><x-icon name="x" class="size-4" /></button>
            </div>

            <div class="text-xs font-bold space-y-2 bg-surface border border-line p-4">
                <p>Kode Tiket: <span class="font-mono font-bold" x-text="activeRegistration?.registration_code"></span></p>
                <p>Metode: <span class="font-bold" x-text="activePayment?.payment_method"></span></p>
                <p>Nominal: <span class="font-bold font-mono" x-text="'Rp ' + Number(activePayment?.amount || 0).toLocaleString('id-ID')"></span></p>
                <div x-show="activePayment?.proof_file" class="pt-2">
                    <span class="block  text-[10px] text-muted mb-1">Bukti Transfer:</span>
                    <a :href="'/storage/' + activePayment?.proof_file" target="_blank" class="text-ink underline font-bold">
                        Lihat Gambar Bukti Transfer ↗
                    </a>
                </div>
            </div>

            <form :action="'/organizer/payments/' + (activePayment?.id || 0) + '/verify'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-bold text-xs  mb-1">Catatan Panitia (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Nominal sesuai / bukti sah..."
                        class="w-full bg-surface border border-line p-2 font-bold text-xs"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="submit" name="action" value="reject"
                        class="bg-brand-600 text-white hover:bg-pink-400 border border-line p-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                        <x-icon name="x" class="size-4" /> Tolak Pembayaran
                    </button>

                    <button type="submit" name="action" value="approve"
                        class="bg-brand-600 text-white hover:bg-brand-600 text-white border border-line p-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                        <x-icon name="check" class="size-4" /> Setujui (Konfirmasi)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
