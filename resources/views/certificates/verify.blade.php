@extends('layouts.app')

@section('title', 'Portal Verifikasi Sertifikat Publik — EventHub')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center space-y-3 mb-8">
        <span class="bg-surface border border-line px-3 py-1 text-xs font-bold  tracking-wider shadow-card rounded-2xl">
            Portal Publik Bebas Login
        </span>
        <h1 class="text-3xl sm:text-5xl font-bold">Verifikasi Keaslian Sertifikat</h1>
        <p class="text-sm font-bold text-muted max-w-lg mx-auto">
            Cek validitas nomor seri e-sertifikat kegiatan kampus untuk memastikan keaslian data penerima dan penyelenggara.
        </p>
    </div>

    <!-- Search / Verify Form -->
    <div class="bg-surface border border-line p-6 sm:p-8 shadow-card mb-8 rounded-2xl">
        <form action="{{ route('certificates.verify') }}" method="GET" class="space-y-4">
            <div>
                <label for="code" class="block font-bold text-xs  mb-1">Nomor Seri Sertifikat (Certificate ID)</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" id="code" name="code" value="{{ $code }}" required
                        placeholder="Contoh: CERT-2026-908123"
                        class="w-full bg-surface border border-line p-3 font-mono font-bold text-base  focus:outline-none focus:bg-surface focus:shadow-card transition-all rounded-2xl">
                    <button type="submit"
                        class="bg-brand-600 text-white hover:bg-brand-700 border border-line px-6 py-3 font-bold text-sm  tracking-wider shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all whitespace-nowrap rounded-full">
                        <x-icon name="search" class="size-4" /> Verifikasi
                    </button>
                </div>
            </div>
            <p class="text-[11px] font-bold text-muted">
                *Tip: Anda juga dapat memindai QR Code yang tertera pada bagian bawah dokumen sertifikat fisik/PDF.
            </p>
        </form>
    </div>

    <!-- Result Box -->
    @if($searched)
        @if($certificate)
            <!-- Valid Result Card -->
            <div class="bg-surface border border-line shadow-card overflow-hidden rounded-2xl">
                <div class="bg-surface border-b border-line p-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl"><x-icon name="shield" class="size-4" /></span>
                        <div>
                            <span class="text-[10px] font-bold  tracking-widest text-muted">HASIL VERIFIKASI RESMI</span>
                            <h2 class="text-xl sm:text-2xl font-bold">SERTIFIKAT VALID & TERDAFTAR</h2>
                        </div>
                    </div>
                    <span class="bg-danger text-white border border-line px-3 py-1 font-mono font-bold text-xs">
                        AUTHENTIC
                    </span>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold">
                        <div class="bg-surface border border-line p-3">
                            <span class="text-[10px]  text-muted font-bold block">Nama Penerima Sertifikat</span>
                            <p class="font-bold text-base text-black mt-0.5">{{ $certificate->registration->user->name }}</p>
                            <span class="text-muted font-mono">{{ $certificate->registration->user->email }}</span>
                        </div>

                        <div class="bg-surface border border-line p-3">
                            <span class="text-[10px]  text-muted font-bold block">Nomor Seri Sertifikat</span>
                            <p class="font-mono font-bold text-base text-ink mt-0.5">{{ $certificate->certificate_number }}</p>
                            <span class="text-muted">Diterbitkan: {{ $certificate->issued_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-line pt-4 space-y-3 text-xs font-bold">
                        <div>
                            <span class="text-[10px]  text-muted font-bold block">Nama Event Kampus</span>
                            <h3 class="font-bold text-lg text-black mt-0.5">{{ $certificate->registration->event->title }}</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <span class="text-[10px]  text-muted font-bold block">Penyelenggara</span>
                                <p class="font-bold">{{ $certificate->registration->event->organization->name }}</p>
                            </div>
                            <div>
                                <span class="text-[10px]  text-muted font-bold block">Status Kehadiran di Lapangan</span>
                                <span class="bg-surface border border-line px-2 py-0.5 text-xs font-bold inline-block mt-0.5">
                                    HADIR & TERVERIFIKASI
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-surface-soft border-t border-line p-4 text-center text-xs font-bold text-muted">
                    Dokumen ini sah dikeluarkan oleh Sistem Pengelolaan Event Kampus (EventHub) terintegrasi.
                </div>
            </div>
        @else
            <!-- Invalid Result Card -->
            <div class="bg-surface border border-line p-8 shadow-card text-center space-y-4 rounded-2xl">
                <div class="w-16 h-16 bg-surface border border-line flex items-center justify-center font-bold text-3xl mx-auto shadow-card rounded-2xl">
                    <x-icon name="x" class="size-4" />
                </div>
                <h2 class="text-2xl font-bold">SERTIFIKAT TIDAK DITEMUKAN</h2>
                <p class="text-xs font-bold text-muted max-w-md mx-auto">
                    Nomor seri <span class="font-mono font-bold bg-surface px-1 border border-line">{{ $code }}</span> tidak terdaftar dalam basis data resmi EventHub Kampus. Mohon pastikan kode yang diinput sesuai huruf besar dan angka.
                </p>
            </div>
        @endif
    @endif
</div>
@endsection
