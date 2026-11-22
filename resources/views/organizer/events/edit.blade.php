@extends('layouts.dashboard')

@section('title', 'Edit Event: ' . $event->title)
@section('page_title', 'Edit event')

@section('dashboard_content')
<div class="max-w-3xl space-y-6">
    <div class="bg-surface rounded-2xl border border-line/60 p-6 sm:p-8 flex items-center justify-between">
        <div>
            <span class="inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700 mb-2">
                Edit Mode
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink">Edit event</h1>
            <p class="text-sm text-muted mt-1">{{ $event->title }}</p>
        </div>
        <a href="{{ route('organizer.events.index') }}" class="text-xs font-semibold text-slate-600 hover:text-ink">
            ← Kembali
        </a>
    </div>

    <div class="bg-surface rounded-2xl border border-line/60 p-6 sm:p-8">
        <form action="{{ route('organizer.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block font-semibold text-xs text-ink mb-1.5">Judul Event Resmi *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" required
                    class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2.5 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">
                @error('title')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category_id" class="block font-semibold text-xs text-ink mb-1.5">Kategori Event *</label>
                    <select id="category_id" name="category_id" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2.5 text-sm text-slate-700 focus:border-brand-700 focus:bg-surface focus:outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="venue_id" class="block font-semibold text-xs text-ink mb-1.5">Lokasi / Venue</label>
                    <select id="venue_id" name="venue_id"
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2.5 text-sm text-slate-700 focus:border-brand-700 focus:bg-surface focus:outline-none">
                        <option value="">Daring / Online (Zoom / GMeet)</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ old('venue_id', $event->venue_id) == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }} ({{ $venue->city }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="start_time" class="block font-semibold text-xs text-ink mb-1.5">Waktu Mulai *</label>
                    <input type="datetime-local" id="start_time" name="start_time"
                        value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                </div>

                <div>
                    <label for="end_time" class="block font-semibold text-xs text-ink mb-1.5">Waktu Selesai *</label>
                    <input type="datetime-local" id="end_time" name="end_time"
                        value="{{ old('end_time', $event->end_time->format('Y-m-d\TH:i')) }}" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                </div>

                <div>
                    <label for="capacity" class="block font-semibold text-xs text-ink mb-1.5">Total Target Kapasitas *</label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $event->capacity) }}" min="1" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                </div>
            </div>

            <div>
                <label for="description" class="block font-semibold text-xs text-ink mb-1.5">Deskripsi Lengkap *</label>
                <textarea id="description" name="description" rows="5" required
                    class="w-full rounded-xl border border-line/80 bg-surface-soft/40 p-3.5 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">{{ old('description', $event->description) }}</textarea>
            </div>

            <div>
                <label for="banner" class="block font-semibold text-xs text-ink mb-1.5">Ganti Banner / Poster</label>
                <input type="file" id="banner" name="banner" accept="image/*"
                    class="w-full rounded-lg border border-line/80 bg-surface-soft/40 p-2 text-xs text-slate-600 focus:outline-none">
            </div>

            <div class="pt-4 border-t border-line/60 flex items-center justify-between">
                <a href="{{ route('organizer.events.index') }}" class="text-xs font-semibold text-slate-600 hover:text-ink">
                    ← Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-6 py-2.5 text-xs font-semibold text-white hover:bg-brand-800 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
