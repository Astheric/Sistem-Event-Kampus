@extends('layouts.app')

@section('title', 'EventHub — Platform Pengelolaan Event Kampus')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16 text-center space-y-6">
    <div class="inline-flex items-center gap-2 bg-brand-50 text-brand-600 px-3 py-1 rounded-full text-xs font-semibold">
        <x-icon name="calendar" class="size-4" />
        EventHub
    </div>
    <h1 class="text-4xl font-bold text-ink">Platform Pengelolaan Event Kampus Terpadu</h1>
    <p class="text-muted">Temukan, kelola, dan ikuti berbagai event kampus dengan mudah.</p>
    <div class="flex items-center justify-center gap-3">
        <a href="{{ route('events.index') }}" class="bg-brand-600 text-white px-5 py-2.5 rounded-full font-semibold hover:bg-brand-700">
            <x-icon name="compass" class="size-4" /> Jelajahi Event
        </a>
        <a href="{{ route('login') }}" class="bg-surface border border-line text-ink px-5 py-2.5 rounded-full font-semibold hover:bg-surface-soft">
            Masuk
        </a>
    </div>
</div>
@endsection
