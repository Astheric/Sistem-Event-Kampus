@extends('layouts.dashboard')

@section('title', 'Dashboard Administrator — EventHub')

@section('dashboard_content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-surface border border-line p-6 shadow-card rounded-2xl">
        <div class="flex items-center gap-2 mb-1">
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                Super Administrator
            </span>
        </div>
        <h1 class="text-3xl font-bold">Pusat Kendali & Pengawasan Kampus</h1>
        <p class="text-xs font-bold text-muted mt-1">
            Moderasi publikasi event, kelola master data kategori, dan pantau log audit seluruh sistem.
        </p>
    </div>

    <!-- Health Metrics Grid (FR-18) -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 text-center">
        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-[10px]  font-bold text-muted">Total Pengguna</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $metrics['total_users'] }}</div>
        </div>

        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-[10px]  font-bold text-muted">Total Event</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $metrics['total_events'] }}</div>
        </div>

        <div class="bg-surface text-white border border-line p-4 shadow-card rounded-2xl">
            <span class="text-[10px]  font-bold">Antrean Moderasi</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $metrics['pending_approvals'] }}</div>
        </div>

        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-[10px]  font-bold">Total Pendaftaran</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $metrics['total_registrations'] }}</div>
        </div>

        <div class="bg-surface border border-line p-4 shadow-card rounded-2xl">
            <span class="text-[10px]  font-bold">Sertifikat Terbit</span>
            <div class="text-3xl font-bold font-mono mt-1">{{ $metrics['total_certificates'] }}</div>
        </div>
    </div>

    <!-- Pending Moderation Queue (FR-19) -->
    <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <div class="flex items-center justify-between border-b border-line pb-2">
            <div>
                <h3 class="font-bold text-lg">Antrean Event Menunggu Persetujuan</h3>
                <p class="text-xs font-bold text-muted">Event berstatus SUBMITTED memerlukan tinjauan Admin sebelum terbit ke publik.</p>
            </div>
            <a href="{{ route('admin.moderation.index') }}" class="font-bold text-xs hover:underline">
                Lihat Semua Antrean →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-danger text-white font-mono  text-[10px]">
                        <th class="p-3 border border-line">Judul Event</th>
                        <th class="p-3 border border-line">Penyelenggara</th>
                        <th class="p-3 border border-line">Kategori</th>
                        <th class="p-3 border border-line">Jadwal</th>
                        <th class="p-3 border border-line text-center">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingEvents as $event)
                        <tr class="hover:bg-surface-soft border-b border-line">
                            <td class="p-3 border border-line font-bold text-sm">{{ $event->title }}</td>
                            <td class="p-3 border border-line">{{ $event->organization->name }}</td>
                            <td class="p-3 border border-line">
                                <span class="bg-surface border border-line px-1.5 py-0.5 text-[10px] font-bold ">
                                    {{ $event->category->name }}
                                </span>
                            </td>
                            <td class="p-3 border border-line font-mono text-[10px]">
                                {{ $event->start_time->format('d M Y, H:i') }}
                            </td>
                            <td class="p-3 border border-line text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.moderation.approve', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-brand-600 text-white hover:bg-brand-700 border border-line px-2.5 py-1 font-bold text-[10px]  shadow-card active:translate-x-[1px] active:translate-y-[1px] rounded-full">
                                            <x-icon name="check" class="size-4" /> Setujui
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.moderation.show', $event->id) }}"
                                        class="bg-brand-600 text-white border border-line px-2.5 py-1 font-bold text-[10px]  shadow-card active:translate-x-[1px] active:translate-y-[1px] rounded-full">
                                        Tinjau <x-icon name="search" class="size-4" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-muted font-bold">
                                <x-icon name="star" class="size-4" /> Tidak ada event yang menunggu persetujuan saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Audit Logs (FR-20) -->
    <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <div class="flex items-center justify-between border-b border-line pb-2">
            <div>
                <h3 class="font-bold text-lg">Aktivitas Audit Log Sistem Terbaru</h3>
                <p class="text-xs font-bold text-muted">Pencatatan transparan seluruh mutasi data krusial sistem.</p>
            </div>
            <a href="{{ route('admin.audit_logs.index') }}" class="font-bold text-xs hover:underline">
                Buka Log Lengkap →
            </a>
        </div>

        <div class="space-y-2">
            @forelse($recentLogs as $log)
                <div class="p-3 border border-line bg-surface flex items-center justify-between text-xs font-bold">
                    <div class="flex items-center gap-3">
                        <span class="bg-danger text-white px-2 py-0.5 text-[10px] font-mono font-bold ">
                            {{ $log->action }}
                        </span>
                        <div>
                            <span class="font-bold">{{ $log->user ? $log->user->name : 'Sistem' }}</span>
                            <span class="text-muted">• {{ $log->entity_type }} #{{ $log->entity_id }}</span>
                        </div>
                    </div>
                    <span class="font-mono text-[10px] text-muted">{{ $log->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-xs font-bold text-muted py-4 text-center">Belum ada catatan log audit.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
