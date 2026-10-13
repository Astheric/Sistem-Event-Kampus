@extends('layouts.dashboard')

@section('title', 'Scanner QR Check-in — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6" x-data="scannerApp()">
    <!-- Header -->
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-[#57EB74] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                    Kamera Browser Real-time
                </span>
                <span class="bg-black text-white px-2 py-0.5 text-[10px] font-mono font-black">
                    ANTI-DOUBLE CHECK-IN
                </span>
            </div>
            <h1 class="text-3xl font-black">Scanner QR Check-in</h1>
            <p class="text-xs font-bold text-zinc-600 mt-1">{{ $event->title }}</p>
        </div>

        <a href="{{ route('organizer.events.index') }}" class="bg-white border-2 border-black px-3 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
            ← Kembali
        </a>
    </div>

    <!-- Attendance Stats -->
    <div class="grid grid-cols-2 gap-4 text-center">
        <div class="bg-white border-3 border-black p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-xs font-black uppercase text-zinc-500">Tiket Terkonfirmasi</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $stats['total_confirmed'] }}</div>
        </div>
        <div class="bg-[#57EB74] border-3 border-black p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-xs font-black uppercase">Kehadiran (Hadir)</span>
            <div class="text-3xl font-black font-mono mt-1" x-text="attendedCount">{{ $stats['total_attended'] }}</div>
        </div>
    </div>

    <!-- Scanner & Result Area -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Camera Viewport & Manual Form (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Camera Viewport Card -->
            <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
                <div class="flex items-center justify-between border-b-2 border-black pb-2">
                    <h3 class="font-black text-base uppercase">📷 Pemindaian Kamera Langsung</h3>
                    <span class="text-[10px] font-mono font-bold bg-[#00F0FF] border border-black px-1.5 py-0.5">AUTO SCAN</span>
                </div>

                <!-- Html5Qrcode Scanner Container -->
                <div id="reader" class="w-full bg-[#FFFDF8] border-3 border-black overflow-hidden min-h-[260px]"></div>

                <p class="text-xs font-bold text-zinc-500 text-center">
                    Arahkan kamera ke kode QR pada E-Tiket peserta. Sistem akan memvalidasi status secara otomatis.
                </p>
            </div>

            <!-- Manual Ticket Code Fallback (FR-10) -->
            <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-3">
                <h3 class="font-black text-base uppercase border-b-2 border-black pb-2">⌨️ Fallback Input Kode Manual</h3>
                <form @submit.prevent="submitManualCode()" class="flex flex-col sm:flex-row gap-2">
                    <input type="text" x-model="manualCode" placeholder="Ketik kode: EVT-2026-XXXXXX"
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-mono font-black text-sm uppercase focus:outline-none focus:bg-[#FFE600]">
                    <button type="submit" :disabled="loading"
                        class="bg-[#FFE600] hover:bg-[#ffd700] border-2 border-black px-5 py-2.5 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap">
                        <span x-show="!loading">Check-in</span>
                        <span x-show="loading">Proses...</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Real-Time Feedback Card (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Dynamic Result Card -->
            <div x-show="scanResult" x-cloak
                class="border-4 border-black p-6 shadow-[8px_8px_0px_0px_#000] space-y-4 transition-all"
                :class="{
                    'bg-[#57EB74]': scanResult?.status === 'SUCCESS',
                    'bg-[#FFE600]': scanResult?.status === 'ALREADY_CHECKED_IN',
                    'bg-[#FF70A6]': scanResult?.status === 'INVALID' || scanResult?.status === 'UNPAID'
                }">

                <div class="flex items-center justify-between border-b-3 border-black pb-2">
                    <span class="text-xs font-black uppercase tracking-wider font-mono">STATUS HASIL:</span>
                    <span class="bg-black text-white px-2 py-0.5 text-xs font-mono font-black" x-text="scanResult?.status"></span>
                </div>

                <div class="space-y-2">
                    <h3 class="text-2xl font-black leading-tight" x-text="scanResult?.message"></h3>

                    <template x-if="scanResult?.data">
                        <div class="bg-white border-2 border-black p-3 space-y-1 text-xs font-bold mt-3">
                            <p>Nama: <span class="font-black text-sm" x-text="scanResult?.data.name"></span></p>
                            <p>Kode: <span class="font-mono font-black" x-text="scanResult?.data.code"></span></p>
                            <p>Tipe Tiket: <span class="font-black" x-text="scanResult?.data.ticket"></span></p>
                            <p>Waktu Scan: <span class="font-mono" x-text="scanResult?.data.time"></span></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Recent Scans Feed -->
            <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-3">
                <h3 class="font-black text-base uppercase border-b-2 border-black pb-2">Riwayat Check-in Terkini</h3>
                <div class="space-y-2 max-h-80 overflow-y-auto">
                    <template x-for="item in recentLogs" :key="item.id">
                        <div class="p-2.5 border-2 border-black bg-zinc-50 flex items-center justify-between text-xs font-bold">
                            <div>
                                <span class="font-black block" x-text="item.name"></span>
                                <span class="font-mono text-[10px] text-zinc-500" x-text="item.code"></span>
                            </div>
                            <span class="font-mono text-[10px] bg-[#57EB74] px-1.5 py-0.5 border border-black" x-text="item.time"></span>
                        </div>
                    </template>

                    @foreach($recentCheckins as $check)
                        <div class="p-2.5 border-2 border-black bg-zinc-50 flex items-center justify-between text-xs font-bold">
                            <div>
                                <span class="font-black block">{{ $check->registration->user->name }}</span>
                                <span class="font-mono text-[10px] text-zinc-500">{{ $check->registration->registration_code }}</span>
                            </div>
                            <span class="font-mono text-[10px] bg-[#57EB74] px-1.5 py-0.5 border border-black">
                                {{ $check->checked_in_at->format('H:i:s') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function scannerApp() {
    return {
        attendedCount: {{ $stats['total_attended'] }},
        manualCode: '',
        loading: false,
        scanResult: null,
        recentLogs: [],
        lastScannedCode: null,
        lastScanTime: 0,

        init() {
            this.startCamera();
        },

        startCamera() {
            const html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            }, false);

            html5QrcodeScanner.render(
                (decodedText) => this.handleScan(decodedText),
                (errorMessage) => { /* ignore minor frame search errors */ }
            );
        },

        handleScan(code) {
            const now = Date.now();
            if (this.lastScannedCode === code && (now - this.lastScanTime < 4000)) {
                return; // Throttle duplicate reads within 4s
            }
            this.lastScannedCode = code;
            this.lastScanTime = now;
            this.sendCheckin(code);
        },

        submitManualCode() {
            if (!this.manualCode.trim()) return;
            this.sendCheckin(this.manualCode.trim());
            this.manualCode = '';
        },

        async sendCheckin(code) {
            this.loading = true;
            try {
                const response = await fetch("{{ route('organizer.checkin.scan', $event->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ code: code })
                });

                const data = await response.json();
                this.scanResult = data;

                if (data.success) {
                    this.playBeep(true);
                    this.attendedCount++;
                    this.recentLogs.unshift({
                        id: Date.now(),
                        name: data.data.name,
                        code: data.data.code,
                        time: data.data.time
                    });
                } else {
                    this.playBeep(false);
                }
            } catch (err) {
                this.scanResult = {
                    status: 'INVALID',
                    message: 'Koneksi bermasalah: ' + err.message
                };
                this.playBeep(false);
            } finally {
                this.loading = false;
            }
        },

        playBeep(isSuccess) {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);

                if (isSuccess) {
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime); // High pitched pleasant beep
                    gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.2);
                } else {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(220, audioCtx.currentTime); // Low warning buzz
                    gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.4);
                }
            } catch (e) {
                // Ignore audio context block if user hasn't interacted
            }
        }
    }
}
</script>
@endpush
@endsection
