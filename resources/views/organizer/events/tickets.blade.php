@extends('layouts.dashboard')

@section('title', 'Atur Tiket — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl">
        <div>
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                Tiket Bertingkat (Tiered)
            </span>
            <h1 class="text-3xl font-bold mt-2">Konfigurasi Tiket Event</h1>
            <p class="text-xs font-bold text-muted mt-1">{{ $event->title }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('organizer.events.index') }}" class="bg-surface border border-line px-3 py-2 font-bold text-xs  shadow-card rounded-2xl">
                ← Kembali
            </a>
            @if($event->status === 'DRAFT' || $event->status === 'REJECTED')
                <form action="{{ route('organizer.events.submit', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-brand-600 text-white bg-danger text-white hover:opacity-90 border border-line px-4 py-2 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                        <x-icon name="send" class="size-4" /> Ajukan ke Admin
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Form Add Ticket Tier (FR-06) -->
    <div class="bg-surface border border-line p-6 shadow-card rounded-2xl">
        <h3 class="font-bold text-base  mb-4 border-b border-line pb-2">+ Tambah Tipe / Tier Tiket Baru</h3>

        <form action="{{ route('organizer.events.tickets.store', $event->id) }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="name" class="block font-bold text-xs  mb-1">Nama Tipe Tiket *</label>
                    <input type="text" id="name" name="name" placeholder="Contoh: Early Bird / Regular / VIP" required
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                </div>

                <div>
                    <label for="price" class="block font-bold text-xs  mb-1">Harga Tiket (Rp) *</label>
                    <input type="number" id="price" name="price" value="0" min="0" required
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                    <span class="text-[10px] text-muted font-bold">*Isi 0 untuk tiket gratis</span>
                </div>

                <div>
                    <label for="quota" class="block font-bold text-xs  mb-1">Batas Kuota Tiket *</label>
                    <input type="number" id="quota" name="quota" min="1" required
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_sale" class="block font-bold text-xs  mb-1">Mulai Penjualan *</label>
                    <input type="datetime-local" id="start_sale" name="start_sale" required
                        class="w-full bg-surface border border-line p-2 font-bold text-xs focus:outline-none focus:bg-surface">
                </div>

                <div>
                    <label for="end_sale" class="block font-bold text-xs  mb-1">Batas Akhir Penjualan *</label>
                    <input type="datetime-local" id="end_sale" name="end_sale" required
                        class="w-full bg-surface border border-line p-2 font-bold text-xs focus:outline-none focus:bg-surface">
                </div>
            </div>

            <button type="submit"
                class="bg-brand-600 text-white hover:bg-brand-700 border border-line px-5 py-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                + Tambahkan Tipe Tiket
            </button>
        </form>
    </div>

    <!-- Existing Tickets Table -->
    <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <h3 class="font-bold text-base  border-b border-line pb-2">Daftar Tiket Terkonfigurasi</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-danger text-white font-mono  text-[10px]">
                        <th class="p-3 border border-line">Tipe Tiket</th>
                        <th class="p-3 border border-line">Harga</th>
                        <th class="p-3 border border-line">Kuota / Sisa</th>
                        <th class="p-3 border border-line">Periode Jual</th>
                        <th class="p-3 border border-line">Status</th>
                        <th class="p-3 border border-line text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->tickets as $t)
                        <tr class="hover:bg-surface-soft border-b border-line">
                            <td class="p-3 border border-line font-bold text-sm">{{ $t->name }}</td>
                            <td class="p-3 border border-line font-mono">
                                @if($t->price == 0)
                                    <span class="bg-surface border border-line px-1.5 py-0.5 text-[10px] font-bold">GRATIS</span>
                                @else
                                    Rp {{ number_format($t->price, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="p-3 border border-line font-mono">
                                <span class="font-bold">{{ $t->available_quota }}</span> / {{ $t->quota }} tiket
                            </td>
                            <td class="p-3 border border-line text-[11px]">
                                {{ $t->start_sale->format('d M') }} s/d {{ $t->end_sale->format('d M Y') }}
                            </td>
                            <td class="p-3 border border-line">
                                @if($t->isAvailable())
                                    <span class="bg-surface text-black px-2 py-0.5 border border-line text-[10px] font-bold ">Aktif Dijual</span>
                                @elseif($t->isSoldOut())
                                    <span class="bg-surface text-black px-2 py-0.5 border border-line text-[10px] font-bold ">Habis</span>
                                @else
                                    <span class="bg-surface-soft text-black px-2 py-0.5 border border-line text-[10px] font-bold ">Tutup</span>
                                @endif
                            </td>
                            <td class="p-3 border border-line text-center">
                                <form action="{{ route('organizer.events.tickets.destroy', ['event' => $event->id, 'ticket' => $t->id]) }}" method="POST" onsubmit="return confirm('Hapus tipe tiket ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-brand-600 text-white border border-line px-2 py-1 font-bold text-[10px] hover:bg-red-600">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-muted">Belum ada tipe tiket. Silakan buat minimal 1 tipe tiket di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
