@extends('layouts.dashboard')

@section('title', 'Dashboard Organizer')

@section('dashboard_content')
<div class="space-y-8">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-ink">Ringkasan penyelenggara</h1>
        </div>
        <a href="{{ route('organizer.events.create') }}"
            class="btn btn-primary text-xs py-2 px-3.5 whitespace-nowrap self-start sm:self-auto">
            + Buat event baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted block">Total Event</span>
            <div class="text-3xl font-extrabold tracking-tight text-ink mt-2">{{ number_format($totalEvents) }}</div>
            <span class="text-xs text-muted mt-1 block">Agenda terdaftar</span>
        </div>

        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted block">Total Registrasi</span>
            <div class="text-3xl font-extrabold tracking-tight text-ink mt-2">{{ number_format($totalRegistrations) }}</div>
            <span class="text-xs text-muted mt-1 block">Peserta mendaftar</span>
        </div>

        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted block">Peserta Hadir</span>
            <div class="text-3xl font-extrabold tracking-tight text-emerald-600 mt-2">{{ number_format($totalAttended) }}</div>
            <span class="text-xs text-muted mt-1 block">Check-in terverifikasi</span>
        </div>

        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted block">Simulasi Pendapatan</span>
            <div class="text-2xl font-extrabold tracking-tight text-ink mt-2 truncate">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <span class="text-xs text-muted mt-1 block">Tiket berbayar</span>
        </div>
    </div>

    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-line">
            <h3 class="font-bold text-base text-ink">Pendaftaran dan kehadiran per event</h3>
        </div>

        <div class="h-64 sm:h-72">
            <canvas id="organizerChart"></canvas>
        </div>
    </div>

    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-line">
            <h3 class="font-bold text-base text-ink">Pendaftaran terbaru</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-soft text-xs font-semibold text-muted uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-lg">Kode</th>
                        <th class="py-3 px-4">Peserta</th>
                        <th class="py-3 px-4">Event</th>
                        <th class="py-3 px-4">Tiket</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 rounded-r-lg">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/60">
                    @forelse($recentRegistrations as $reg)
                        @php
                            $statusBadge = match($reg->status) {
                                'CONFIRMED' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                'ATTENDED' => 'bg-brand-50 text-brand-700 border-brand-200/60',
                                'PENDING' => 'bg-amber-50 text-amber-800 border-amber-200/60',
                                'CANCELLED' => 'bg-red-50 text-red-700 border-red-200/60',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-surface-soft/60 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-semibold text-xs text-ink">{{ $reg->registration_code }}</td>
                            <td class="py-3.5 px-4 font-semibold text-ink">{{ $reg->user->name }}</td>
                            <td class="py-3.5 px-4 text-slate-600 truncate max-w-xs">{{ $reg->event->title }}</td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $reg->ticket->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold {{ $statusBadge }}">
                                    {{ $reg->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-muted">{{ $reg->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-muted">Belum ada pendaftaran masuk.</td>
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
                            backgroundColor: '#4823cd',
                            borderRadius: 6,
                        },
                        {
                            label: 'Peserta Hadir (Check-in)',
                            data: {!! json_encode($chartAttended) !!},
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { weight: '500', family: 'Plus Jakarta Sans' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: '500', family: 'Plus Jakarta Sans' } }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: { weight: '600', family: 'Plus Jakarta Sans' },
                                boxWidth: 14,
                                usePointStyle: true,
                                pointStyle: 'circle'
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
