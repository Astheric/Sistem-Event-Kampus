@extends('layouts.dashboard')

@section('title', 'Audit Trail Log — Admin EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-surface border border-line p-6 shadow-card rounded-2xl">
        <div class="flex items-center gap-2 mb-1">
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                Transparansi Sistem (FR-20, BR-08)
            </span>
        </div>
        <h1 class="text-3xl font-bold">Audit Trail & Log Aktivitas</h1>
        <p class="text-xs font-bold text-muted mt-1">
            Riwayat otomatis setiap aksi krusial sistem: check-in QR, verifikasi pembayaran, moderasi event, dan penerbitan sertifikat.
        </p>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('admin.audit_logs.index') }}" method="GET"
        class="bg-surface border border-line p-4 shadow-card flex flex-wrap items-center gap-3 rounded-2xl">
        <select name="action" class="bg-surface border border-line p-2 font-bold text-xs focus:outline-none">
            <option value="">Semua Aksi (All Actions)</option>
            @foreach($actions as $act)
                <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-brand-600 text-white border border-line px-4 py-2 font-bold text-xs  shadow-card rounded-full">
            Filter Log
        </button>
    </form>

    <!-- Audit Logs Table -->
    <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-danger text-white font-mono  text-[10px]">
                        <th class="p-3 border border-line">Waktu</th>
                        <th class="p-3 border border-line">Pengguna</th>
                        <th class="p-3 border border-line">Aksi / Event</th>
                        <th class="p-3 border border-line">Target Entitas</th>
                        <th class="p-3 border border-line">Alamat IP</th>
                        <th class="p-3 border border-line">Rincian Metadata (JSON)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="hover:bg-surface-soft border-b border-line">
                            <td class="p-3 border border-line font-mono text-[11px]">
                                {{ $log->created_at->format('d M Y, H:i:s') }}
                            </td>
                            <td class="p-3 border border-line">
                                <span class="font-bold">{{ $log->user ? $log->user->name : 'Sistem / Anonim' }}</span>
                                @if($log->user)
                                    <span class="text-[10px] text-muted font-mono block">{{ $log->user->email }}</span>
                                @endif
                            </td>
                            <td class="p-3 border border-line">
                                <span class="bg-surface border border-line px-2 py-0.5 text-[10px] font-mono font-bold ">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-3 border border-line font-mono text-[11px]">
                                {{ $log->entity_type }} #{{ $log->entity_id ?? '-' }}
                            </td>
                            <td class="p-3 border border-line font-mono text-[11px] text-muted">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                            <td class="p-3 border border-line font-mono text-[10px] max-w-xs truncate">
                                @if($log->details)
                                    <code class="bg-surface-soft p-1 border border-zinc-300 block truncate">
                                        {{ json_encode($log->details) }}
                                    </code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-muted font-bold">
                                Belum ada aktivitas audit log yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $logs->links() }}</div>
    </div>
</div>
@endsection
