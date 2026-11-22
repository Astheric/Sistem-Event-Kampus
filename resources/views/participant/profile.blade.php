@extends('layouts.dashboard')

@section('title', 'Profil Pengguna')

@section('dashboard_content')
<div class="max-w-2xl space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6">
        <h1 class="text-xl sm:text-2xl font-bold text-ink">Profil saya</h1>
    </div>

    <div class="bg-surface rounded-xl border border-line p-6 sm:p-8">
        <div class="flex items-center gap-4 pb-6 mb-6 border-b border-line">
            <img
                src="https://api.dicebear.com/9.x/big-smile/svg?seed={{ urlencode($user->name) }}&backgroundColor=c7d2fe"
                alt="{{ $user->name }}"
                class="size-16 rounded-full bg-brand-100 object-cover"
                width="64"
                height="64"
            >
            <div>
                <p class="text-base font-semibold text-ink">{{ $user->name }}</p>
                <p class="text-sm text-muted">{{ $user->roleLabel() }}</p>
            </div>
        </div>

        <form action="{{ route('participant.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block font-semibold text-xs text-ink mb-1.5">Nama lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full rounded-lg border border-line/80 bg-surface px-3.5 py-2.5 text-sm focus:border-brand-700 focus:outline-none focus:ring-1 focus:ring-brand-700">
                @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-semibold text-xs text-ink mb-1.5">Alamat email</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="w-full rounded-lg border border-line/60 bg-surface-soft px-3.5 py-2.5 text-sm text-muted cursor-not-allowed">
            </div>

            <div>
                <label for="phone" class="block font-semibold text-xs text-ink mb-1.5">Nomor WhatsApp / Kontak</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2.5 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">
                @error('phone')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bio" class="block font-semibold text-xs text-ink mb-1.5">Bio Singkat</label>
                <textarea id="bio" name="bio" rows="3" placeholder="Ceritakan program studi, minat kegiatan, atau peran kampus Anda..."
                    class="w-full rounded-xl border border-line/80 bg-surface-soft/40 p-3 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if($user->isOrganizer() || $organization)
                <div class="pt-6 border-t border-line/60 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-md bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700">
                            <x-icon name="home" class="size-3.5" /> Detail Profil Organisasi Penyelenggara
                        </span>
                    </div>

                    <div class="space-y-4 rounded-xl border border-line/60 bg-surface-soft/40 p-5">
                        <div>
                            <label for="org_name" class="block font-semibold text-xs text-ink mb-1.5">Nama Organisasi / Lembaga Kampus</label>
                            <input type="text" id="org_name" name="org_name" value="{{ old('org_name', $organization?->name) }}"
                                placeholder="Contoh: BEM Fakultas Ilmu Komputer"
                                class="w-full rounded-lg border border-line/80 bg-surface px-3.5 py-2 text-sm focus:border-brand-700 focus:outline-none">
                            @error('org_name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="org_contact" class="block font-semibold text-xs text-ink mb-1.5">Kontak Resmi Organisasi</label>
                            <input type="text" id="org_contact" name="org_contact" value="{{ old('org_contact', $organization?->contact) }}"
                                placeholder="Email atau kontak resmi humas..."
                                class="w-full rounded-lg border border-line/80 bg-surface px-3.5 py-2 text-sm focus:border-brand-700 focus:outline-none">
                        </div>

                        <div>
                            <label for="org_description" class="block font-semibold text-xs text-ink mb-1.5">Deskripsi Singkat Organisasi</label>
                            <textarea id="org_description" name="org_description" rows="3"
                                placeholder="Visi misi atau fokus kegiatan organisasi Anda..."
                                class="w-full rounded-lg border border-line/80 bg-surface p-3 text-sm focus:border-brand-700 focus:outline-none">{{ old('org_description', $organization?->description) }}</textarea>
                        </div>

                        <div>
                            <label for="org_logo" class="block font-semibold text-xs text-ink mb-1.5">Logo Organisasi</label>
                            @if($organization?->logo)
                                <div class="mb-3 flex items-center gap-3">
                                    <img src="{{ asset('storage/'.$organization->logo) }}" alt="Logo" class="size-12 rounded-lg object-cover border border-line">
                                    <span class="text-xs text-muted">Logo saat ini terpasang</span>
                                </div>
                            @endif
                            <input type="file" id="org_logo" name="org_logo" accept="image/*"
                                class="w-full rounded-lg border border-line/80 bg-surface p-2 text-xs text-slate-600 focus:outline-none">
                        </div>
                    </div>
                </div>
            @endif

            <div class="pt-4">
                <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-6 py-2.5 text-xs font-semibold text-white hover:bg-brand-800 transition">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
