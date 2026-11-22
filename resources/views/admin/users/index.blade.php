@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6">
        <h1 class="text-xl sm:text-2xl font-bold text-ink">Pengguna</h1>
    </div>

    <!-- Search & Filter -->
    <form action="{{ route('admin.users.index') }}" method="GET"
        class="bg-surface rounded-2xl border border-line/60 p-4 flex flex-wrap items-center gap-3">
        <div class="flex-grow min-w-[240px]">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau NIDN pengguna..."
                class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">
        </div>

        <select name="role" class="rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm text-slate-700 focus:border-brand-700 focus:bg-surface focus:outline-none">
            <option value="">Semua Peran</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="organizer" {{ request('role') === 'organizer' ? 'selected' : '' }}>Organizer</option>
            <option value="dosen" {{ request('role') === 'dosen' ? 'selected' : '' }}>Dosen</option>
            <option value="participant" {{ request('role') === 'participant' ? 'selected' : '' }}>Mahasiswa / Peserta</option>
        </select>

        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
            <x-icon name="search" class="size-3.5" />
            Cari Pengguna
        </button>
    </form>

    <!-- Users Table -->
    <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-soft text-xs font-semibold text-muted uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-lg">Pengguna</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Peran Saat Ini</th>
                        <th class="py-3 px-4">Identitas / Afiliasi</th>
                        <th class="py-3 px-4">Verifikasi Akun</th>
                        <th class="py-3 px-4">Terdaftar</th>
                        <th class="py-3 px-4 text-center rounded-r-lg">Ubah Peran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/60">
                    @foreach($users as $u)
                        @php
                            $roleBadge = match($u->role) {
                                'admin' => 'bg-red-50 text-red-700 border-red-200/60',
                                'organizer' => 'bg-brand-50 text-brand-700 border-brand-200/60',
                                'dosen' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-surface-soft/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-ink block">{{ $u->name }}</span>
                                @if($u->phone)
                                    <span class="text-xs text-muted block">{{ $u->phone }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 text-xs">{{ $u->email }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold {{ $roleBadge }}">
                                    {{ $u->roleLabel() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">
                                @if($u->isOrganizer() && $u->organization)
                                    <span class="font-medium text-ink">{{ $u->organization->name }}</span>
                                @elseif($u->isLecturer() && $u->nidn)
                                    <span class="font-mono text-muted">NIDN: {{ $u->nidn }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($u->isVerified())
                                    <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700">
                                        <x-icon name="check-circle" class="size-3.5" /> Terverifikasi
                                    </span>
                                @else
                                    <form action="{{ route('admin.users.verify', $u->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-amber-50 border border-amber-200/60 hover:bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 transition">
                                            <x-icon name="check" class="size-3" /> Verifikasi
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs text-muted">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($u->id !== Auth::id())
                                    <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="flex items-center justify-center gap-1.5">
                                        @csrf
                                        <select name="role" class="rounded-lg border border-line bg-surface-soft/50 px-2 py-1 text-xs text-slate-700 focus:outline-none">
                                            <option value="participant" {{ $u->role === 'participant' ? 'selected' : '' }}>Mahasiswa</option>
                                            <option value="dosen" {{ $u->role === 'dosen' ? 'selected' : '' }}>Dosen</option>
                                            <option value="organizer" {{ $u->role === 'organizer' ? 'selected' : '' }}>Organizer</option>
                                            <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="rounded-lg bg-slate-100 hover:bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700 transition">
                                            Ubah
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>{{ $users->links() }}</div>
    </div>
</div>
@endsection
