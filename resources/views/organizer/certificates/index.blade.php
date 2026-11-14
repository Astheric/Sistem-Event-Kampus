@extends('layouts.dashboard')

@section('title', 'Penerbitan Sertifikat — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                    Penerbitan Digital (FR-13)
                </span>
                <span class="bg-danger text-white px-2 py-0.5 text-[10px] font-mono font-bold">BR-04 ENFORCED</span>
            </div>
            <h1 class="text-3xl font-bold">Penerbitan E-Sertifikat</h1>
            <p class="text-xs font-bold text-muted mt-1">{{ $event->title }}</p>
        </div>

        <div class="flex items-center gap-2">
            @if($stats['pending_issuance'] > 0)
                <form action="{{ route('organizer.certificates.generate_all', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-brand-600 text-white hover:bg-brand-700 border border-line px-4 py-2 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap rounded-full">
                        <x-icon name="zap" class="size-4" /> Terbitkan Semua Sertifikat ({{ $stats['pending_issuance'] }})
                    </button>
                </form>
            @endif
            <a href="{{ route('organizer.events.index') }}" class="bg-surface border border-line px-3 py-2 font-bold text-xs  shadow-card rounded-2xl">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 text-center">
        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-xs font-bold  text-muted">Peserta Hadir (ATTENDED)</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $stats['total_attended'] }}</div>
        </div>
        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-xs font-bold ">Sertifikat Terbit</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $stats['certificates_issued'] }}</div>
        </div>
        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-xs font-bold ">Belum Diterbitkan</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $stats['pending_issuance'] }}</div>
        </div>
    </div>

    <!-- Table of Attended Attendees -->
    <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <h3 class="font-bold text-base  border-b border-line pb-2">Peserta Terkonfirmasi Hadir</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-danger text-white font-mono  text-[10px]">
                        <th class="p-3 border border-line">Nama Peserta</th>
                        <th class="p-3 border border-line">Email</th>
                        <th class="p-3 border border-line">Tipe Tiket</th>
                        <th class="p-3 border border-line">Waktu Check-in</th>
                        <th class="p-3 border border-line">Nomor Sertifikat</th>
                        <th class="p-3 border border-line text-center">Status & Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendedRegistrations as $reg)
                        <tr class="hover:bg-surface-soft border-b border-line">
                            <td class="p-3 border border-line font-bold text-sm">{{ $reg->user->name }}</td>
                            <td class="p-3 border border-line font-mono">{{ $reg->user->email }}</td>
                            <td class="p-3 border border-line">{{ $reg->ticket->name }}</td>
                            <td class="p-3 border border-line font-mono text-[10px]">
                                {{ $reg->checkin ? $reg->checkin->checked_in_at->format('d M H:i') : 'Manual' }}
                            </td>
                            <td class="p-3 border border-line font-mono font-bold">
                                @if($reg->certificate)
                                    <span class="bg-surface border border-line px-2 py-0.5 text-xs">
                                        {{ $reg->certificate->certificate_number }}
                                    </span>
                                @else
                                    <span class="text-muted">Belum Terbit</span>
                                @endif
                            </td>
                            <td class="p-3 border border-line text-center">
                                @if($reg->certificate)
                                    <a href="{{ route('participant.certificates.download', $reg->certificate->id) }}"
                                        class="bg-brand-600 text-white hover:bg-brand-600 text-white border border-line px-2.5 py-1 font-bold text-[10px]  shadow-card active:translate-x-[1px] active:translate-y-[1px] inline-block rounded-full">
                                        <x-icon name="download" class="size-4" /> Unduh PDF
                                    </a>
                                @else
                                    <form action="{{ route('organizer.certificates.generate', $reg->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-brand-600 text-white hover:bg-brand-700 border border-line px-2.5 py-1 font-bold text-[10px]  shadow-card active:translate-x-[1px] active:translate-y-[1px] rounded-full">
                                            <x-icon name="zap" class="size-4" /> Terbitkan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-muted">
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
