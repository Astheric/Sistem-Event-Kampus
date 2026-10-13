@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna — Admin EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="bg-[#FF70A6] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                Otoritas Akses
            </span>
            <h1 class="text-3xl font-black mt-2">Manajemen Akun Pengguna</h1>
            <p class="text-xs font-bold text-zinc-600 mt-1">Kelola data pengguna terdaftar dan alokasikan peran (Admin, Organizer, Peserta).</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <form action="{{ route('admin.users.index') }}" method="GET"
        class="bg-white border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000] flex flex-wrap items-center gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email pengguna..."
            class="bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs flex-grow focus:outline-none focus:bg-[#FFE600]">

        <select name="role" class="bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none">
            <option value="">Semua Peran</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="organizer" {{ request('role') === 'organizer' ? 'selected' : '' }}>Organizer</option>
            <option value="participant" {{ request('role') === 'participant' ? 'selected' : '' }}>Peserta</option>
        </select>

        <button type="submit" class="bg-[#FFE600] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
            Cari
        </button>
    </form>

    <!-- Users Table -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-black text-white font-mono uppercase text-[10px]">
                        <th class="p-3 border border-black">Pengguna</th>
                        <th class="p-3 border border-black">Email</th>
                        <th class="p-3 border border-black">Peran Saat Ini</th>
                        <th class="p-3 border border-black">Organisasi</th>
                        <th class="p-3 border border-black">Terdaftar</th>
                        <th class="p-3 border border-black text-center">Ubah Peran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr class="hover:bg-zinc-50 border-b border-black">
                            <td class="p-3 border border-black">
                                <span class="font-black text-sm">{{ $u->name }}</span>
                                @if($u->phone)
                                    <span class="text-[10px] text-zinc-500 font-mono block">{{ $u->phone }}</span>
                                @endif
                            </td>
                            <td class="p-3 border border-black font-mono">{{ $u->email }}</td>
                            <td class="p-3 border border-black">
                                <span class="px-2 py-0.5 border border-black text-[10px] font-black uppercase {{ $u->isAdmin() ? 'bg-black text-white' : ($u->isOrganizer() ? 'bg-[#4DEEEA]' : 'bg-[#57EB74]') }}">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="p-3 border border-black">
                                {{ $u->organization ? $u->organization->name : '-' }}
                            </td>
                            <td class="p-3 border border-black font-mono text-[10px] text-zinc-500">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="p-3 border border-black text-center">
                                @if($u->id !== Auth::id())
                                    <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="flex items-center justify-center gap-1">
                                        @csrf
                                        <select name="role" class="bg-[#FFFDF8] border border-black p-1 text-[11px] font-bold">
                                            <option value="participant" {{ $u->role === 'participant' ? 'selected' : '' }}>Peserta</option>
                                            <option value="organizer" {{ $u->role === 'organizer' ? 'selected' : '' }}>Organizer</option>
                                            <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="bg-[#FFE600] border border-black px-2 py-1 font-black text-[10px] uppercase shadow-[1px_1px_0px_0px_#000]">
                                            Ubah
                                        </button>
                                    </form>
                                @else
                                    <span class="text-zinc-400 font-mono text-[10px]">Akun Anda</span>
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
