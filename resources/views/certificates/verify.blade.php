@extends('layouts.app')

@section('title', 'Verifikasi Sertifikat : EventHub')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-12 space-y-6">
    <div class="text-center space-y-2">
        <h1 class="text-2xl sm:text-3xl font-bold text-ink">Verifikasi sertifikat</h1>
        <p class="text-sm text-muted max-w-md mx-auto">
            Masukkan nomor sertifikat untuk memeriksa keabsahan penerima dan kegiatan.
        </p>
    </div>

    {{-- Form --}}
    <div class="bg-surface border border-line p-6 rounded-xl">
        <form action="{{ route('certificates.verify') }}" method="GET" class="space-y-3">
            <div>
                <label for="code" class="block font-semibold text-xs text-ink mb-1.5">Nomor sertifikat</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" id="code" name="code" value="{{ $code }}" required
                        placeholder="Contoh: CERT-2026-908123"
                        class="w-full bg-surface border border-line px-3.5 py-2.5 rounded-lg font-mono text-sm text-ink focus:outline-none focus:border-brand-700">
                    <button type="submit"
                        class="btn btn-primary text-xs px-5 py-2.5 whitespace-nowrap">
                        <x-icon name="search" class="size-3.5" /> Cek sertifikat
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Result --}}
    @if($searched)
        @if($certificate)
            <div class="bg-surface border border-line rounded-xl overflow-hidden">
                <div class="bg-surface border-b border-line p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                            <x-icon name="check" class="size-4" />
                        </span>
                        <div>
                            <h2 class="text-base font-bold text-ink">SERTIFIKAT VALID & TERDAFTAR</h2>
                            <p class="text-xs text-muted font-mono">{{ $certificate->certificate_number }}</p>
                        </div>
                    </div>
                    <span class="inline-flex rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                        Terverifikasi
                    </span>
                </div>

                <div class="p-5 sm:p-6 space-y-4 text-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-lg border border-line bg-surface-soft p-3.5">
                            <span class="text-xs text-muted block">Nama penerima</span>
                            <p class="font-semibold text-ink mt-0.5">{{ $certificate->registration->user->name }}</p>
                            <span class="text-xs text-muted">{{ $certificate->registration->user->email }}</span>
                        </div>

                        <div class="rounded-lg border border-line bg-surface-soft p-3.5">
                            <span class="text-xs text-muted block">Tanggal terbit</span>
                            <p class="font-semibold text-ink mt-0.5">{{ $certificate->issued_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-line bg-surface-soft p-3.5 space-y-2">
                        <div>
                            <span class="text-xs text-muted block">Kegiatan</span>
                            <p class="font-semibold text-ink mt-0.5">{{ $certificate->registration->event->title }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-muted block">Penyelenggara</span>
                            <p class="font-semibold text-ink mt-0.5">{{ $certificate->registration->event->organization->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-surface border border-line p-8 text-center space-y-3 rounded-xl">
                <div class="size-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto">
                    <x-icon name="alert-circle" class="size-5" />
                </div>
                <h2 class="text-base font-bold text-ink">Sertifikat tidak ditemukan</h2>
                <p class="text-xs text-muted max-w-sm mx-auto">
                    Nomor seri <span class="font-mono font-semibold text-ink">{{ $code }}</span> tidak terdaftar dalam pangkalan data. Periksa kembali karakter dan format kode.
                </p>
            </div>
        @endif
    @endif
</div>
@endsection
