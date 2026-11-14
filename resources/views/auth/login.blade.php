@extends('layouts.app')

@section('title', 'Masuk — EventHub')
@section('plain', true)

@section('content')
<div class="min-h-screen grid lg:grid-cols-[1.05fr_.95fr]">
    <div class="relative hidden lg:block">
        <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1600&q=80" alt="Gedung kampus" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-brand-900/70"></div>
        <div class="relative flex h-full flex-col justify-between p-12 text-white">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-md bg-white/15"><x-icon name="calendar" class="size-5" /></span>
                <div>
                    <p class="text-lg font-bold leading-tight">EventHub</p>
                    <p class="text-sm text-white/80">Sistem Manajemen Event Kampus</p>
                </div>
            </div>
            <div class="max-w-md">
                <h1 class="text-3xl font-bold leading-tight">Portal terintegrasi untuk kegiatan dan event kampus</h1>
                <p class="mt-4 text-base leading-relaxed text-white/85">Kelola publikasi event, pendaftaran peserta, tiket, presensi, dan sertifikat dalam satu sistem yang terstruktur.</p>
            </div>
            <p class="text-sm text-white/70">Untuk mahasiswa, dosen, panitia, dan administrator.</p>
        </div>
    </div>

    <div class="flex items-center justify-center bg-canvas px-6 py-12 sm:px-10">
        <div class="w-full max-w-md">
            <div class="mb-8 lg:hidden">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-md bg-brand-700 text-white"><x-icon name="calendar" class="size-5" /></span>
                    <div>
                        <p class="text-lg font-bold">EventHub</p>
                        <p class="text-sm text-muted">Sistem Manajemen Event Kampus</p>
                    </div>
                </div>
            </div>

            <div class="card p-8">
                <h2 class="text-2xl font-bold text-ink">Masuk ke akun</h2>
                <p class="mt-2 text-sm text-muted">Gunakan email dan kata sandi terdaftar untuk mengakses sistem.</p>

                @if ($errors->any())
                    <div class="mt-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="label">Alamat email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="input" placeholder="nama@kampus.ac.id">
                    </div>
                    <div>
                        <label for="password" class="label">Kata sandi</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password" class="input" placeholder="Masukkan kata sandi">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-muted">
                            <input type="checkbox" name="remember" class="rounded border-line text-brand-700 focus:ring-brand-200">
                            Ingat sesi saya
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Masuk</button>
                </form>

                <p class="mt-6 border-t border-line pt-6 text-sm text-muted">
                    Belum memiliki akun? <a href="{{ route('register') }}" class="font-semibold text-brand-700 hover:text-brand-900">Daftar sekarang</a>
                </p>
            </div>

            <details class="mt-4 card p-4 text-sm">
                <summary class="cursor-pointer font-semibold text-ink">Akun demo pengujian</summary>
                <div class="mt-3 grid grid-cols-2 gap-2">
                    <button type="button" onclick="fillLogin('admin@eventhub.test','password')" class="rounded-md border border-line px-3 py-2 text-left font-medium hover:bg-surface-soft">Admin</button>
                    <button type="button" onclick="fillLogin('organizer@eventhub.test','password')" class="rounded-md border border-line px-3 py-2 text-left font-medium hover:bg-surface-soft">Organizer</button>
                    <button type="button" onclick="fillLogin('panitia@eventhub.test','password')" class="rounded-md border border-line px-3 py-2 text-left font-medium hover:bg-surface-soft">Panitia</button>
                    <button type="button" onclick="fillLogin('participant@eventhub.test','password')" class="rounded-md border border-line px-3 py-2 text-left font-medium hover:bg-surface-soft">Peserta</button>
                </div>
            </details>

            <p class="mt-6 text-center text-xs text-subtle"><a href="{{ route('events.index') }}" class="hover:text-muted">Lihat katalog event publik</a></p>
        </div>
    </div>
</div>
<script>
function fillLogin(email, password){document.getElementById('email').value=email;document.getElementById('password').value=password;}
</script>
@endsection
