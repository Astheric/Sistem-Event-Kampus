@extends('layouts.dashboard')

@section('title', 'Atur Tiket — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="bg-[#FFE600] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                Tiket Bertingkat (Tiered)
            </span>
            <h1 class="text-3xl font-black mt-2">Konfigurasi Tiket Event</h1>
            <p class="text-xs font-bold text-zinc-600 mt-1">{{ $event->title }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('organizer.events.index') }}" class="bg-white border-2 border-black px-3 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
                ← Kembali
            </a>
            @if($event->status === 'DRAFT' || $event->status === 'REJECTED')
                <form action="{{ route('organizer.events.submit', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-black text-white hover:bg-zinc-800 border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                        🚀 Ajukan ke Admin
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Form Add Ticket Tier (FR-06) -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000]">
        <h3 class="font-black text-base uppercase mb-4 border-b-2 border-black pb-2">+ Tambah Tipe / Tier Tiket Baru</h3>

        <form action="{{ route('organizer.events.tickets.store', $event->id) }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="name" class="block font-black text-xs uppercase mb-1">Nama Tipe Tiket *</label>
                    <input type="text" id="name" name="name" placeholder="Contoh: Early Bird / Regular / VIP" required
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                </div>

                <div>
                    <label for="price" class="block font-black text-xs uppercase mb-1">Harga Tiket (Rp) *</label>
                    <input type="number" id="price" name="price" value="0" min="0" required
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                    <span class="text-[10px] text-zinc-500 font-bold">*Isi 0 untuk tiket gratis</span>
                </div>

                <div>
                    <label for="quota" class="block font-black text-xs uppercase mb-1">Batas Kuota Tiket *</label>
                    <input type="number" id="quota" name="quota" min="1" required
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_sale" class="block font-black text-xs uppercase mb-1">Mulai Penjualan *</label>
                    <input type="datetime-local" id="start_sale" name="start_sale" required
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                </div>

                <div>
                    <label for="end_sale" class="block font-black text-xs uppercase mb-1">Batas Akhir Penjualan *</label>
                    <input type="datetime-local" id="end_sale" name="end_sale" required
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                </div>
            </div>

            <button type="submit"
                class="bg-[#57EB74] hover:bg-[#45d461] border-2 border-black px-5 py-2.5 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                + Tambahkan Tipe Tiket
            </button>
        </form>
    </div>

    <!-- Existing Tickets Table -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <h3 class="font-black text-base uppercase border-b-2 border-black pb-2">Daftar Tiket Terkonfigurasi</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-black text-white font-mono uppercase text-[10px]">
                        <th class="p-3 border border-black">Tipe Tiket</th>
                        <th class="p-3 border border-black">Harga</th>
                        <th class="p-3 border border-black">Kuota / Sisa</th>
                        <th class="p-3 border border-black">Periode Jual</th>
                        <th class="p-3 border border-black">Status</th>
                        <th class="p-3 border border-black text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->tickets as $t)
                        <tr class="hover:bg-zinc-50 border-b border-black">
                            <td class="p-3 border border-black font-black text-sm">{{ $t->name }}</td>
                            <td class="p-3 border border-black font-mono">
                                @if($t->price == 0)
                                    <span class="bg-[#57EB74] border border-black px-1.5 py-0.5 text-[10px] font-black">GRATIS</span>
                                @else
                                    Rp {{ number_format($t->price, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="p-3 border border-black font-mono">
                                <span class="font-black">{{ $t->available_quota }}</span> / {{ $t->quota }} tiket
                            </td>
                            <td class="p-3 border border-black text-[11px]">
                                {{ $t->start_sale->format('d M') }} s/d {{ $t->end_sale->format('d M Y') }}
                            </td>
                            <td class="p-3 border border-black">
                                @if($t->isAvailable())
                                    <span class="bg-[#57EB74] text-black px-2 py-0.5 border border-black text-[10px] font-black uppercase">Aktif Dijual</span>
                                @elseif($t->isSoldOut())
                                    <span class="bg-[#FF70A6] text-black px-2 py-0.5 border border-black text-[10px] font-black uppercase">Habis</span>
                                @else
                                    <span class="bg-zinc-300 text-black px-2 py-0.5 border border-black text-[10px] font-black uppercase">Tutup</span>
                                @endif
                            </td>
                            <td class="p-3 border border-black text-center">
                                <form action="{{ route('organizer.events.tickets.destroy', ['event' => $event->id, 'ticket' => $t->id]) }}" method="POST" onsubmit="return confirm('Hapus tipe tiket ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-[#FF6B4A] text-white border border-black px-2 py-1 font-black text-[10px] hover:bg-red-600">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-zinc-500">Belum ada tipe tiket. Silakan buat minimal 1 tipe tiket di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
