@extends('layouts.dashboard')

@section('title', 'Audit Log Sistem')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6">
        <h1 class="text-xl sm:text-2xl font-bold text-ink">Log audit sistem</h1>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('admin.audit_logs.index') }}" method="GET"
        class="bg-surface rounded-xl border border-line p-4 flex flex-wrap items-center gap-3">
        <select name="action" class="rounded-lg border border-line/80 bg-surface px-3 py-2 text-sm text-slate-700 focus:border-brand-700 focus:outline-none">
            <option value="">Semua aksi</option>
            @foreach($actions as $act)
                <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
            @endforeach
        </select>

        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
            <x-icon name="filter" class="size-3.5" />
            Filter Log
        </button>
    </form>

    <!-- Audit Logs Table -->
    <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-soft text-xs font-semibold text-muted uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-lg">Waktu</th>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Aksi / Event</th>
                        <th class="py-3 px-4">Target Entitas</th>
                        <th class="py-3 px-4">Alamat IP</th>
                        <th class="py-3 px-4 rounded-r-lg">Rincian Metadata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/60">
                    @forelse($logs as $log)
                        <tr class="hover:bg-surface-soft/60 transition-colors">
                            <td class="py-3.5 px-4 text-xs text-muted whitespace-nowrap">
                                {{ $log->created_at->format('d M Y, H:i:s') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-ink text-xs block">{{ $log->user ? $log->user->name : 'Sistem / Anonim' }}</span>
                                @if($log->user)
                                    <span class="text-xs text-muted block">{{ $log->user->email }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-700 font-mono">
                                {{ $log->entity_type }} #{{ $log->entity_id ?? '—' }}
                            </td>
                            <td class="py-3.5 px-4 text-xs text-muted font-mono">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                            <td class="py-3.5 px-4 text-xs max-w-xs truncate">
                                @if($log->details)
                                    <code class="rounded bg-surface-soft px-2 py-1 text-xs font-mono text-slate-600 block truncate">
                                        {{ json_encode($log->details) }}
                                    </code>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-muted">
                                <p class="text-sm font-medium text-ink">Belum ada aktivitas audit log yang tercatat</p>
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
