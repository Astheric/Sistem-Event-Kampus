@extends('layouts.app')

@section('title', 'Daftar Akun — EventHub')

@section('content')
<div class="max-w-lg mx-auto px-4 py-12" x-data="{ role: '{{ old('role', 'participant') }}' }">
    <!-- Register Card -->
    <div class="bg-white border-4 border-black p-8 shadow-[8px_8px_0px_0px_#000]">
        <div class="border-b-4 border-black pb-4 mb-6">
            <span class="bg-[#57EB74] border-2 border-black px-3 py-1 text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000]">
                Registrasi Akun
            </span>
            <h1 class="text-3xl font-black mt-3">Bergabung ke EventHub</h1>
            <p class="text-sm font-bold text-zinc-600 mt-1">Daftar sebagai Peserta atau Penyelenggara Event Kampus.</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Role Selector -->
            <div>
                <label class="block font-black text-xs uppercase mb-2">Pilih Peran Akun</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer border-3 border-black p-3 text-center transition-all"
                        :class="role === 'participant' ? 'bg-[#57EB74] shadow-[4px_4px_0px_0px_#000] font-black' : 'bg-white hover:bg-zinc-100 font-bold'">
                        <input type="radio" name="role" value="participant" x-model="role" class="sr-only">
                        <span>🎓 Peserta Event</span>
                    </label>
                    <label class="cursor-pointer border-3 border-black p-3 text-center transition-all"
                        :class="role === 'organizer' ? 'bg-[#4DEEEA] shadow-[4px_4px_0px_0px_#000] font-black' : 'bg-white hover:bg-zinc-100 font-bold'">
                        <input type="radio" name="role" value="organizer" x-model="role" class="sr-only">
                        <span>🏢 Organizer / BEM</span>
                    </label>
                </div>
                @error('role')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block font-black text-xs uppercase mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    placeholder="Contoh: Muhammad Budi Santoso"
                    class="w-full bg-[#FFFDF8] border-3 border-black p-3 font-bold text-sm focus:outline-none focus:bg-[#FFE600] focus:shadow-[4px_4px_0px_0px_#000] transition-all">
                @error('name')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Organization Name (if organizer) -->
            <div x-show="role === 'organizer'" x-cloak>
                <label for="organization_name" class="block font-black text-xs uppercase mb-1">Nama Organisasi / Lembaga Kampus</label>
                <input type="text" id="organization_name" name="organization_name" value="{{ old('organization_name') }}"
                    placeholder="Contoh: Himpunan Mahasiswa Informatika / BEM Fasilkom"
                    class="w-full bg-[#FFFDF8] border-3 border-black p-3 font-bold text-sm focus:outline-none focus:bg-[#4DEEEA] focus:shadow-[4px_4px_0px_0px_#000] transition-all">
                @error('organization_name')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block font-black text-xs uppercase mb-1">Alamat Email Kampus / Pribadi</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    placeholder="nama@student.kampus.ac.id"
                    class="w-full bg-[#FFFDF8] border-3 border-black p-3 font-bold text-sm focus:outline-none focus:bg-[#FFE600] focus:shadow-[4px_4px_0px_0px_#000] transition-all">
                @error('email')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block font-black text-xs uppercase mb-1">Nomor WhatsApp / HP</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                    placeholder="081234567890"
                    class="w-full bg-[#FFFDF8] border-3 border-black p-3 font-bold text-sm focus:outline-none focus:bg-[#FFE600] focus:shadow-[4px_4px_0px_0px_#000] transition-all">
                @error('phone')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block font-black text-xs uppercase mb-1">Kata Sandi</label>
                    <input type="password" id="password" name="password" required
                        class="w-full bg-[#FFFDF8] border-3 border-black p-3 font-bold text-sm focus:outline-none focus:bg-[#FFE600] focus:shadow-[4px_4px_0px_0px_#000] transition-all">
                    @error('password')
                        <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block font-black text-xs uppercase mb-1">Konfirmasi Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full bg-[#FFFDF8] border-3 border-black p-3 font-bold text-sm focus:outline-none focus:bg-[#FFE600] focus:shadow-[4px_4px_0px_0px_#000] transition-all">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-[#57EB74] hover:bg-[#48de65] border-3 border-black p-3 font-black text-base uppercase tracking-wider shadow-[4px_4px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all mt-4">
                🚀 Daftar Akun Sekarang
            </button>
        </form>

        <div class="mt-6 text-center text-xs font-bold pt-4 border-t-2 border-black">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="underline decoration-2 font-black text-[#FF6B4A]">Masuk di Sini</a>
        </div>
    </div>
</div>
@endsection
