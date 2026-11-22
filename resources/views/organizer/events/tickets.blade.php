@extends('layouts.dashboard')

@section('title', 'Atur Tiket: ' . $event->title)
@section('page_title', 'Atur tiket')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-ink">Tiket event</h1>
            <p class="text-xs sm:text-sm text-muted mt-0.5">{{ $event->title }}</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('organizer.events.index') }}" class="btn btn-secondary text-xs py-2 px-3.5">
                Kembali ke daftar event
            </a>
            @if($event->status === 'DRAFT' || $event->status === 'REJECTED')
                <form action="{{ route('organizer.events.submit', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary text-xs py-2 px-3.5">
                        <x-icon name="send" class="size-3.5" /> Ajukan ke admin
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Form Add Ticket Tier -->
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 space-y-4">
        <h3 class="font-bold text-base text-ink pb-2 border-b border-line">Tambah tipe tiket</h3>

        <form action="{{ route('organizer.events.tickets.store', $event->id) }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="name" class="block font-semibold text-xs text-ink mb-1.5">Nama Tipe Tiket *</label>
                    <input type="text" id="name" name="name" placeholder="Contoh: Early Bird / Regular / VIP" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">
                </div>

                <div>
                    <label for="price" class="block font-semibold text-xs text-ink mb-1.5">Harga Tiket (Rp) *</label>
                    <input type="number" id="price" name="price" value="0" min="0" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">
                    <span class="text-xs text-muted mt-1 block">*Isi 0 untuk tiket gratis</span>
                </div>

                <div>
                    <label for="quota" class="block font-semibold text-xs text-ink mb-1.5">Batas Kuota Tiket *</label>
                    <input type="number" id="quota" name="quota" min="1" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_sale" class="block font-semibold text-xs text-ink mb-1.5">Mulai Penjualan *</label>
                    <input type="datetime-local" id="start_sale" name="start_sale" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                </div>

                <div>
                    <label for="end_sale" class="block font-semibold text-xs text-ink mb-1.5">Batas Akhir Penjualan *</label>
                    <input type="datetime-local" id="end_sale" name="end_sale" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                </div>
            </div>

            <button type="submit"
                class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-5 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
                + Tambahkan Tipe Tiket
            </button>
        </form>
    </div>

    <!-- Existing Tickets Table -->
    <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-4">
        <h3 class="font-bold text-base text-ink pb-2 border-b border-line/60">Daftar Tiket Terkonfigurasi</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-soft text-xs font-semibold text-muted uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-lg">Tipe Tiket</th>
                        <th class="py-3 px-4">Harga</th>
                        <th class="py-3 px-4">Kuota / Sisa</th>
                        <th class="py-3 px-4">Periode Jual</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/60">
                    @forelse($event->tickets as $t)
                        <tr class="hover:bg-surface-soft/60 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-ink">{{ $t->name }}</td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-ink">
                                @if($t->price == 0)
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700">GRATIS</span>
                                @else
                                    Rp {{ number_format($t->price, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs text-muted">
                                <span class="font-bold text-ink">{{ $t->available_quota }}</span> / {{ $t->quota }} tiket
                            </td>
                            <td class="py-3.5 px-4 text-xs text-muted">
                                {{ $t->start_sale->format('d M') }} s/d {{ $t->end_sale->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($t->isAvailable())
                                    <span class="inline-flex items-center rounded-md border border-emerald-200/60 bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Aktif Dijual</span>
                                @elseif($t->isSoldOut())
                                    <span class="inline-flex items-center rounded-md border border-amber-200/60 bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Habis</span>
                                @else
                                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">Tutup</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('organizer.events.tickets.destroy', ['event' => $event->id, 'ticket' => $t->id]) }}" method="POST" onsubmit="return confirm('Hapus tipe tiket ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-red-50 hover:bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-muted">Belum ada tipe tiket. Silakan buat minimal 1 tipe tiket di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
