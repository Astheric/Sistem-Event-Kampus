@extends('layouts.app')

@section('title', 'Daftar Akun — EventHub')
@section('plain', true)

@section('content')
<div class="min-h-screen bg-canvas px-4 py-10 sm:px-6 sm:py-16" x-data="{ role: '{{ old('role', 'participant') }}' }">
    <div class="mx-auto w-full max-w-lg">
        <div class="mb-8 text-center">
            <div class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-xl bg-brand-700 text-white">
                <x-icon name="calendar" class="size-6" />
            </div>
            <h1 class="mt-4 text-2xl font-semibold text-ink">EventHub</h1>
            <p class="mt-1 text-sm text-muted">Sistem Manajemen Event Kampus</p>
        </div>

        <div class="card p-6 sm:p-8">
            <h2 class="text-xl font-semibold text-ink">Pendaftaran akun</h2>
            <p class="mt-1 text-sm text-muted">Lengkapi data berikut untuk membuat akun baru.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div>
                    <span class="label">Jenis akun</span>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer rounded-lg border px-4 py-3 text-center text-sm font-medium transition" :class="role === 'participant' ? 'border-brand-700 bg-brand-50 text-brand-900' : 'border-line bg-surface text-muted hover:bg-surface-soft'">
                            <input type="radio" name="role" value="participant" x-model="role" class="sr-only"> Peserta
                        </label>
                        <label class="cursor-pointer rounded-lg border px-4 py-3 text-center text-sm font-medium transition" :class="role === 'organizer' ? 'border-brand-700 bg-brand-50 text-brand-900' : 'border-line bg-surface text-muted hover:bg-surface-soft'">
                            <input type="radio" name="role" value="organizer" x-model="role" class="sr-only"> Organizer
                        </label>
                    </div>
                </div>

                <div>
                    <label for="name" class="label">Nama lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="input" placeholder="Nama lengkap">
                </div>

                <div x-show="role === 'organizer'" x-cloak>
                    <label for="organization_name" class="label">Nama organisasi / unit</label>
                    <input type="text" id="organization_name" name="organization_name" value="{{ old('organization_name') }}" class="input" placeholder="Contoh: BEM Fakultas">
                </div>

                <div>
                    <label for="email" class="label">Alamat email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="input" placeholder="nama@kampus.ac.id">
                </div>

                <div>
                    <label for="phone" class="label">Nomor telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="input" placeholder="08xxxxxxxxxx">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="password" class="label">Kata sandi</label>
                        <input type="password" id="password" name="password" required class="input" placeholder="Minimal 8 karakter">
                    </div>
                    <div>
                        <label for="password_confirmation" class="label">Konfirmasi kata sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="input" placeholder="Ulangi kata sandi">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full">Daftar</button>
            </form>

            <div class="mt-6 border-t border-line pt-6 text-sm text-muted">
                Sudah memiliki akun? <a href="{{ route('login') }}" class="font-semibold text-brand-700 hover:text-brand-900">Masuk</a>
            </div>
        </div>

        <p class="mt-6 text-center text-sm text-muted"><a href="{{ route('events.index') }}" class="hover:text-ink hover:underline">Buka katalog event publik</a></p>
    </div>
</div>
@endsection
