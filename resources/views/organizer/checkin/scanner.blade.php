@extends('layouts.dashboard')

@section('title', 'Scanner QR Check-in: ' . $event->title)

@section('dashboard_content')
<div class="space-y-6" x-data="scannerApp()">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-ink">Pemindai tiket QR</h1>
            <p class="text-xs sm:text-sm text-muted mt-0.5">{{ $event->title }}</p>
        </div>

        <a href="{{ Auth::user()->isOrganizer() || Auth::user()->isAdmin() ? route('organizer.events.index') : route('organizer.committee.tasks') }}" class="btn btn-secondary text-xs py-2 px-3.5">
            Kembali
        </a>
    </div>

    <!-- Attendance Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted block">Tiket Terkonfirmasi</span>
            <div class="text-3xl font-extrabold tracking-tight text-ink mt-1.5">{{ number_format($stats['total_confirmed']) }}</div>
        </div>
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-semibold text-emerald-700 block">Total Kehadiran</span>
            <div class="text-3xl font-extrabold tracking-tight text-emerald-600 mt-1.5" x-text="attendedCount">{{ number_format($stats['total_attended']) }}</div>
        </div>
    </div>

    <!-- Scanner & Result Area -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Camera Viewport & Manual Form (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Camera Viewport Card -->
            <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-line/60">
                    <h3 class="font-bold text-base text-ink flex items-center gap-2">
                        <x-icon name="camera" class="size-4 text-brand-700" /> Pemindaian Kamera Langsung
                    </h3>
                    <span class="text-xs font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-md">AUTO SCAN</span>
                </div>

                <!-- Html5Qrcode Scanner Container -->
                <div id="reader" class="w-full bg-slate-900 rounded-xl overflow-hidden min-h-[260px]"></div>

                <p class="text-xs text-muted text-center">
                    Arahkan kamera perangkat ke kode QR pada E-Tiket peserta. Sistem memvalidasi kehadiran secara otomatis.
                </p>
            </div>

            <!-- Manual Ticket Code Fallback -->
            <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-3">
                <h3 class="font-bold text-base text-ink pb-2 border-b border-line/60 flex items-center gap-2">
                    <x-icon name="edit-2" class="size-4 text-slate-500" /> Fallback Input Kode Manual
                </h3>
                <form @submit.prevent="submitManualCode()" class="flex flex-col sm:flex-row gap-2.5">
                    <input type="text" x-model="manualCode" placeholder="Ketik kode tiket: EVT-2026-XXXXXX"
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2.5 font-mono text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                    <button type="submit" :disabled="loading"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand-700 px-5 py-2.5 text-xs font-semibold text-white hover:bg-brand-800 transition whitespace-nowrap">
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
                class="rounded-2xl border p-6 space-y-4 transition-all"
                :class="{
                    'bg-emerald-50/70 border-emerald-200 text-emerald-950': scanResult?.status === 'SUCCESS',
                    'bg-amber-50/70 border-amber-200 text-amber-950': scanResult?.status === 'ALREADY_CHECKED_IN',
                    'bg-red-50/70 border-red-200 text-red-950': scanResult?.status === 'INVALID' || scanResult?.status === 'UNPAID'
                }">

                <div class="flex items-center justify-between pb-2 border-b border-black/10">
                    <span class="text-xs font-bold uppercase tracking-wider">Hasil Pemindaian</span>
                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-md"
                          :class="{
                              'bg-emerald-200 text-emerald-900': scanResult?.status === 'SUCCESS',
                              'bg-amber-200 text-amber-900': scanResult?.status === 'ALREADY_CHECKED_IN',
                              'bg-red-200 text-red-900': scanResult?.status === 'INVALID' || scanResult?.status === 'UNPAID'
                          }"
                          x-text="scanResult?.status"></span>
                </div>

                <div class="space-y-2">
                    <h3 class="text-xl font-extrabold leading-snug" x-text="scanResult?.message"></h3>

                    <template x-if="scanResult?.data">
                        <div class="bg-white/80 rounded-xl border border-black/5 p-4 space-y-1 text-xs mt-3">
                            <p class="text-slate-600">Nama: <span class="font-bold text-ink text-sm" x-text="scanResult?.data.name"></span></p>
                            <p class="text-slate-600">Kode: <span class="font-mono font-semibold text-ink" x-text="scanResult?.data.code"></span></p>
                            <p class="text-slate-600">Tipe Tiket: <span class="font-semibold text-ink" x-text="scanResult?.data.ticket"></span></p>
                            <p class="text-slate-600">Waktu Scan: <span class="font-mono text-ink" x-text="scanResult?.data.time"></span></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Recent Scans Feed -->
            <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-3">
                <h3 class="font-bold text-base text-ink pb-2 border-b border-line/60">Riwayat Check-in Terkini</h3>
                <div class="space-y-2 max-h-80 overflow-y-auto">
                    <template x-for="item in recentLogs" :key="item.id">
                        <div class="p-3 rounded-xl bg-surface-soft/80 border border-line/40 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-semibold text-ink block" x-text="item.name"></span>
                                <span class="font-mono text-xs text-muted" x-text="item.code"></span>
                            </div>
                            <span class="font-mono text-xs text-muted bg-white px-2 py-0.5 rounded border border-line/60" x-text="item.time"></span>
                        </div>
                    </template>

                    @foreach($recentCheckins as $check)
                        <div class="p-3 rounded-xl bg-surface-soft/80 border border-line/40 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-semibold text-ink block">{{ $check->registration->user->name }}</span>
                                <span class="font-mono text-xs text-muted">{{ $check->registration->registration_code }}</span>
                            </div>
                            <span class="font-mono text-xs text-muted bg-white px-2 py-0.5 rounded border border-line/60">
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
