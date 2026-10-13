@extends('layouts.app')

@section('title', 'Masuk — EventHub')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <!-- Login Card -->
    <div class="bg-white border-4 border-black p-8 shadow-[8px_8px_0px_0px_#000]">
        <div class="border-b-4 border-black pb-4 mb-6">
            <span class="bg-[#FFE600] border-2 border-black px-3 py-1 text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000]">
                Autentikasi
            </span>
            <h1 class="text-3xl font-black mt-3">Masuk ke EventHub</h1>
            <p class="text-sm font-bold text-zinc-600 mt-1">Kelola atau ikuti berbagai event kampus terpadu.</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block font-black text-xs uppercase mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full bg-[#FFFDF8] border-3 border-black p-3 font-bold text-sm focus:outline-none focus:bg-[#FFE600] focus:shadow-[4px_4px_0px_0px_#000] transition-all">
                @error('email')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block font-black text-xs uppercase mb-1">Kata Sandi (Password)</label>
                <input type="password" id="password" name="password" required
                    class="w-full bg-[#FFFDF8] border-3 border-black p-3 font-bold text-sm focus:outline-none focus:bg-[#FFE600] focus:shadow-[4px_4px_0px_0px_#000] transition-all">
                @error('password')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs font-bold pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 border-2 border-black accent-black">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-[#FFE600] hover:bg-[#FFD700] border-3 border-black p-3 font-black text-base uppercase tracking-wider shadow-[4px_4px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                ⚡ Masuk Sekarang
            </button>
        </form>

        <!-- Quick Fill Demo Accounts -->
        <div class="mt-8 pt-6 border-t-3 border-dashed border-black">
            <p class="font-black text-xs uppercase text-zinc-500 mb-2">⚡ Quick-Fill Akun Demo / Pengujian:</p>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="fillLogin('admin@eventhub.test', 'password')"
                    class="bg-[#FF70A6] border-2 border-black p-2 font-black text-xs hover:bg-[#ff5795] shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] text-left">
                    🛡️ Admin
                </button>
                <button type="button" onclick="fillLogin('organizer@eventhub.test', 'password')"
                    class="bg-[#4DEEEA] border-2 border-black p-2 font-black text-xs hover:bg-[#34deda] shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] text-left">
                    🏢 Organizer
                </button>
                <button type="button" onclick="fillLogin('panitia@eventhub.test', 'password')"
                    class="bg-[#FFE600] border-2 border-black p-2 font-black text-xs hover:bg-[#ffd500] shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] text-left">
                    📷 Panitia (Scan)
                </button>
                <button type="button" onclick="fillLogin('participant@eventhub.test', 'password')"
                    class="bg-[#57EB74] border-2 border-black p-2 font-black text-xs hover:bg-[#3de05e] shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] text-left">
                    🎓 Peserta
                </button>
            </div>
        </div>

        <div class="mt-6 text-center text-xs font-bold">
            Belum punya akun?
            <a href="{{ route('register') }}" class="underline decoration-2 font-black text-[#FF6B4A]">Daftar Akun Baru</a>
        </div>
    </div>
</div>

<script>
    function fillLogin(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
