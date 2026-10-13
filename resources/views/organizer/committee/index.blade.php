@extends('layouts.dashboard')

@section('title', 'Manajemen Panitia — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="bg-[#00F0FF] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                Delegasi Tim Event
            </span>
            <h1 class="text-3xl font-black mt-2">Manajemen Panitia Acara</h1>
            <p class="text-xs font-bold text-zinc-600 mt-1">{{ $event->title }}</p>
        </div>
        <a href="{{ route('organizer.events.index') }}" class="bg-white border-2 border-black px-3 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
            ← Kembali
        </a>
    </div>

    <!-- Form Add Committee Member (FR-12) -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000]">
        <h3 class="font-black text-base uppercase mb-3 border-b-2 border-black pb-2">+ Tambahkan Panitia Baru</h3>
        <p class="text-xs font-bold text-zinc-500 mb-4">
            Masukkan alamat email mahasiswa yang telah terdaftar di EventHub untuk didelegasikan hak aksesnya.
        </p>

        <form action="{{ route('organizer.committee.store', $event->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="email" class="block font-black text-xs uppercase mb-1">Email Pengguna Terdaftar *</label>
                    <input type="email" id="email" name="email" required placeholder="panitia@eventhub.test"
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                    @error('email')
                        <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="committee_role" class="block font-black text-xs uppercase mb-1">Divisi / Peran *</label>
                    <select id="committee_role" name="committee_role" required
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                        <option value="Registration">Registration (Check-in Scanner)</option>
                        <option value="Finance">Finance (Validasi Pembayaran)</option>
                        <option value="Documentation">Documentation (Dokumentasi)</option>
                    </select>
                </div>
            </div>

            <button type="submit"
                class="bg-[#FFE600] hover:bg-[#ffd700] border-2 border-black px-5 py-2.5 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                + Tugaskan Panitia
            </button>
        </form>
    </div>

    <!-- Committee Members Table -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <h3 class="font-black text-base uppercase border-b-2 border-black pb-2">Daftar Fungsionaris Panitia</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-black text-white font-mono uppercase text-[10px]">
                        <th class="p-3 border border-black">Nama Panitia</th>
                        <th class="p-3 border border-black">Email</th>
                        <th class="p-3 border border-black">Divisi / Otoritas</th>
                        <th class="p-3 border border-black">Waktu Ditugaskan</th>
                        <th class="p-3 border border-black text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->eventMembers as $member)
                        <tr class="hover:bg-zinc-50 border-b border-black">
                            <td class="p-3 border border-black font-black text-sm">{{ $member->user->name }}</td>
                            <td class="p-3 border border-black font-mono">{{ $member->user->email }}</td>
                            <td class="p-3 border border-black">
                                <span class="px-2 py-0.5 border border-black text-[10px] font-black uppercase {{ $member->committee_role === 'Registration' ? 'bg-[#57EB74]' : ($member->committee_role === 'Finance' ? 'bg-[#FFE600]' : 'bg-[#4DEEEA]') }}">
                                    {{ $member->committee_role }}
                                </span>
                            </td>
                            <td class="p-3 border border-black font-mono text-[10px] text-zinc-500">{{ $member->created_at->format('d M Y') }}</td>
                            <td class="p-3 border border-black text-center">
                                <form action="{{ route('organizer.committee.destroy', ['event' => $event->id, 'member' => $member->id]) }}" method="POST" onsubmit="return confirm('Hapus panitia ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-[#FF6B4A] text-white border border-black px-2 py-1 font-black text-[10px]">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-zinc-500">Belum ada panitia tambahan yang didelegasikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
