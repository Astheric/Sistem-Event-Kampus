@extends('layouts.dashboard')

@section('title', 'Penerbitan Sertifikat: ' . $event->title)
@section('page_title', 'Penerbitan sertifikat')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-ink">Penerbitan e-sertifikat</h1>
            <p class="text-xs sm:text-sm text-muted mt-0.5">{{ $event->title }}</p>
        </div>

        <div class="flex items-center gap-2.5">
            @if($stats['pending_issuance'] > 0)
                <form action="{{ route('organizer.certificates.generate_all', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition whitespace-nowrap">
                        <x-icon name="zap" class="size-3.5" /> Terbitkan Semua ({{ $stats['pending_issuance'] }})
                    </button>
                </form>
            @endif
            <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center rounded-lg bg-surface-soft px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted block">Peserta Hadir (ATTENDED)</span>
            <div class="text-2xl font-extrabold text-ink mt-1.5">{{ number_format($stats['total_attended']) }}</div>
        </div>
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-semibold text-emerald-700 block">Sertifikat Terbit</span>
            <div class="text-2xl font-extrabold text-emerald-600 mt-1.5">{{ number_format($stats['certificates_issued']) }}</div>
        </div>
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-semibold text-amber-800 block">Belum Diterbitkan</span>
            <div class="text-2xl font-extrabold text-amber-700 mt-1.5">{{ number_format($stats['pending_issuance']) }}</div>
        </div>
    </div>

    <!-- Table of Attended Attendees -->
    <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-4">
        <h3 class="font-bold text-base text-ink pb-2 border-b border-line/60">Peserta hadir</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-soft text-xs font-semibold text-muted uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-lg">Nama Peserta</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Tipe Tiket</th>
                        <th class="py-3 px-4">Waktu Check-in</th>
                        <th class="py-3 px-4">Nomor Sertifikat</th>
                        <th class="py-3 px-4 text-center rounded-r-lg">Status & Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/60">
                    @forelse($attendedRegistrations as $reg)
                        <tr class="hover:bg-surface-soft/60 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-ink">{{ $reg->user->name }}</td>
                            <td class="py-3.5 px-4 text-xs text-muted font-mono">{{ $reg->user->email }}</td>
                            <td class="py-3.5 px-4 text-xs text-slate-700">{{ $reg->ticket->name }}</td>
                            <td class="py-3.5 px-4 text-xs text-muted">
                                {{ $reg->checkin ? $reg->checkin->checked_in_at->format('d M H:i') : 'Manual' }}
                            </td>
                            <td class="py-3.5 px-4 text-xs font-mono">
                                @if($reg->certificate)
                                    <span class="inline-flex items-center rounded-md bg-brand-50 px-2 py-0.5 font-semibold text-brand-700">
                                        {{ $reg->certificate->certificate_number }}
                                    </span>
                                @else
                                    <span class="text-muted">Belum Terbit</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($reg->certificate)
                                    <a href="{{ route('participant.certificates.download', $reg->certificate->id) }}"
                                        class="inline-flex items-center gap-1 rounded-lg bg-surface-soft px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                                        <x-icon name="download" class="size-3.5" /> Unduh PDF
                                    </a>
                                @else
                                    <form action="{{ route('organizer.certificates.generate', $reg->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-lg bg-brand-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-800 transition">
                                            <x-icon name="zap" class="size-3.5" /> Terbitkan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-muted">
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
