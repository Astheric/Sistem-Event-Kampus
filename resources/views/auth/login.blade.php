@extends('layouts.app')

@section('title', 'Masuk — EventHub')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <!-- Login Card -->
    <div class="bg-surface border border-line p-8 shadow-card rounded-2xl">
        <div class="border-b border-line pb-4 mb-6">
            <span class="bg-surface border border-line px-3 py-1 text-xs font-bold  tracking-wider shadow-card rounded-2xl">
                Autentikasi
            </span>
            <h1 class="text-3xl font-bold mt-3">Masuk ke EventHub</h1>
            <p class="text-sm font-bold text-muted mt-1">Kelola atau ikuti berbagai event kampus terpadu.</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block font-bold text-xs  mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full bg-surface border border-line p-3 font-bold text-sm focus:outline-none focus:bg-surface focus:shadow-card transition-all rounded-2xl">
                @error('email')
                    <p class="text-ink font-bold text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block font-bold text-xs  mb-1">Kata Sandi (Password)</label>
                <input type="password" id="password" name="password" required
                    class="w-full bg-surface border border-line p-3 font-bold text-sm focus:outline-none focus:bg-surface focus:shadow-card transition-all rounded-2xl">
                @error('password')
                    <p class="text-ink font-bold text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs font-bold pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 border border-line accent-black">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-brand-600 text-white hover:bg-brand-700 border border-line p-3 font-bold text-base  tracking-wider shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                <x-icon name="zap" class="size-4" /> Masuk Sekarang
            </button>
        </form>

        <!-- Quick Fill Demo Accounts -->
        <div class="mt-8 pt-6 border-t border-dashed border-line">
            <p class="font-bold text-xs  text-muted mb-2"><x-icon name="zap" class="size-4" /> Quick-Fill Akun Demo / Pengujian:</p>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="fillLogin('admin@eventhub.test', 'password')"
                    class="bg-brand-600 text-white border border-line p-2 font-bold text-xs hover:bg-brand-600 text-white shadow-card active:translate-x-[1px] active:translate-y-[1px] text-left rounded-full">
                    <x-icon name="shield" class="size-4" /> Admin
                </button>
                <button type="button" onclick="fillLogin('organizer@eventhub.test', 'password')"
                    class="bg-brand-600 text-white border border-line p-2 font-bold text-xs hover:bg-brand-600 text-white shadow-card active:translate-x-[1px] active:translate-y-[1px] text-left rounded-full">
                    <x-icon name="home" class="size-4" /> Organizer
                </button>
                <button type="button" onclick="fillLogin('panitia@eventhub.test', 'password')"
                    class="bg-brand-600 text-white border border-line p-2 font-bold text-xs hover:bg-brand-600 text-white shadow-card active:translate-x-[1px] active:translate-y-[1px] text-left rounded-full">
                    <x-icon name="camera" class="size-4" /> Panitia (Scan)
                </button>
                <button type="button" onclick="fillLogin('participant@eventhub.test', 'password')"
                    class="bg-brand-600 text-white border border-line p-2 font-bold text-xs hover:bg-brand-600 text-white shadow-card active:translate-x-[1px] active:translate-y-[1px] text-left rounded-full">
                    <x-icon name="book-open" class="size-4" /> Peserta
                </button>
            </div>
        </div>

        <div class="mt-6 text-center text-xs font-bold">
            Belum punya akun?
            <a href="{{ route('register') }}" class="underline decoration-2 font-bold text-ink">Daftar Akun Baru</a>
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
