@extends('layouts.app')

@section('title', 'Halaman tidak ditemukan - EventHub')

@section('content')
<div class="mx-auto max-w-7xl px-6 py-20 sm:px-8 sm:py-28">
    <div class="mx-auto max-w-xl text-center">
        <p class="text-sm font-semibold text-brand-700">404</p>
        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
            Halaman tidak ditemukan
        </h1>
        <p class="mt-4 text-base leading-relaxed text-muted">
            Halaman yang Anda cari tidak ada atau sudah dipindahkan. Periksa alamat URL, atau kembali ke halaman utama.
        </p>
        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a href="{{ route('home') }}" class="btn btn-primary px-6 py-3 text-sm">
                Kembali ke beranda
            </a>
            <a href="{{ route('events.index') }}" class="btn btn-secondary px-6 py-3 text-sm">
                Lihat katalog event
            </a>
        </div>
    </div>
</div>
@endsection
