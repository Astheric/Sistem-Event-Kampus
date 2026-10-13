@extends('layouts.dashboard')

@section('title', 'Penerbitan Sertifikat — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-[#FF70A6] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                    Penerbitan Digital (FR-13)
                </span>
                <span class="bg-black text-white px-2 py-0.5 text-[10px] font-mono font-black">BR-04 ENFORCED</span>
            </div>
            <h1 class="text-3xl font-black">Penerbitan E-Sertifikat</h1>
            <p class="text-xs font-bold text-zinc-600 mt-1">{{ $event->title }}</p>
        </div>

        <div class="flex items-center gap-2">
            @if($stats['pending_issuance'] > 0)
                <form action="{{ route('organizer.certificates.generate_all', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-[#57EB74] hover:bg-[#43d960] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap">
                        ⚡ Terbitkan Semua Sertifikat ({{ $stats['pending_issuance'] }})
                    </button>
                </form>
            @endif
            <a href="{{ route('organizer.events.index') }}" class="bg-white border-2 border-black px-3 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 text-center">
        <div class="bg-white border-3 border-black p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-xs font-black uppercase text-zinc-500">Peserta Hadir (ATTENDED)</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $stats['total_attended'] }}</div>
        </div>
        <div class="bg-[#57EB74] border-3 border-black p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-xs font-black uppercase">Sertifikat Terbit</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $stats['certificates_issued'] }}</div>
        </div>
        <div class="bg-[#FFE600] border-3 border-black p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-xs font-black uppercase">Belum Diterbitkan</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $stats['pending_issuance'] }}</div>
        </div>
    </div>

    <!-- Table of Attended Attendees -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <h3 class="font-black text-base uppercase border-b-2 border-black pb-2">Peserta Terkonfirmasi Hadir</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-black text-white font-mono uppercase text-[10px]">
                        <th class="p-3 border border-black">Nama Peserta</th>
                        <th class="p-3 border border-black">Email</th>
                        <th class="p-3 border border-black">Tipe Tiket</th>
                        <th class="p-3 border border-black">Waktu Check-in</th>
                        <th class="p-3 border border-black">Nomor Sertifikat</th>
                        <th class="p-3 border border-black text-center">Status & Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendedRegistrations as $reg)
                        <tr class="hover:bg-zinc-50 border-b border-black">
                            <td class="p-3 border border-black font-black text-sm">{{ $reg->user->name }}</td>
                            <td class="p-3 border border-black font-mono">{{ $reg->user->email }}</td>
                            <td class="p-3 border border-black">{{ $reg->ticket->name }}</td>
                            <td class="p-3 border border-black font-mono text-[10px]">
                                {{ $reg->checkin ? $reg->checkin->checked_in_at->format('d M H:i') : 'Manual' }}
                            </td>
                            <td class="p-3 border border-black font-mono font-black">
                                @if($reg->certificate)
                                    <span class="bg-[#FFE600] border border-black px-2 py-0.5 text-xs">
                                        {{ $reg->certificate->certificate_number }}
                                    </span>
                                @else
                                    <span class="text-zinc-400">Belum Terbit</span>
                                @endif
                            </td>
                            <td class="p-3 border border-black text-center">
                                @if($reg->certificate)
                                    <a href="{{ route('participant.certificates.download', $reg->certificate->id) }}"
                                        class="bg-[#57EB74] hover:bg-[#43d960] border border-black px-2.5 py-1 font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] inline-block">
                                        📥 Unduh PDF
                                    </a>
                                @else
                                    <form action="{{ route('organizer.certificates.generate', $reg->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-[#FFE600] hover:bg-[#ffd700] border border-black px-2.5 py-1 font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]">
                                            ⚡ Terbitkan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-zinc-500">
                                Belum ada peserta yang hadir. Peserta harus check-in terlebih dahulu sebelum sertifikat dapat diterbitkan (BR-04).
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
