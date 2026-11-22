@extends('layouts.dashboard')

@section('title', 'E-Sertifikat Saya')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6">
        <h1 class="text-xl sm:text-2xl font-bold text-ink">Sertifikat saya</h1>
    </div>

    <!-- Attended Events / Certificates Cards -->
    <div class="space-y-4">
        @forelse($attendedRegistrations as $reg)
            <div class="bg-surface rounded-2xl border border-line/60 p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 transition hover:border-slate-300">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                            <x-icon name="check-circle" class="size-3.5" /> Terverifikasi Hadir
                        </span>
                        @if($reg->certificate)
                            <span class="font-mono text-xs font-semibold bg-brand-50 text-brand-700 px-2.5 py-0.5 rounded">
                                {{ $reg->certificate->certificate_number }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-ink">
                            <a href="{{ route('events.show', $reg->event->slug) }}" class="hover:text-brand-700 transition">
                                {{ $reg->event->title }}
                            </a>
                        </h3>
                        <p class="text-xs text-muted mt-1 font-medium">
                            Penyelenggara: <span class="text-ink font-semibold">{{ $reg->event->organization->name }}</span>
                        </p>
                    </div>

                    <div class="text-xs text-muted font-medium flex items-center gap-1.5">
                        <x-icon name="calendar" class="size-4 text-slate-400" /> Tanggal Event: {{ $reg->event->start_time->format('d M Y') }}
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row md:flex-col gap-2 min-w-[210px]">
                    @if($reg->certificate)
                        <a href="{{ route('participant.certificates.download', $reg->certificate->id) }}"
                            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
                            <x-icon name="download" class="size-3.5" /> Unduh Sertifikat PDF
                        </a>

                        <a href="{{ route('certificates.verify.show', $reg->certificate->certificate_number) }}" target="_blank"
                            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-surface-soft px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                            <x-icon name="search" class="size-3.5" /> Verifikasi sertifikat
                        </a>
                    @else
                        <form action="{{ route('participant.certificates.claim', $reg->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                                <x-icon name="book-open" class="size-3.5" /> Terbitkan / Klaim Sertifikat
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-surface rounded-2xl border border-line/60 p-12 text-center">
                <x-icon name="book-open" class="size-10 mx-auto text-slate-300 mb-2" />
                <h3 class="font-bold text-lg text-ink">Belum Ada Sertifikat Tersedia</h3>
                <p class="text-xs text-muted mt-1 max-w-md mx-auto">
                    Sertifikat akan otomatis dapat diklaim setelah Anda hadir dan panitia memindai tiket Anda di lokasi acara.
                </p>
                <a href="{{ route('participant.tickets.index') }}" class="inline-flex items-center gap-1.5 mt-4 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
                    Cek Tiket Saya
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
