@extends('layouts.app')

@section('title', 'Daftar — EventHub')

@section('content')
<div class="mx-auto max-w-lg px-4 py-12 sm:py-16" x-data="{ role: '{{ old('role', 'participant') }}' }">
    <div class="card p-8">
        <div class="text-center">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600"><x-icon name="user-plus" class="size-6" /></span>
            <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-ink">Buat akun EventHub</h1>
            <p class="mt-2 text-sm text-muted">Daftar sebagai peserta atau penyelenggara event kampus.</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="mt-8 space-y-4">
            @csrf
            <div>
                <span class="label">Peran akun</span>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer rounded-[14px] border p-4 text-center text-sm font-semibold transition"
                        :class="role === 'participant' ? 'border-brand-500 bg-brand-50 text-brand-900 ring-1 ring-brand-200' : 'border-line bg-surface text-muted hover:bg-surface-soft'">
                        <input type="radio" name="role" value="participant" x-model="role" class="sr-only">
                        Peserta
                    </label>
                    <label class="cursor-pointer rounded-[14px] border p-4 text-center text-sm font-semibold transition"
                        :class="role === 'organizer' ? 'border-brand-500 bg-brand-50 text-brand-900 ring-1 ring-brand-200' : 'border-line bg-surface text-muted hover:bg-surface-soft'">
                        <input type="radio" name="role" value="organizer" x-model="role" class="sr-only">
                        Organizer
                    </label>
                </div>
                @error('role')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="name" class="label">Nama lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="input" placeholder="Nama lengkap">
                @error('name')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>

            <div x-show="role === 'organizer'" x-cloak>
                <label for="organization_name" class="label">Nama organisasi / unit</label>
                <input type="text" id="organization_name" name="organization_name" value="{{ old('organization_name') }}" class="input" placeholder="Contoh: BEM Fasilkom">
                @error('organization_name')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="input" placeholder="nama@kampus.ac.id">
                @error('email')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="phone" class="label">Nomor WhatsApp / HP</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="input" placeholder="08xxxxxxxxxx">
                @error('phone')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label for="password" class="label">Kata sandi</label>
                    <input type="password" id="password" name="password" required class="input">
                    @error('password')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="label">Konfirmasi kata sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="input">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full">Daftar akun</button>
        </form>

        <p class="mt-6 text-center text-sm text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-brand-700 hover:text-brand-600">Masuk</a></p>
    </div>
</div>
@endsection
