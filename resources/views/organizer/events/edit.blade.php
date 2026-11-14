@extends('layouts.dashboard')

@section('title', 'Edit Event — ' . $event->title)

@section('dashboard_content')
<div class="max-w-3xl space-y-6">
    <div class="bg-surface border border-line p-6 shadow-card flex items-center justify-between rounded-2xl">
        <div>
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                Edit Mode
            </span>
            <h1 class="text-3xl font-bold mt-2">Edit Informasi Event</h1>
            <p class="text-xs font-bold text-muted mt-1">{{ $event->title }}</p>
        </div>
        <a href="{{ route('organizer.events.index') }}" class="font-bold text-xs hover:underline">
            ← Kembali
        </a>
    </div>

    <div class="bg-surface border border-line p-6 sm:p-8 shadow-card rounded-2xl">
        <form action="{{ route('organizer.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block font-bold text-xs  mb-1">Judul Event Resmi *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" required
                    class="w-full bg-surface border border-line p-3 font-bold text-sm focus:outline-none focus:bg-surface">
                @error('title')
                    <p class="text-ink font-bold text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category_id" class="block font-bold text-xs  mb-1">Kategori Event *</label>
                    <select id="category_id" name="category_id" required
                        class="w-full bg-surface border border-line p-3 font-bold text-xs focus:outline-none focus:bg-surface">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="venue_id" class="block font-bold text-xs  mb-1">Lokasi / Venue</label>
                    <select id="venue_id" name="venue_id"
                        class="w-full bg-surface border border-line p-3 font-bold text-xs focus:outline-none focus:bg-surface">
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
                    <label for="start_time" class="block font-bold text-xs  mb-1">Waktu Mulai *</label>
                    <input type="datetime-local" id="start_time" name="start_time"
                        value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}" required
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                </div>

                <div>
                    <label for="end_time" class="block font-bold text-xs  mb-1">Waktu Selesai *</label>
                    <input type="datetime-local" id="end_time" name="end_time"
                        value="{{ old('end_time', $event->end_time->format('Y-m-d\TH:i')) }}" required
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                </div>

                <div>
                    <label for="capacity" class="block font-bold text-xs  mb-1">Total Target Kapasitas *</label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $event->capacity) }}" min="1" required
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                </div>
            </div>

            <div>
                <label for="description" class="block font-bold text-xs  mb-1">Deskripsi Lengkap *</label>
                <textarea id="description" name="description" rows="5" required
                    class="w-full bg-surface border border-line p-3 font-bold text-xs focus:outline-none focus:bg-surface">{{ old('description', $event->description) }}</textarea>
            </div>

            <div>
                <label for="banner" class="block font-bold text-xs  mb-1">Ganti Banner / Poster</label>
                <input type="file" id="banner" name="banner" accept="image/*"
                    class="w-full bg-surface border border-line p-2 font-bold text-xs focus:outline-none">
            </div>

            <div class="pt-4 border-t border-line flex items-center justify-between">
                <a href="{{ route('organizer.events.index') }}" class="font-bold text-xs hover:underline">
                    ← Batal
                </a>
                <button type="submit"
                    class="bg-brand-600 text-white hover:bg-brand-700 border border-line px-6 py-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
