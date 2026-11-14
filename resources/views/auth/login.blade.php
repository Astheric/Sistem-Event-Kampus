@extends('layouts.app')

@section('title', 'Masuk — EventHub')

@section('content')
<div class="mx-auto max-w-md px-4 py-12 sm:py-16">
    <div class="card p-8">
        <div class="text-center">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600"><x-icon name="log-in" class="size-6" /></span>
            <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-ink">Masuk ke EventHub</h1>
            <p class="mt-2 text-sm text-muted">Kelola dan ikuti event kampus dari satu akun.</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="mt-8 space-y-4">
            @csrf
            <div>
                <label for="email" class="label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="input" placeholder="nama@kampus.ac.id">
                @error('email')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="label">Kata sandi</label>
                <input type="password" id="password" name="password" required class="input">
                @error('password')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-muted">
                <input type="checkbox" name="remember" class="rounded border-line text-brand-600 focus:ring-brand-200">
                Ingat saya
            </label>
            <button type="submit" class="btn btn-primary w-full">Masuk</button>
        </form>

        <div class="mt-8 border-t border-line pt-6">
            <p class="text-xs font-semibold uppercase tracking-wide text-muted">Akun demo pengujian</p>
            <div class="mt-3 grid grid-cols-2 gap-2">
                <button type="button" onclick="fillLogin('admin@eventhub.test','password')" class="rounded-[12px] border border-line bg-surface-soft px-3 py-2 text-left text-xs font-semibold text-ink hover:bg-brand-50">Admin</button>
                <button type="button" onclick="fillLogin('organizer@eventhub.test','password')" class="rounded-[12px] border border-line bg-surface-soft px-3 py-2 text-left text-xs font-semibold text-ink hover:bg-brand-50">Organizer</button>
                <button type="button" onclick="fillLogin('panitia@eventhub.test','password')" class="rounded-[12px] border border-line bg-surface-soft px-3 py-2 text-left text-xs font-semibold text-ink hover:bg-brand-50">Panitia</button>
                <button type="button" onclick="fillLogin('participant@eventhub.test','password')" class="rounded-[12px] border border-line bg-surface-soft px-3 py-2 text-left text-xs font-semibold text-ink hover:bg-brand-50">Peserta</button>
            </div>
        </div>

        <p class="mt-6 text-center text-sm text-muted">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-brand-700 hover:text-brand-600">Daftar</a></p>
    </div>
</div>
<script>
function fillLogin(email, password){document.getElementById('email').value=email;document.getElementById('password').value=password;}
</script>
@endsection
