@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna — Admin EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl">
        <div>
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                Otoritas Akses
            </span>
            <h1 class="text-3xl font-bold mt-2">Manajemen Akun Pengguna</h1>
            <p class="text-xs font-bold text-muted mt-1">Kelola data pengguna terdaftar dan alokasikan peran (Admin, Organizer, Peserta).</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <form action="{{ route('admin.users.index') }}" method="GET"
        class="bg-surface border border-line p-4 shadow-card flex flex-wrap items-center gap-3 rounded-2xl">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email pengguna..."
            class="bg-surface border border-line p-2 font-bold text-xs flex-grow focus:outline-none focus:bg-surface">

        <select name="role" class="bg-surface border border-line p-2 font-bold text-xs focus:outline-none">
            <option value="">Semua Peran</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="organizer" {{ request('role') === 'organizer' ? 'selected' : '' }}>Organizer</option>
            <option value="participant" {{ request('role') === 'participant' ? 'selected' : '' }}>Peserta</option>
        </select>

        <button type="submit" class="bg-brand-600 text-white border border-line px-4 py-2 font-bold text-xs  shadow-card rounded-full">
            Cari
        </button>
    </form>

    <!-- Users Table -->
    <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-danger text-white font-mono  text-[10px]">
                        <th class="p-3 border border-line">Pengguna</th>
                        <th class="p-3 border border-line">Email</th>
                        <th class="p-3 border border-line">Peran Saat Ini</th>
                        <th class="p-3 border border-line">Organisasi</th>
                        <th class="p-3 border border-line">Terdaftar</th>
                        <th class="p-3 border border-line text-center">Ubah Peran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr class="hover:bg-surface-soft border-b border-line">
                            <td class="p-3 border border-line">
                                <span class="font-bold text-sm">{{ $u->name }}</span>
                                @if($u->phone)
                                    <span class="text-[10px] text-muted font-mono block">{{ $u->phone }}</span>
                                @endif
                            </td>
                            <td class="p-3 border border-line font-mono">{{ $u->email }}</td>
                            <td class="p-3 border border-line">
                                <span class="px-2 py-0.5 border border-line text-[10px] font-bold  {{ $u->isAdmin() ? 'bg-danger text-white' : ($u->isOrganizer() ? 'bg-surface' : 'bg-surface') }}">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="p-3 border border-line">
                                {{ $u->organization ? $u->organization->name : '-' }}
                            </td>
                            <td class="p-3 border border-line font-mono text-[10px] text-muted">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="p-3 border border-line text-center">
                                @if($u->id !== Auth::id())
                                    <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="flex items-center justify-center gap-1">
                                        @csrf
                                        <select name="role" class="bg-surface border border-line p-1 text-[11px] font-bold">
                                            <option value="participant" {{ $u->role === 'participant' ? 'selected' : '' }}>Peserta</option>
                                            <option value="organizer" {{ $u->role === 'organizer' ? 'selected' : '' }}>Organizer</option>
                                            <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="bg-brand-600 text-white border border-line px-2 py-1 font-bold text-[10px]  shadow-card rounded-full">
                                            Ubah
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted font-mono text-[10px]">Akun Anda</span>
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
