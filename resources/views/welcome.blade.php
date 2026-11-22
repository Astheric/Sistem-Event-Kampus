@extends('layouts.app')

@section('title', 'EventHub')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16 text-center space-y-6">
    <img src="{{ asset('images/logo.webp') }}" alt="EventHub" class="mx-auto h-8 w-auto object-contain">
    <h1 class="text-3xl font-bold text-ink">EventHub</h1>
    <p class="text-muted">Pendaftaran event, tiket, dan sertifikat kegiatan kampus.</p>
    <div class="flex items-center justify-center gap-3">
        <a href="{{ route('events.index') }}" class="btn btn-primary px-5 py-2.5 text-sm">
            Buka katalog event
        </a>
        <a href="{{ route('login') }}" class="btn btn-secondary px-5 py-2.5 text-sm">
            Masuk
        </a>
    </div>
</div>
@endsection
