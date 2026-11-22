@extends('layouts.dashboard')

@section('title', 'Manajemen Panitia: ' . $event->title)
@section('page_title', 'Manajemen panitia')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-ink">Manajemen panitia</h1>
            <p class="text-xs sm:text-sm text-muted mt-0.5">{{ $event->title }}</p>
        </div>
        <a href="{{ route('organizer.events.index') }}" class="btn btn-secondary text-xs py-2 px-3.5">
            Kembali ke daftar event
        </a>
    </div>

    <!-- Form Add Committee Member -->
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 space-y-4">
        <h3 class="font-bold text-base text-ink pb-2 border-b border-line">Tambah panitia</h3>
        <p class="text-xs text-muted">
            Masukkan email pengguna yang sudah terdaftar untuk memberi akses panitia pada event ini.
        </p>

        <form action="{{ route('organizer.committee.store', $event->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="email" class="block font-semibold text-xs text-ink mb-1.5">Email Pengguna Terdaftar *</label>
                    <input type="email" id="email" name="email" required placeholder="panitia@eventhub.test"
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="committee_role" class="block font-semibold text-xs text-ink mb-1.5">Divisi / Peran *</label>
                    <select id="committee_role" name="committee_role" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2 text-sm text-slate-700 focus:border-brand-700 focus:bg-surface focus:outline-none">
                        <option value="Registration">Registration (Check-in Scanner)</option>
                        <option value="Finance">Finance (Validasi Pembayaran)</option>
                        <option value="Documentation">Documentation (Dokumentasi)</option>
                    </select>
                </div>
            </div>

            <button type="submit"
                class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-5 py-2.5 text-xs font-semibold text-white hover:bg-brand-800 transition">
                + Tugaskan Panitia
            </button>
        </form>
    </div>

    <!-- Committee Members Table -->
    <div class="bg-surface rounded-2xl border border-line/60 p-6 space-y-4">
        <h3 class="font-bold text-base text-ink pb-2 border-b border-line/60">Daftar panitia</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-soft text-xs font-semibold text-muted uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-lg">Nama Panitia</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Divisi / Otoritas</th>
                        <th class="py-3 px-4">Waktu Ditugaskan</th>
                        <th class="py-3 px-4 text-center rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/60">
                    @forelse($event->eventMembers as $member)
                        <tr class="hover:bg-surface-soft/60 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-ink">{{ $member->user->name }}</td>
                            <td class="py-3.5 px-4 text-xs text-muted font-mono">{{ $member->user->email }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-md bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">
                                    {{ $member->committee_role }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-muted">{{ $member->created_at->format('d M Y') }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('organizer.committee.destroy', ['event' => $event->id, 'member' => $member->id]) }}" method="POST" onsubmit="return confirm('Hapus panitia ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-red-50 hover:bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-muted">Belum ada panitia tambahan yang didelegasikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
