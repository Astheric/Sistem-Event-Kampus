@extends('layouts.dashboard')

@section('title', 'Dashboard Organizer — EventHub')

@section('dashboard_content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl">
        <div>
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                Manajemen Penyelenggara
            </span>
            <h1 class="text-3xl font-bold mt-2">Dashboard Organizer</h1>
            <p class="text-xs font-bold text-muted mt-1">
                Pantau performa event, status penjualan tiket, dan kehadiran real-time.
            </p>
        </div>
        <a href="{{ route('organizer.events.create') }}"
            class="bg-brand-600 text-white hover:bg-brand-600 text-white border border-line px-5 py-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap rounded-full">
            + Buat Event Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-xs font-bold text-muted  block">Total Event</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $totalEvents }}</div>
            <span class="text-[10px] font-bold text-muted">Event terdaftar</span>
        </div>

        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-xs font-bold  block">Total Registrasi</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $totalRegistrations }}</div>
            <span class="text-[10px] font-bold text-muted">Pendaftar tiket</span>
        </div>

        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-xs font-bold  block">Peserta Hadir</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $totalAttended }}</div>
            <span class="text-[10px] font-bold text-muted">Check-in terverifikasi</span>
        </div>

        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-xs font-bold  block">Simulasi Pendapatan</span>
            <div class="text-2xl font-bold font-mono mt-1 truncate">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <span class="text-[10px] font-bold text-muted">Pembayaran terverifikasi</span>
        </div>
    </div>

    <!-- Chart.js Graph (FR-17) -->
    <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <div class="flex items-center justify-between border-b border-line pb-2">
            <div>
                <h3 class="font-bold text-lg">Statistik Pendaftaran vs Kehadiran per Event</h3>
                <p class="text-xs font-bold text-muted">Grafik perbandingan kuota pemesanan tiket dengan scan kehadiran hari-H.</p>
            </div>
            <span class="bg-danger text-white px-2 py-0.5 text-[10px] font-mono font-bold">CHART ANALYTICS</span>
        </div>

        <div class="h-64 sm:h-72">
            <canvas id="organizerChart"></canvas>
        </div>
    </div>

    <!-- Recent Registrations Table -->
    <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <div class="flex items-center justify-between border-b border-line pb-2">
            <h3 class="font-bold text-lg">Pendaftaran Peserta Terbaru</h3>
            <span class="text-xs font-bold text-muted">5 Transaksi Terakhir</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-danger text-white  text-[10px] font-mono">
                        <th class="p-3 border border-line">Kode</th>
                        <th class="p-3 border border-line">Peserta</th>
                        <th class="p-3 border border-line">Event</th>
                        <th class="p-3 border border-line">Tiket</th>
                        <th class="p-3 border border-line">Status</th>
                        <th class="p-3 border border-line">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRegistrations as $reg)
                        <tr class="hover:bg-surface-soft border-b border-line">
                            <td class="p-3 border border-line font-mono font-bold">{{ $reg->registration_code }}</td>
                            <td class="p-3 border border-line">{{ $reg->user->name }}</td>
                            <td class="p-3 border border-line truncate max-w-xs">{{ $reg->event->title }}</td>
                            <td class="p-3 border border-line">{{ $reg->ticket->name }}</td>
                            <td class="p-3 border border-line">
                                <span class="px-2 py-0.5 border border-line text-[10px] font-bold  {{ $reg->status === 'CONFIRMED' ? 'bg-surface' : ($reg->status === 'ATTENDED' ? 'bg-surface' : 'bg-surface') }}">
                                    {{ $reg->status }}
                                </span>
                            </td>
                            <td class="p-3 border border-line font-mono text-[10px] text-muted">{{ $reg->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-muted">Belum ada pendaftaran masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('organizerChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Total Pendaftar (Tiket)',
                            data: {!! json_encode($chartRegistrations) !!},
                            backgroundColor: '#FFE600',
                            borderColor: '#000000',
                            borderWidth: 2,
                            borderRadius: 0,
                        },
                        {
                            label: 'Peserta Hadir (Check-in)',
                            data: {!! json_encode($chartAttended) !!},
                            backgroundColor: '#57EB74',
                            borderColor: '#000000',
                            borderWidth: 2,
                            borderRadius: 0,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e5e7eb' },
                            ticks: { font: { weight: 'bold', family: 'Space Grotesk' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: 'bold', family: 'Space Grotesk' } }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: { weight: 'bold', family: 'Space Grotesk' },
                                boxWidth: 16,
                                borderColor: '#000000',
                                borderWidth: 1
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
