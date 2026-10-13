@extends('layouts.dashboard')

@section('title', 'Manajemen Peserta — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6" x-data="{ modalOpen: false, activePayment: null, activeRegistration: null }">
    <!-- Header -->
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="bg-[#4DEEEA] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                Data Peserta & Transaksi
            </span>
            <h1 class="text-3xl font-black mt-2">Daftar Peserta Event</h1>
            <p class="text-xs font-bold text-zinc-600 mt-1">{{ $event->title }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('organizer.participants.export', $event->id) }}"
                class="bg-[#57EB74] hover:bg-[#42d45f] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                📥 Ekspor Data CSV
            </a>
            <a href="{{ route('organizer.events.index') }}" class="bg-white border-2 border-black px-3 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
        <div class="bg-white border-3 border-black p-3 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] uppercase font-black text-zinc-500">Total Pendaftar</span>
            <div class="text-2xl font-black font-mono mt-0.5">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-[#57EB74] border-3 border-black p-3 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] uppercase font-black">Terkonfirmasi</span>
            <div class="text-2xl font-black font-mono mt-0.5">{{ $stats['confirmed'] }}</div>
        </div>
        <div class="bg-[#FFE600] border-3 border-black p-3 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] uppercase font-black">Menunggu Bayar</span>
            <div class="text-2xl font-black font-mono mt-0.5">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-[#00F0FF] border-3 border-black p-3 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] uppercase font-black">Telah Check-in</span>
            <div class="text-2xl font-black font-mono mt-0.5">{{ $stats['attended'] }}</div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <form action="{{ route('organizer.participants.index', $event->id) }}" method="GET"
        class="bg-white border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000] flex flex-wrap items-center gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, kode..."
            class="bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs flex-grow focus:outline-none focus:bg-[#FFE600]">

        <select name="status" class="bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none">
            <option value="">Semua Status</option>
            <option value="CONFIRMED" {{ request('status') === 'CONFIRMED' ? 'selected' : '' }}>CONFIRMED (Aktif)</option>
            <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>PENDING (Menunggu)</option>
            <option value="ATTENDED" {{ request('status') === 'ATTENDED' ? 'selected' : '' }}>ATTENDED (Hadir)</option>
            <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>CANCELLED (Batal)</option>
        </select>

        <select name="ticket_id" class="bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none">
            <option value="">Semua Tipe Tiket</option>
            @foreach($tickets as $t)
                <option value="{{ $t->id }}" {{ request('ticket_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-[#FFE600] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
            Filter
        </button>
    </form>

    <!-- Table -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-black text-white font-mono uppercase text-[10px]">
                        <th class="p-3 border border-black">Kode</th>
                        <th class="p-3 border border-black">Nama Peserta</th>
                        <th class="p-3 border border-black">Tipe Tiket</th>
                        <th class="p-3 border border-black">Biaya</th>
                        <th class="p-3 border border-black">Status Tiket</th>
                        <th class="p-3 border border-black">Pembayaran</th>
                        <th class="p-3 border border-black">Kehadiran</th>
                        <th class="p-3 border border-black text-center">Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-zinc-50 border-b border-black">
                            <td class="p-3 border border-black font-mono font-black">{{ $reg->registration_code }}</td>
                            <td class="p-3 border border-black">
                                <p class="font-black text-sm">{{ $reg->user->name }}</p>
                                <span class="text-zinc-500 font-mono text-[10px]">{{ $reg->user->email }}</span>
                            </td>
                            <td class="p-3 border border-black">{{ $reg->ticket->name }}</td>
                            <td class="p-3 border border-black font-mono">
                                @if($reg->ticket->price == 0)
                                    <span class="text-[#2e9c45]">Gratis</span>
                                @else
                                    Rp {{ number_format($reg->ticket->price, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="p-3 border border-black">
                                <span class="px-2 py-0.5 border border-black text-[10px] font-black uppercase {{ $reg->status === 'CONFIRMED' ? 'bg-[#57EB74]' : ($reg->status === 'ATTENDED' ? 'bg-[#00F0FF]' : ($reg->status === 'PENDING' ? 'bg-[#FFE600]' : 'bg-[#FF70A6]')) }}">
                                    {{ $reg->status }}
                                </span>
                            </td>
                            <td class="p-3 border border-black">
                                @if($reg->payment)
                                    <span class="block text-[10px] font-bold">{{ $reg->payment->payment_method }}</span>
                                    <span class="px-1.5 py-0.2 border border-black text-[9px] font-black uppercase {{ $reg->payment->isVerified() ? 'bg-[#57EB74]' : ($reg->payment->isPending() ? 'bg-[#FFE600]' : 'bg-[#FF70A6]') }}">
                                        {{ $reg->payment->status }}
                                    </span>
                                @else
                                    <span class="text-zinc-400 font-mono">-</span>
                                @endif
                            </td>
                            <td class="p-3 border border-black">
                                @if($reg->checkin)
                                    <span class="text-[#2e9c45] font-black">✓ Hadir</span>
                                    <span class="text-[10px] text-zinc-500 font-mono block">{{ $reg->checkin->checked_in_at->format('H:i') }} WIB</span>
                                @else
                                    <span class="text-zinc-400">Belum Check-in</span>
                                @endif
                            </td>
                            <td class="p-3 border border-black text-center">
                                @if($reg->payment && $reg->payment->isPending())
                                    <button @click="activePayment = {{ json_encode($reg->payment) }}; activeRegistration = {{ json_encode($reg) }}; modalOpen = true;"
                                        class="bg-[#FFE600] border border-black px-2.5 py-1 font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]">
                                        Verifikasi 🔍
                                    </button>
                                @else
                                    <span class="text-zinc-400 text-[10px] font-mono">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-zinc-500">Tidak ada peserta yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $registrations->links() }}</div>
    </div>

    <!-- Verification Modal (Neo-Brutalism Pop-up) -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div @click.outside="modalOpen = false" class="bg-white border-4 border-black p-6 sm:p-8 max-w-lg w-full shadow-[10px_10px_0px_0px_#000] space-y-4">
            <div class="flex items-center justify-between border-b-3 border-black pb-3">
                <h3 class="font-black text-lg">Verifikasi Pembayaran Simulasi</h3>
                <button @click="modalOpen = false" class="border-2 border-black px-2 py-0.5 font-black text-xs">✕</button>
            </div>

            <div class="text-xs font-bold space-y-2 bg-[#FFFDF8] border-2 border-black p-4">
                <p>Kode Tiket: <span class="font-mono font-black" x-text="activeRegistration?.registration_code"></span></p>
                <p>Metode: <span class="font-black" x-text="activePayment?.payment_method"></span></p>
                <p>Nominal: <span class="font-black font-mono" x-text="'Rp ' + Number(activePayment?.amount || 0).toLocaleString('id-ID')"></span></p>
                <div x-show="activePayment?.proof_file" class="pt-2">
                    <span class="block uppercase text-[10px] text-zinc-500 mb-1">Bukti Transfer:</span>
                    <a :href="'/storage/' + activePayment?.proof_file" target="_blank" class="text-[#FF6B4A] underline font-black">
                        Lihat Gambar Bukti Transfer ↗
                    </a>
                </div>
            </div>

            <form :action="'/organizer/payments/' + (activePayment?.id || 0) + '/verify'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-black text-xs uppercase mb-1">Catatan Panitia (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Nominal sesuai / bukti sah..."
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="submit" name="action" value="reject"
                        class="bg-[#FF70A6] hover:bg-pink-400 border-2 border-black p-2.5 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                        ✕ Tolak Pembayaran
                    </button>

                    <button type="submit" name="action" value="approve"
                        class="bg-[#57EB74] hover:bg-[#43d960] border-2 border-black p-2.5 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                        ✓ Setujui (Konfirmasi)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
