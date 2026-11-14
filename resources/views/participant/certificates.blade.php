@extends('layouts.dashboard')

@section('title', 'E-Sertifikat Saya — EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface border border-line p-6 shadow-card rounded-2xl">
        <div class="flex items-center gap-2 mb-2">
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                E-Sertifikat Digital
            </span>
        </div>
        <h1 class="text-3xl font-bold">Sertifikat Kegiatan Kampus</h1>
        <p class="text-xs font-bold text-muted mt-1">
            Sertifikat resmi hanya diterbitkan untuk peserta yang telah terkonfirmasi hadir (ATTENDED) pada hari pelaksanaan event.
        </p>
    </div>

    <!-- Attended Events / Certificates Cards -->
    <div class="space-y-4">
        @forelse($attendedRegistrations as $reg)
            <div class="bg-surface border border-line p-6 shadow-card flex flex-col md:flex-row md:items-center justify-between gap-6 rounded-2xl">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold ">
                            <x-icon name="check" class="size-4" /> TERVERIFIKASI HADIR
                        </span>
                        @if($reg->certificate)
                            <span class="font-mono text-xs font-bold bg-surface border border-line px-2 py-0.5">
                                {{ $reg->certificate->certificate_number }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-xl font-bold">
                            <a href="{{ route('events.show', $reg->event->slug) }}" class="hover:underline">
                                {{ $reg->event->title }}
                            </a>
                        </h3>
                        <p class="text-xs font-bold text-muted mt-0.5">
                            Penyelenggara: {{ $reg->event->organization->name }}
                        </p>
                    </div>

                    <div class="text-xs font-bold text-muted">
                        <span><x-icon name="calendar" class="size-4" /> Tanggal Event: {{ $reg->event->start_time->format('d M Y') }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row md:flex-col gap-2 min-w-[200px]">
                    @if($reg->certificate)
                        <a href="{{ route('participant.certificates.download', $reg->certificate->id) }}"
                            class="text-center bg-brand-600 text-white hover:bg-brand-600 text-white border border-line py-2.5 px-4 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                            <x-icon name="download" class="size-4" /> Unduh Sertifikat PDF
                        </a>

                        <a href="{{ route('certificates.verify.show', $reg->certificate->certificate_number) }}" target="_blank"
                            class="text-center bg-brand-600 text-white hover:bg-brand-600 text-white border border-line py-2 px-4 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                            <x-icon name="search" class="size-4" /> Cek Verifikasi Publik ↗
                        </a>
                    @else
                        <form action="{{ route('participant.certificates.claim', $reg->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-brand-600 text-white hover:bg-brand-700 border border-line py-2.5 px-4 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                                <x-icon name="book-open" class="size-4" /> Terbitkan / Klaim Sertifikat
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-surface border border-line p-12 text-center shadow-card rounded-2xl">
                <span class="text-4xl block mb-2"><x-icon name="book-open" class="size-4" /></span>
                <h3 class="font-bold text-lg">Belum Ada Sertifikat Tersedia</h3>
                <p class="text-xs font-bold text-muted mt-1">
                    Sertifikat akan otomatis dapat diklaim setelah Anda hadir dan panitia memindai tiket Anda di lokasi acara.
                </p>
                <a href="{{ route('participant.tickets.index') }}" class="inline-block mt-4 bg-surface border border-line px-4 py-2 font-bold text-xs  shadow-card rounded-2xl">
                    Cek Tiket Saya
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
