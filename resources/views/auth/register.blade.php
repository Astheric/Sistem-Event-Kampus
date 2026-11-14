@extends('layouts.app')

@section('title', 'Daftar Akun — EventHub')
@section('plain', true)

@section('content')
<div class="min-h-screen grid lg:grid-cols-[1.05fr_.95fr]" x-data="{ role: '{{ old('role', 'participant') }}' }">
    <div class="relative hidden lg:block">
        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c476?auto=format&fit=crop&w=1600&q=80" alt="Mahasiswa di lingkungan kampus" class="absolute inset-0 h-full w-full object-cover">
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
                <h1 class="text-3xl font-bold leading-tight">Registrasi akun pengguna</h1>
                <p class="mt-4 text-base leading-relaxed text-white/85">Daftarkan akun sebagai peserta untuk mengikuti event, atau sebagai organizer untuk mengelola kegiatan dan peserta.</p>
            </div>
            <p class="text-sm text-white/70">Akses terkelola sesuai peran pengguna.</p>
        </div>
    </div>

    <div class="flex items-center justify-center bg-canvas px-6 py-12 sm:px-10">
        <div class="w-full max-w-lg">
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
                <h2 class="text-2xl font-bold text-ink">Buat akun baru</h2>
                <p class="mt-2 text-sm text-muted">Lengkapi informasi di bawah ini untuk mendaftar.</p>

                @if ($errors->any())
                    <div class="mt-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <span class="label">Jenis akun</span>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer rounded-md border p-4 text-center text-sm font-semibold transition" :class="role === 'participant' ? 'border-brand-700 bg-brand-50 text-brand-900 ring-1 ring-brand-200' : 'border-line bg-surface text-muted hover:bg-surface-soft'">
                                <input type="radio" name="role" value="participant" x-model="role" class="sr-only"> Peserta
                            </label>
                            <label class="cursor-pointer rounded-md border p-4 text-center text-sm font-semibold transition" :class="role === 'organizer' ? 'border-brand-700 bg-brand-50 text-brand-900 ring-1 ring-brand-200' : 'border-line bg-surface text-muted hover:bg-surface-soft'">
                                <input type="radio" name="role" value="organizer" x-model="role" class="sr-only"> Organizer
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="label">Nama lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="input" placeholder="Nama lengkap sesuai identitas">
                    </div>

                    <div x-show="role === 'organizer'" x-cloak>
                        <label for="organization_name" class="label">Nama organisasi / unit</label>
                        <input type="text" id="organization_name" name="organization_name" value="{{ old('organization_name') }}" class="input" placeholder="Contoh: BEM Fakultas / Himpunan Mahasiswa">
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="email" class="label">Alamat email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="input" placeholder="nama@kampus.ac.id">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="phone" class="label">Nomor telepon / WhatsApp</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="input" placeholder="08xxxxxxxxxx">
                        </div>
                        <div>
                            <label for="password" class="label">Kata sandi</label>
                            <input type="password" id="password" name="password" required class="input" placeholder="Min. 8 karakter">
                        </div>
                        <div>
                            <label for="password_confirmation" class="label">Konfirmasi kata sandi</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="input" placeholder="Ulangi kata sandi">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full">Daftar</button>
                </form>

                <p class="mt-6 border-t border-line pt-6 text-sm text-muted">
                    Sudah memiliki akun? <a href="{{ route('login') }}" class="font-semibold text-brand-700 hover:text-brand-900">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
