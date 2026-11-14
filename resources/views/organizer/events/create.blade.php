@extends('layouts.dashboard')

@section('title', 'Buat Event Baru — Organizer EventHub')

@section('dashboard_content')
<div class="max-w-3xl space-y-6">
    <div class="bg-surface border border-line p-6 shadow-card rounded-2xl">
        <div class="flex items-center gap-2 mb-2">
            <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                Event Maker
            </span>
        </div>
        <h1 class="text-3xl font-bold">Buat Event Kampus Baru</h1>
        <p class="text-xs font-bold text-muted mt-1">Lengkapi informasi dasar kegiatan sebelum mengonfigurasi tiket.</p>
    </div>

    <div class="bg-surface border border-line p-6 sm:p-8 shadow-card rounded-2xl">
        <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="title" class="block font-bold text-xs  mb-1">Judul Event Resmi *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    placeholder="Contoh: National Tech Summit 2026: AI Horizons"
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
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-ink font-bold text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="venue_id" class="block font-bold text-xs  mb-1">Lokasi / Venue Acara</label>
                    <select id="venue_id" name="venue_id"
                        class="w-full bg-surface border border-line p-3 font-bold text-xs focus:outline-none focus:bg-surface">
                        <option value="">Daring / Online (Zoom / GMeet)</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }} ({{ $venue->city }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="start_time" class="block font-bold text-xs  mb-1">Waktu Mulai *</label>
                    <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time') }}" required
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                    @error('start_time')
                        <p class="text-ink font-bold text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time" class="block font-bold text-xs  mb-1">Waktu Selesai *</label>
                    <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time') }}" required
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                    @error('end_time')
                        <p class="text-ink font-bold text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="capacity" class="block font-bold text-xs  mb-1">Total Target Kapasitas *</label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity', 100) }}" min="1" required
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                    @error('capacity')
                        <p class="text-ink font-bold text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block font-bold text-xs  mb-1">Deskripsi Lengkap Event *</label>
                <textarea id="description" name="description" rows="5" required
                    placeholder="Tuliskan latar belakang acara, pembicara, fasilitas, dan ketentuan pendaftaran..."
                    class="w-full bg-surface border border-line p-3 font-bold text-xs focus:outline-none focus:bg-surface">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-ink font-bold text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="banner" class="block font-bold text-xs  mb-1">Upload Banner / Poster Event</label>
                <input type="file" id="banner" name="banner" accept="image/*"
                    class="w-full bg-surface border border-line p-2 font-bold text-xs focus:outline-none">
                <span class="text-[10px] text-muted font-bold">Format: JPG, PNG, WEBP (Maksimal 3MB)</span>
            </div>

            <div class="pt-4 border-t border-line flex items-center justify-between gap-4">
                <a href="{{ route('organizer.events.index') }}" class="font-bold text-xs hover:underline">
                    ← Batal
                </a>

                <div class="flex gap-2">
                    <button type="submit" name="action" value="draft"
                        class="bg-brand-600 text-white hover:bg-brand-600 text-white-soft border border-line px-4 py-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                        <x-icon name="download" class="size-4" /> Simpan Sebagai Draft
                    </button>
                    <button type="submit" name="action" value="submit"
                        class="bg-brand-600 text-white hover:bg-brand-600 text-white border border-line px-5 py-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                        Lanjut Atur Tiket <x-icon name="tag" class="size-4" /> →
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
