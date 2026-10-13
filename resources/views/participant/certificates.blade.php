@extends('layouts.dashboard')

@section('title', 'E-Sertifikat Saya — EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000]">
        <div class="flex items-center gap-2 mb-2">
            <span class="bg-[#FF70A6] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                E-Sertifikat Digital
            </span>
        </div>
        <h1 class="text-3xl font-black">Sertifikat Kegiatan Kampus</h1>
        <p class="text-xs font-bold text-zinc-600 mt-1">
            Sertifikat resmi hanya diterbitkan untuk peserta yang telah terkonfirmasi hadir (ATTENDED) pada hari pelaksanaan event.
        </p>
    </div>

    <!-- Attended Events / Certificates Cards -->
    <div class="space-y-4">
        @forelse($attendedRegistrations as $reg)
            <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="bg-[#57EB74] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase">
                            ✓ TERVERIFIKASI HADIR
                        </span>
                        @if($reg->certificate)
                            <span class="font-mono text-xs font-black bg-[#FFE600] border border-black px-2 py-0.5">
                                {{ $reg->certificate->certificate_number }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-xl font-black">
                            <a href="{{ route('events.show', $reg->event->slug) }}" class="hover:underline">
                                {{ $reg->event->title }}
                            </a>
                        </h3>
                        <p class="text-xs font-bold text-zinc-500 mt-0.5">
                            Penyelenggara: {{ $reg->event->organization->name }}
                        </p>
                    </div>

                    <div class="text-xs font-bold text-zinc-600">
                        <span>📅 Tanggal Event: {{ $reg->event->start_time->format('d M Y') }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row md:flex-col gap-2 min-w-[200px]">
                    @if($reg->certificate)
                        <a href="{{ route('participant.certificates.download', $reg->certificate->id) }}"
                            class="text-center bg-[#57EB74] hover:bg-[#43d960] border-2 border-black py-2.5 px-4 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                            📥 Unduh Sertifikat PDF
                        </a>

                        <a href="{{ route('certificates.verify.show', $reg->certificate->certificate_number) }}" target="_blank"
                            class="text-center bg-[#00F0FF] hover:bg-[#00d8e6] border-2 border-black py-2 px-4 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                            🔍 Cek Verifikasi Publik ↗
                        </a>
                    @else
                        <form action="{{ route('participant.certificates.claim', $reg->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-[#FFE600] hover:bg-[#ffd700] border-2 border-black py-2.5 px-4 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                                🎓 Terbitkan / Klaim Sertifikat
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border-3 border-black p-12 text-center shadow-[4px_4px_0px_0px_#000]">
                <span class="text-4xl block mb-2">🎓</span>
                <h3 class="font-black text-lg">Belum Ada Sertifikat Tersedia</h3>
                <p class="text-xs font-bold text-zinc-500 mt-1">
                    Sertifikat akan otomatis dapat diklaim setelah Anda hadir dan panitia memindai tiket Anda di lokasi acara.
                </p>
                <a href="{{ route('participant.tickets.index') }}" class="inline-block mt-4 bg-[#FFE600] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
                    Cek Tiket Saya
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
