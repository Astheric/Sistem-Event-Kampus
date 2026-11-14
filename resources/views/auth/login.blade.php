@extends('layouts.app')

@section('title', 'Masuk — EventHub')
@section('plain', true)

@section('content')
<div class="min-h-screen bg-canvas px-4 py-10 sm:px-6 sm:py-16">
    <div class="mx-auto w-full max-w-md">
        <div class="mb-8 text-center">
            <div class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-xl bg-brand-700 text-white">
                <x-icon name="calendar" class="size-6" />
            </div>
            <h1 class="mt-4 text-2xl font-semibold text-ink">EventHub</h1>
            <p class="mt-1 text-sm text-muted">Sistem Manajemen Event Kampus</p>
        </div>

        <div class="card p-6 sm:p-8">
            <h2 class="text-xl font-semibold text-ink">Masuk ke akun</h2>
            <p class="mt-1 text-sm text-muted">Silakan masukkan kredensial Anda untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @if (session('info'))
                <div class="mt-5 rounded-lg border border-line bg-surface-soft px-4 py-3 text-sm text-muted">{{ session('info') }}</div>
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
                <label class="flex items-center gap-2 text-sm text-muted">
                    <input type="checkbox" name="remember" class="rounded border-line text-brand-700 focus:ring-brand-200">
                    Ingat saya
                </label>
                <button type="submit" class="btn btn-primary w-full">Masuk</button>
            </form>

            <div class="mt-6 border-t border-line pt-6 text-sm text-muted">
                Belum memiliki akun? <a href="{{ route('register') }}" class="font-semibold text-brand-700 hover:text-brand-900">Buat akun baru</a>
            </div>
        </div>

        <details class="mt-4 text-center text-sm">
            <summary class="cursor-pointer text-muted hover:text-ink">Gunakan akun demo</summary>
            <div class="mt-3 grid grid-cols-2 gap-2 text-left">
                <button type="button" onclick="fillLogin('admin@eventhub.test','password')" class="card-soft px-3 py-2 font-medium hover:bg-surface-soft">Admin</button>
                <button type="button" onclick="fillLogin('organizer@eventhub.test','password')" class="card-soft px-3 py-2 font-medium hover:bg-surface-soft">Organizer</button>
                <button type="button" onclick="fillLogin('panitia@eventhub.test','password')" class="card-soft px-3 py-2 font-medium hover:bg-surface-soft">Panitia</button>
                <button type="button" onclick="fillLogin('participant@eventhub.test','password')" class="card-soft px-3 py-2 font-medium hover:bg-surface-soft">Peserta</button>
            </div>
        </details>

        <p class="mt-6 text-center text-sm text-muted"><a href="{{ route('events.index') }}" class="hover:text-ink hover:underline">Buka katalog event publik</a></p>
    </div>
</div>
<script>
function fillLogin(email, password){document.getElementById('email').value=email;document.getElementById('password').value=password;}
</script>
@endsection
