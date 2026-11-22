@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('dashboard_content')
<div class="space-y-8">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-ink">Ringkasan sistem</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.moderation.index') }}" class="btn btn-primary text-xs py-2 px-3.5">
                    <x-icon name="check-circle" class="size-3.5" />
                    Antrean moderasi ({{ $metrics['pending_approvals'] }})
                </a>
            </div>
        </div>
    </div>

    <!-- Health Metrics Grid (FR-18) -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted">Total Pengguna</span>
            <div class="text-3xl font-extrabold tracking-tight text-ink mt-2">{{ number_format($metrics['total_users']) }}</div>
            <span class="text-xs text-muted mt-1 block">Civitas aktif</span>
        </div>

        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted">Total Event</span>
            <div class="text-3xl font-extrabold tracking-tight text-ink mt-2">{{ number_format($metrics['total_events']) }}</div>
            <span class="text-xs text-muted mt-1 block">Semua status</span>
        </div>

        <div class="bg-amber-50/50 rounded-2xl border border-amber-200/60 p-5">
            <span class="text-xs font-semibold text-amber-800">Antrean Moderasi</span>
            <div class="text-3xl font-extrabold tracking-tight text-amber-900 mt-2">{{ number_format($metrics['pending_approvals']) }}</div>
            <span class="text-xs text-amber-700 mt-1 block">Menunggu verifikasi</span>
        </div>

        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted">Total Pendaftaran</span>
            <div class="text-3xl font-extrabold tracking-tight text-ink mt-2">{{ number_format($metrics['total_registrations']) }}</div>
            <span class="text-xs text-muted mt-1 block">Tiket dipesan</span>
        </div>

        <div class="bg-surface rounded-2xl border border-line/60 p-5">
            <span class="text-xs font-medium text-muted">Sertifikat Terbit</span>
            <div class="text-3xl font-extrabold tracking-tight text-ink mt-2">{{ number_format($metrics['total_certificates']) }}</div>
            <span class="text-xs text-muted mt-1 block">Verifikasi hadir</span>
        </div>
    </div>

    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-line">
            <h3 class="font-bold text-base text-ink">Event menunggu persetujuan</h3>
            <a href="{{ route('admin.moderation.index') }}" class="font-semibold text-xs text-brand-700 hover:text-brand-800">
                Semua antrean
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-soft text-xs font-semibold text-muted uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-lg">Judul Event</th>
                        <th class="py-3 px-4">Penyelenggara</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Jadwal Acara</th>
                        <th class="py-3 px-4 text-center rounded-r-lg">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/60">
                    @forelse($pendingEvents as $event)
                        <tr class="hover:bg-surface-soft/60 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-ink">{{ $event->title }}</td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $event->organization->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-md bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">
                                    {{ $event->category->name }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-muted">
                                {{ $event->start_time->format('d M Y, H:i') }} WIB
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.moderation.approve', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                                            <x-icon name="check" class="size-3.5" /> Setujui
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.moderation.show', $event->id) }}"
                                        class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                                        Tinjau <x-icon name="search" class="size-3.5" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-muted">
                                <x-icon name="check-circle" class="size-8 mx-auto text-emerald-500 mb-2" />
                                <p class="text-sm font-medium text-ink">Tidak ada event yang menunggu persetujuan</p>
                                <p class="text-xs text-muted mt-1">Semua pengajuan event telah ditinjau.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-line">
            <h3 class="font-bold text-base text-ink">Log aktivitas terbaru</h3>
            <a href="{{ route('admin.audit_logs.index') }}" class="font-semibold text-xs text-brand-700 hover:text-brand-800">
                Semua log
            </a>
        </div>

        <div class="space-y-2.5">
            @forelse($recentLogs as $log)
                <div class="p-3.5 rounded-xl bg-surface-soft/60 border border-line/40 flex items-center justify-between text-sm">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center rounded-md bg-slate-200/80 px-2.5 py-1 text-xs font-semibold text-slate-700">
                            {{ $log->action }}
                        </span>
                        <div>
                            <span class="font-semibold text-ink">{{ $log->user ? $log->user->name : 'Sistem' }}</span>
                            <span class="text-xs text-muted ml-1.5">• {{ $log->entity_type }} #{{ $log->entity_id }}</span>
                        </div>
                    </div>
                    <span class="text-xs text-muted">{{ $log->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-sm text-muted py-6 text-center">Belum ada catatan log audit.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
