@extends('layouts.dashboard')

@section('title', 'Manajemen Panitia — ' . $event->title)

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl">
        <div>
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                Delegasi Tim Event
            </span>
            <h1 class="text-3xl font-bold mt-2">Manajemen Panitia Acara</h1>
            <p class="text-xs font-bold text-muted mt-1">{{ $event->title }}</p>
        </div>
        <a href="{{ route('organizer.events.index') }}" class="bg-surface border border-line px-3 py-2 font-bold text-xs  shadow-card rounded-2xl">
            ← Kembali
        </a>
    </div>

    <!-- Form Add Committee Member (FR-12) -->
    <div class="bg-surface border border-line p-6 shadow-card rounded-2xl">
        <h3 class="font-bold text-base  mb-3 border-b border-line pb-2">+ Tambahkan Panitia Baru</h3>
        <p class="text-xs font-bold text-muted mb-4">
            Masukkan alamat email mahasiswa yang telah terdaftar di EventHub untuk didelegasikan hak aksesnya.
        </p>

        <form action="{{ route('organizer.committee.store', $event->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="email" class="block font-bold text-xs  mb-1">Email Pengguna Terdaftar *</label>
                    <input type="email" id="email" name="email" required placeholder="panitia@eventhub.test"
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                    @error('email')
                        <p class="text-ink font-bold text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="committee_role" class="block font-bold text-xs  mb-1">Divisi / Peran *</label>
                    <select id="committee_role" name="committee_role" required
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                        <option value="Registration">Registration (Check-in Scanner)</option>
                        <option value="Finance">Finance (Validasi Pembayaran)</option>
                        <option value="Documentation">Documentation (Dokumentasi)</option>
                    </select>
                </div>
            </div>

            <button type="submit"
                class="bg-brand-600 text-white hover:bg-brand-700 border border-line px-5 py-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                + Tugaskan Panitia
            </button>
        </form>
    </div>

    <!-- Committee Members Table -->
    <div class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <h3 class="font-bold text-base  border-b border-line pb-2">Daftar Fungsionaris Panitia</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-danger text-white font-mono  text-[10px]">
                        <th class="p-3 border border-line">Nama Panitia</th>
                        <th class="p-3 border border-line">Email</th>
                        <th class="p-3 border border-line">Divisi / Otoritas</th>
                        <th class="p-3 border border-line">Waktu Ditugaskan</th>
                        <th class="p-3 border border-line text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->eventMembers as $member)
                        <tr class="hover:bg-surface-soft border-b border-line">
                            <td class="p-3 border border-line font-bold text-sm">{{ $member->user->name }}</td>
                            <td class="p-3 border border-line font-mono">{{ $member->user->email }}</td>
                            <td class="p-3 border border-line">
                                <span class="px-2 py-0.5 border border-line text-[10px] font-bold  {{ $member->committee_role === 'Registration' ? 'bg-surface' : ($member->committee_role === 'Finance' ? 'bg-surface' : 'bg-surface') }}">
                                    {{ $member->committee_role }}
                                </span>
                            </td>
                            <td class="p-3 border border-line font-mono text-[10px] text-muted">{{ $member->created_at->format('d M Y') }}</td>
                            <td class="p-3 border border-line text-center">
                                <form action="{{ route('organizer.committee.destroy', ['event' => $event->id, 'member' => $member->id]) }}" method="POST" onsubmit="return confirm('Hapus panitia ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-brand-600 text-white border border-line px-2 py-1 font-bold text-[10px]">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-muted">Belum ada panitia tambahan yang didelegasikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
