@extends('layouts.dashboard')

@section('title', 'Audit Trail Log — Admin EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000]">
        <div class="flex items-center gap-2 mb-1">
            <span class="bg-[#57EB74] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                Transparansi Sistem (FR-20, BR-08)
            </span>
        </div>
        <h1 class="text-3xl font-black">Audit Trail & Log Aktivitas</h1>
        <p class="text-xs font-bold text-zinc-600 mt-1">
            Riwayat otomatis setiap aksi krusial sistem: check-in QR, verifikasi pembayaran, moderasi event, dan penerbitan sertifikat.
        </p>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('admin.audit_logs.index') }}" method="GET"
        class="bg-white border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000] flex flex-wrap items-center gap-3">
        <select name="action" class="bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none">
            <option value="">Semua Aksi (All Actions)</option>
            @foreach($actions as $act)
                <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-[#FFE600] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
            Filter Log
        </button>
    </form>

    <!-- Audit Logs Table -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-black text-white font-mono uppercase text-[10px]">
                        <th class="p-3 border border-black">Waktu</th>
                        <th class="p-3 border border-black">Pengguna</th>
                        <th class="p-3 border border-black">Aksi / Event</th>
                        <th class="p-3 border border-black">Target Entitas</th>
                        <th class="p-3 border border-black">Alamat IP</th>
                        <th class="p-3 border border-black">Rincian Metadata (JSON)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="hover:bg-zinc-50 border-b border-black">
                            <td class="p-3 border border-black font-mono text-[11px]">
                                {{ $log->created_at->format('d M Y, H:i:s') }}
                            </td>
                            <td class="p-3 border border-black">
                                <span class="font-black">{{ $log->user ? $log->user->name : 'Sistem / Anonim' }}</span>
                                @if($log->user)
                                    <span class="text-[10px] text-zinc-500 font-mono block">{{ $log->user->email }}</span>
                                @endif
                            </td>
                            <td class="p-3 border border-black">
                                <span class="bg-[#FFE600] border border-black px-2 py-0.5 text-[10px] font-mono font-black uppercase">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-3 border border-black font-mono text-[11px]">
                                {{ $log->entity_type }} #{{ $log->entity_id ?? '-' }}
                            </td>
                            <td class="p-3 border border-black font-mono text-[11px] text-zinc-500">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                            <td class="p-3 border border-black font-mono text-[10px] max-w-xs truncate">
                                @if($log->details)
                                    <code class="bg-zinc-100 p-1 border border-zinc-300 block truncate">
                                        {{ json_encode($log->details) }}
                                    </code>
                                @else
                                    <span class="text-zinc-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-zinc-500 font-bold">
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
