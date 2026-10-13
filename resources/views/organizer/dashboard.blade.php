@extends('layouts.dashboard')

@section('title', 'Dashboard Organizer — EventHub')

@section('dashboard_content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="bg-[#4DEEEA] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                Manajemen Penyelenggara
            </span>
            <h1 class="text-3xl font-black mt-2">Dashboard Organizer</h1>
            <p class="text-xs font-bold text-zinc-600 mt-1">
                Pantau performa event, status penjualan tiket, dan kehadiran real-time.
            </p>
        </div>
        <a href="{{ route('organizer.events.create') }}"
            class="bg-[#FFE600] hover:bg-[#ffd700] border-3 border-black px-5 py-2.5 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap">
            + Buat Event Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000]">
            <span class="text-xs font-black text-zinc-500 uppercase block">Total Event</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $totalEvents }}</div>
            <span class="text-[10px] font-bold text-zinc-500">Event terdaftar</span>
        </div>

        <div class="bg-[#FFE600] border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000]">
            <span class="text-xs font-black uppercase block">Total Registrasi</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $totalRegistrations }}</div>
            <span class="text-[10px] font-bold text-zinc-700">Pendaftar tiket</span>
        </div>

        <div class="bg-[#57EB74] border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000]">
            <span class="text-xs font-black uppercase block">Peserta Hadir</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $totalAttended }}</div>
            <span class="text-[10px] font-bold text-zinc-700">Check-in terverifikasi</span>
        </div>

        <div class="bg-[#00F0FF] border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000]">
            <span class="text-xs font-black uppercase block">Simulasi Pendapatan</span>
            <div class="text-2xl font-black font-mono mt-1 truncate">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <span class="text-[10px] font-bold text-zinc-700">Pembayaran terverifikasi</span>
        </div>
    </div>

    <!-- Chart.js Graph (FR-17) -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <div class="flex items-center justify-between border-b-2 border-black pb-2">
            <div>
                <h3 class="font-black text-lg">Statistik Pendaftaran vs Kehadiran per Event</h3>
                <p class="text-xs font-bold text-zinc-500">Grafik perbandingan kuota pemesanan tiket dengan scan kehadiran hari-H.</p>
            </div>
            <span class="bg-black text-white px-2 py-0.5 text-[10px] font-mono font-black">CHART ANALYTICS</span>
        </div>

        <div class="h-64 sm:h-72">
            <canvas id="organizerChart"></canvas>
        </div>
    </div>

    <!-- Recent Registrations Table -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <div class="flex items-center justify-between border-b-2 border-black pb-2">
            <h3 class="font-black text-lg">Pendaftaran Peserta Terbaru</h3>
            <span class="text-xs font-bold text-zinc-500">5 Transaksi Terakhir</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-black text-white uppercase text-[10px] font-mono">
                        <th class="p-3 border border-black">Kode</th>
                        <th class="p-3 border border-black">Peserta</th>
                        <th class="p-3 border border-black">Event</th>
                        <th class="p-3 border border-black">Tiket</th>
                        <th class="p-3 border border-black">Status</th>
                        <th class="p-3 border border-black">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRegistrations as $reg)
                        <tr class="hover:bg-zinc-50 border-b border-black">
                            <td class="p-3 border border-black font-mono font-black">{{ $reg->registration_code }}</td>
                            <td class="p-3 border border-black">{{ $reg->user->name }}</td>
                            <td class="p-3 border border-black truncate max-w-xs">{{ $reg->event->title }}</td>
                            <td class="p-3 border border-black">{{ $reg->ticket->name }}</td>
                            <td class="p-3 border border-black">
                                <span class="px-2 py-0.5 border border-black text-[10px] font-black uppercase {{ $reg->status === 'CONFIRMED' ? 'bg-[#57EB74]' : ($reg->status === 'ATTENDED' ? 'bg-[#00F0FF]' : 'bg-[#FFE600]') }}">
                                    {{ $reg->status }}
                                </span>
                            </td>
                            <td class="p-3 border border-black font-mono text-[10px] text-zinc-500">{{ $reg->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-zinc-500">Belum ada pendaftaran masuk.</td>
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
