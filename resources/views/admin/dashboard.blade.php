@extends('layouts.dashboard')

@section('title', 'Dashboard Administrator — EventHub')

@section('dashboard_content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000]">
        <div class="flex items-center gap-2 mb-1">
            <span class="bg-[#FFE600] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                Super Administrator
            </span>
        </div>
        <h1 class="text-3xl font-black">Pusat Kendali & Pengawasan Kampus</h1>
        <p class="text-xs font-bold text-zinc-600 mt-1">
            Moderasi publikasi event, kelola master data kategori, dan pantau log audit seluruh sistem.
        </p>
    </div>

    <!-- Health Metrics Grid (FR-18) -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 text-center">
        <div class="bg-white border-3 border-black p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] uppercase font-black text-zinc-500">Total Pengguna</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $metrics['total_users'] }}</div>
        </div>

        <div class="bg-white border-3 border-black p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] uppercase font-black text-zinc-500">Total Event</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $metrics['total_events'] }}</div>
        </div>

        <div class="bg-[#FF6B4A] text-white border-3 border-black p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] uppercase font-black">Antrean Moderasi</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $metrics['pending_approvals'] }}</div>
        </div>

        <div class="bg-[#FFE600] border-3 border-black p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] uppercase font-black">Total Pendaftaran</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $metrics['total_registrations'] }}</div>
        </div>

        <div class="bg-[#57EB74] border-3 border-black p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] uppercase font-black">Sertifikat Terbit</span>
            <div class="text-3xl font-black font-mono mt-1">{{ $metrics['total_certificates'] }}</div>
        </div>
    </div>

    <!-- Pending Moderation Queue (FR-19) -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <div class="flex items-center justify-between border-b-2 border-black pb-2">
            <div>
                <h3 class="font-black text-lg">Antrean Event Menunggu Persetujuan</h3>
                <p class="text-xs font-bold text-zinc-500">Event berstatus SUBMITTED memerlukan tinjauan Admin sebelum terbit ke publik.</p>
            </div>
            <a href="{{ route('admin.moderation.index') }}" class="font-black text-xs hover:underline">
                Lihat Semua Antrean →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-black text-white font-mono uppercase text-[10px]">
                        <th class="p-3 border border-black">Judul Event</th>
                        <th class="p-3 border border-black">Penyelenggara</th>
                        <th class="p-3 border border-black">Kategori</th>
                        <th class="p-3 border border-black">Jadwal</th>
                        <th class="p-3 border border-black text-center">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingEvents as $event)
                        <tr class="hover:bg-zinc-50 border-b border-black">
                            <td class="p-3 border border-black font-black text-sm">{{ $event->title }}</td>
                            <td class="p-3 border border-black">{{ $event->organization->name }}</td>
                            <td class="p-3 border border-black">
                                <span class="bg-[#00F0FF] border border-black px-1.5 py-0.5 text-[10px] font-black uppercase">
                                    {{ $event->category->name }}
                                </span>
                            </td>
                            <td class="p-3 border border-black font-mono text-[10px]">
                                {{ $event->start_time->format('d M Y, H:i') }}
                            </td>
                            <td class="p-3 border border-black text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.moderation.approve', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-[#57EB74] hover:bg-[#43d960] border border-black px-2.5 py-1 font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]">
                                            ✓ Setujui
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.moderation.show', $event->id) }}"
                                        class="bg-[#FFE600] border border-black px-2.5 py-1 font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]">
                                        Tinjau 🔍
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-zinc-500 font-bold">
                                ✨ Tidak ada event yang menunggu persetujuan saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Audit Logs (FR-20) -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <div class="flex items-center justify-between border-b-2 border-black pb-2">
            <div>
                <h3 class="font-black text-lg">Aktivitas Audit Log Sistem Terbaru</h3>
                <p class="text-xs font-bold text-zinc-500">Pencatatan transparan seluruh mutasi data krusial sistem.</p>
            </div>
            <a href="{{ route('admin.audit_logs.index') }}" class="font-black text-xs hover:underline">
                Buka Log Lengkap →
            </a>
        </div>

        <div class="space-y-2">
            @forelse($recentLogs as $log)
                <div class="p-3 border-2 border-black bg-[#FFFDF8] flex items-center justify-between text-xs font-bold">
                    <div class="flex items-center gap-3">
                        <span class="bg-black text-white px-2 py-0.5 text-[10px] font-mono font-black uppercase">
                            {{ $log->action }}
                        </span>
                        <div>
                            <span class="font-black">{{ $log->user ? $log->user->name : 'Sistem' }}</span>
                            <span class="text-zinc-500">• {{ $log->entity_type }} #{{ $log->entity_id }}</span>
                        </div>
                    </div>
                    <span class="font-mono text-[10px] text-zinc-500">{{ $log->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-xs font-bold text-zinc-500 py-4 text-center">Belum ada catatan log audit.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
