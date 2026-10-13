@extends('layouts.app')

@section('title', 'Portal Verifikasi Sertifikat Publik — EventHub')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center space-y-3 mb-8">
        <span class="bg-[#57EB74] border-2 border-black px-3 py-1 text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000]">
            Portal Publik Bebas Login
        </span>
        <h1 class="text-3xl sm:text-5xl font-black">Verifikasi Keaslian Sertifikat</h1>
        <p class="text-sm font-bold text-zinc-600 max-w-lg mx-auto">
            Cek validitas nomor seri e-sertifikat kegiatan kampus untuk memastikan keaslian data penerima dan penyelenggara.
        </p>
    </div>

    <!-- Search / Verify Form -->
    <div class="bg-white border-4 border-black p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000] mb-8">
        <form action="{{ route('certificates.verify') }}" method="GET" class="space-y-4">
            <div>
                <label for="code" class="block font-black text-xs uppercase mb-1">Nomor Seri Sertifikat (Certificate ID)</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" id="code" name="code" value="{{ $code }}" required
                        placeholder="Contoh: CERT-2026-908123"
                        class="w-full bg-[#FFFDF8] border-3 border-black p-3 font-mono font-black text-base uppercase focus:outline-none focus:bg-[#FFE600] focus:shadow-[4px_4px_0px_0px_#000] transition-all">
                    <button type="submit"
                        class="bg-[#FFE600] hover:bg-[#ffd700] border-3 border-black px-6 py-3 font-black text-sm uppercase tracking-wider shadow-[4px_4px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all whitespace-nowrap">
                        🔍 Verifikasi
                    </button>
                </div>
            </div>
            <p class="text-[11px] font-bold text-zinc-500">
                *Tip: Anda juga dapat memindai QR Code yang tertera pada bagian bawah dokumen sertifikat fisik/PDF.
            </p>
        </form>
    </div>

    <!-- Result Box -->
    @if($searched)
        @if($certificate)
            <!-- Valid Result Card -->
            <div class="bg-white border-4 border-black shadow-[10px_10px_0px_0px_#000] overflow-hidden">
                <div class="bg-[#57EB74] border-b-4 border-black p-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🛡️</span>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-zinc-800">HASIL VERIFIKASI RESMI</span>
                            <h2 class="text-xl sm:text-2xl font-black">SERTIFIKAT VALID & TERDAFTAR</h2>
                        </div>
                    </div>
                    <span class="bg-black text-white border-2 border-black px-3 py-1 font-mono font-black text-xs">
                        AUTHENTIC
                    </span>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold">
                        <div class="bg-[#FFFDF8] border-2 border-black p-3">
                            <span class="text-[10px] uppercase text-zinc-500 font-black block">Nama Penerima Sertifikat</span>
                            <p class="font-black text-base text-black mt-0.5">{{ $certificate->registration->user->name }}</p>
                            <span class="text-zinc-500 font-mono">{{ $certificate->registration->user->email }}</span>
                        </div>

                        <div class="bg-[#FFFDF8] border-2 border-black p-3">
                            <span class="text-[10px] uppercase text-zinc-500 font-black block">Nomor Seri Sertifikat</span>
                            <p class="font-mono font-black text-base text-[#000] mt-0.5">{{ $certificate->certificate_number }}</p>
                            <span class="text-zinc-500">Diterbitkan: {{ $certificate->issued_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="border-t-3 border-dashed border-black pt-4 space-y-3 text-xs font-bold">
                        <div>
                            <span class="text-[10px] uppercase text-zinc-500 font-black block">Nama Event Kampus</span>
                            <h3 class="font-black text-lg text-black mt-0.5">{{ $certificate->registration->event->title }}</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <span class="text-[10px] uppercase text-zinc-500 font-black block">Penyelenggara</span>
                                <p class="font-bold">{{ $certificate->registration->event->organization->name }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase text-zinc-500 font-black block">Status Kehadiran di Lapangan</span>
                                <span class="bg-[#00F0FF] border border-black px-2 py-0.5 text-xs font-black inline-block mt-0.5">
                                    HADIR & TERVERIFIKASI
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-zinc-100 border-t-3 border-black p-4 text-center text-xs font-bold text-zinc-600">
                    Dokumen ini sah dikeluarkan oleh Sistem Pengelolaan Event Kampus (EventHub) terintegrasi.
                </div>
            </div>
        @else
            <!-- Invalid Result Card -->
            <div class="bg-white border-4 border-black p-8 shadow-[8px_8px_0px_0px_#000] text-center space-y-4">
                <div class="w-16 h-16 bg-[#FF70A6] border-3 border-black flex items-center justify-center font-black text-3xl mx-auto shadow-[4px_4px_0px_0px_#000]">
                    ✕
                </div>
                <h2 class="text-2xl font-black">SERTIFIKAT TIDAK DITEMUKAN</h2>
                <p class="text-xs font-bold text-zinc-600 max-w-md mx-auto">
                    Nomor seri <span class="font-mono font-black bg-[#FFE600] px-1 border border-black">{{ $code }}</span> tidak terdaftar dalam basis data resmi EventHub Kampus. Mohon pastikan kode yang diinput sesuai huruf besar dan angka.
                </p>
            </div>
        @endif
    @endif
</div>
@endsection
