@extends('layouts.dashboard')

@section('title', 'Profil Pengguna — EventHub')

@section('dashboard_content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000]">
        <span class="bg-[#FFE600] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
            Pengaturan Akun
        </span>
        <h1 class="text-3xl font-black mt-2">Profil Saya</h1>
        <p class="text-xs font-bold text-zinc-600 mt-1">Perbarui informasi identitas akun Anda di EventHub.</p>
    </div>

    <div class="bg-white border-4 border-black p-6 sm:p-8 shadow-[6px_6px_0px_0px_#000]">
        <form action="{{ route('participant.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block font-black text-xs uppercase mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-sm focus:outline-none focus:bg-[#FFE600]">
                @error('name')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-black text-xs uppercase mb-1">Alamat Email (Akun)</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="w-full bg-zinc-100 border-2 border-black p-2.5 font-bold text-sm text-zinc-500 cursor-not-allowed">
                <span class="text-[10px] text-zinc-500 font-bold">*Email akun tidak dapat diubah secara mandiri.</span>
            </div>

            <div>
                <label for="phone" class="block font-black text-xs uppercase mb-1">Nomor WhatsApp / Kontak</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-sm focus:outline-none focus:bg-[#FFE600]">
                @error('phone')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bio" class="block font-black text-xs uppercase mb-1">Bio Singkat</label>
                <textarea id="bio" name="bio" rows="3" placeholder="Ceritakan program studi, minat, atau peran kampus Anda..."
                    class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if($user->isOrganizer() || $organization)
                <div class="pt-4 border-t-2 border-dashed border-black">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="bg-[#00F0FF] border-2 border-black px-2 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                            🏢 Detail Profil Organisasi
                        </span>
                    </div>

                    <div class="space-y-4 bg-yellow-50 border-2 border-black p-4">
                        <div>
                            <label for="org_name" class="block font-black text-xs uppercase mb-1">Nama Organisasi / Lembaga Kampus</label>
                            <input type="text" id="org_name" name="org_name" value="{{ old('org_name', $organization?->name) }}"
                                placeholder="Contoh: BEM Fakultas Ilmu Komputer"
                                class="w-full bg-white border-2 border-black p-2.5 font-bold text-sm focus:outline-none focus:bg-[#FFE600]">
                            @error('org_name')
                                <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="org_contact" class="block font-black text-xs uppercase mb-1">Kontak Resmi Organisasi</label>
                            <input type="text" id="org_contact" name="org_contact" value="{{ old('org_contact', $organization?->contact) }}"
                                placeholder="Email/No HP Humas Organisasi"
                                class="w-full bg-white border-2 border-black p-2.5 font-bold text-sm focus:outline-none focus:bg-[#FFE600]">
                        </div>

                        <div>
                            <label for="org_description" class="block font-black text-xs uppercase mb-1">Deskripsi Singkat Organisasi</label>
                            <textarea id="org_description" name="org_description" rows="3"
                                placeholder="Visi misi atau fokus kegiatan organisasi Anda..."
                                class="w-full bg-white border-2 border-black p-2.5 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">{{ old('org_description', $organization?->description) }}</textarea>
                        </div>

                        <div>
                            <label for="org_logo" class="block font-black text-xs uppercase mb-1">Logo Organisasi</label>
                            @if($organization?->logo)
                                <div class="mb-2 flex items-center gap-3">
                                    <img src="{{ asset('storage/'.$organization->logo) }}" alt="Logo" class="w-12 h-12 object-cover border-2 border-black shadow-[2px_2px_0px_0px_#000]">
                                    <span class="text-xs font-bold text-zinc-600">Logo saat ini terpasang</span>
                                </div>
                            @endif
                            <input type="file" id="org_logo" name="org_logo" accept="image/*"
                                class="w-full bg-white border-2 border-black p-2 font-bold text-xs focus:outline-none">
                        </div>
                    </div>
                </div>
            @endif

            <button type="submit"
                class="bg-[#FFE600] hover:bg-[#ffd700] border-3 border-black px-6 py-3 font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all mt-4">
                Simpan Perubahan Profil
            </button>
        </form>
    </div>
</div>
@endsection
