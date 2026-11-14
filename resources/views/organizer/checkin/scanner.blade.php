@extends('layouts.dashboard')

@section('title', 'Scanner QR Check-in — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6" x-data="scannerApp()">
    <!-- Header -->
    <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                    Kamera Browser Real-time
                </span>
                <span class="bg-danger text-white px-2 py-0.5 text-[10px] font-mono font-bold">
                    ANTI-DOUBLE CHECK-IN
                </span>
            </div>
            <h1 class="text-3xl font-bold">Scanner QR Check-in</h1>
            <p class="text-xs font-bold text-muted mt-1">{{ $event->title }}</p>
        </div>

        <a href="{{ route('organizer.events.index') }}" class="bg-surface border border-line px-3 py-2 font-bold text-xs  shadow-card rounded-2xl">
            ← Kembali
        </a>
    </div>

    <!-- Attendance Stats -->
    <div class="grid grid-cols-2 gap-4 text-center">
        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-xs font-bold  text-muted">Tiket Terkonfirmasi</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $stats['total_confirmed'] }}</div>
        </div>
        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-xs font-bold ">Kehadiran (Hadir)</span>
            <div class="text-3xl font-bold font-mono mt-1" x-text="attendedCount">{{ $stats['total_attended'] }}</div>
        </div>
    </div>

    <!-- Scanner & Result Area -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Camera Viewport & Manual Form (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Camera Viewport Card -->
            <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
                <div class="flex items-center justify-between border-b border-line pb-2">
                    <h3 class="font-bold text-base "><x-icon name="camera" class="size-4" /> Pemindaian Kamera Langsung</h3>
                    <span class="text-[10px] font-mono font-bold bg-surface border border-line px-1.5 py-0.5">AUTO SCAN</span>
                </div>

                <!-- Html5Qrcode Scanner Container -->
                <div id="reader" class="w-full bg-surface border border-line overflow-hidden min-h-[260px]"></div>

                <p class="text-xs font-bold text-muted text-center">
                    Arahkan kamera ke kode QR pada E-Tiket peserta. Sistem akan memvalidasi status secara otomatis.
                </p>
            </div>

            <!-- Manual Ticket Code Fallback (FR-10) -->
            <div class="bg-surface border border-line p-6 shadow-card space-y-3 rounded-2xl">
                <h3 class="font-bold text-base  border-b border-line pb-2"><x-icon name="edit-2" class="size-4" /> Fallback Input Kode Manual</h3>
                <form @submit.prevent="submitManualCode()" class="flex flex-col sm:flex-row gap-2">
                    <input type="text" x-model="manualCode" placeholder="Ketik kode: EVT-2026-XXXXXX"
                        class="w-full bg-surface border border-line p-2.5 font-mono font-bold text-sm  focus:outline-none focus:bg-surface">
                    <button type="submit" :disabled="loading"
                        class="bg-brand-600 text-white hover:bg-brand-600 text-white border border-line px-5 py-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap rounded-full">
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
                class="border border-line p-6 shadow-card space-y-4 transition-all rounded-2xl"
                :class="{
                    'bg-surface': scanResult?.status === 'SUCCESS',
                    'bg-surface': scanResult?.status === 'ALREADY_CHECKED_IN',
                    'bg-surface': scanResult?.status === 'INVALID' || scanResult?.status === 'UNPAID'
                }">

                <div class="flex items-center justify-between border-b border-line pb-2">
                    <span class="text-xs font-bold  tracking-wider font-mono">STATUS HASIL:</span>
                    <span class="bg-danger text-white px-2 py-0.5 text-xs font-mono font-bold" x-text="scanResult?.status"></span>
                </div>

                <div class="space-y-2">
                    <h3 class="text-2xl font-bold leading-tight" x-text="scanResult?.message"></h3>

                    <template x-if="scanResult?.data">
                        <div class="bg-surface border border-line p-3 space-y-1 text-xs font-bold mt-3">
                            <p>Nama: <span class="font-bold text-sm" x-text="scanResult?.data.name"></span></p>
                            <p>Kode: <span class="font-mono font-bold" x-text="scanResult?.data.code"></span></p>
                            <p>Tipe Tiket: <span class="font-bold" x-text="scanResult?.data.ticket"></span></p>
                            <p>Waktu Scan: <span class="font-mono" x-text="scanResult?.data.time"></span></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Recent Scans Feed -->
            <div class="bg-surface border border-line p-6 shadow-card space-y-3 rounded-2xl">
                <h3 class="font-bold text-base  border-b border-line pb-2">Riwayat Check-in Terkini</h3>
                <div class="space-y-2 max-h-80 overflow-y-auto">
                    <template x-for="item in recentLogs" :key="item.id">
                        <div class="p-2.5 border border-line bg-surface-soft flex items-center justify-between text-xs font-bold">
                            <div>
                                <span class="font-bold block" x-text="item.name"></span>
                                <span class="font-mono text-[10px] text-muted" x-text="item.code"></span>
                            </div>
                            <span class="font-mono text-[10px] bg-surface px-1.5 py-0.5 border border-line" x-text="item.time"></span>
                        </div>
                    </template>

                    @foreach($recentCheckins as $check)
                        <div class="p-2.5 border border-line bg-surface-soft flex items-center justify-between text-xs font-bold">
                            <div>
                                <span class="font-bold block">{{ $check->registration->user->name }}</span>
                                <span class="font-mono text-[10px] text-muted">{{ $check->registration->registration_code }}</span>
                            </div>
                            <span class="font-mono text-[10px] bg-surface px-1.5 py-0.5 border border-line">
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
