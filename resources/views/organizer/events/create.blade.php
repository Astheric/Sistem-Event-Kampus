@extends('layouts.dashboard')

@section('title', 'Buat Event Baru')

@section('dashboard_content')
<div class="max-w-3xl space-y-6">
    <div class="bg-surface rounded-2xl border border-line/60 p-6 sm:p-8">
        <span class="inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700 mb-2">
            Event Maker
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink">Buat event</h1>
        <p class="text-sm text-muted mt-1">Lengkapi informasi dasar kegiatan sebelum mengonfigurasi kuota dan jenis tiket.</p>
    </div>

    <div class="bg-surface rounded-2xl border border-line/60 p-6 sm:p-8">
        <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="title" class="block font-semibold text-xs text-ink mb-1.5">Judul Event Resmi *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    placeholder="Contoh: Seminar Kewirausahaan Mahasiswa 2026"
                    class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2.5 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">
                @error('title')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="category_id" class="block font-semibold text-xs text-ink mb-1.5">Kategori Event *</label>
                    <select id="category_id" name="category_id" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2.5 text-sm text-slate-700 focus:border-brand-700 focus:bg-surface focus:outline-none">
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="venue_id" class="block font-semibold text-xs text-ink mb-1.5">Lokasi / Venue</label>
                    <select id="venue_id" name="venue_id"
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2.5 text-sm text-slate-700 focus:border-brand-700 focus:bg-surface focus:outline-none">
                        <option value="">Daring / Online (Zoom / GMeet)</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }} ({{ $venue->city }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="audience" class="block font-semibold text-xs text-ink mb-1.5">Target Peserta *</label>
                    <select id="audience" name="audience" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3.5 py-2.5 text-sm text-slate-700 focus:border-brand-700 focus:bg-surface focus:outline-none">
                        <option value="ALL" {{ old('audience', 'ALL') === 'ALL' ? 'selected' : '' }}>Semua</option>
                        <option value="STUDENT" {{ old('audience') === 'STUDENT' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="LECTURER" {{ old('audience') === 'LECTURER' ? 'selected' : '' }}>Dosen</option>
                    </select>
                    @error('audience')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="start_time" class="block font-semibold text-xs text-ink mb-1.5">Waktu Mulai *</label>
                    <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time') }}" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                    @error('start_time')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time" class="block font-semibold text-xs text-ink mb-1.5">Waktu Selesai *</label>
                    <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time') }}" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                    @error('end_time')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="capacity" class="block font-semibold text-xs text-ink mb-1.5">Total Target Kapasitas *</label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity', 100) }}" min="1" required
                        class="w-full rounded-lg border border-line/80 bg-surface-soft/40 px-3 py-2 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                    @error('capacity')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block font-semibold text-xs text-ink mb-1.5">Deskripsi Lengkap Event *</label>
                <textarea id="description" name="description" rows="5" required
                    placeholder="Tuliskan latar belakang acara, pembicara, fasilitas, dan ketentuan pendaftaran..."
                    class="w-full rounded-xl border border-line/80 bg-surface-soft/40 p-3.5 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="banner" class="block font-semibold text-xs text-ink mb-1.5">Upload Banner / Poster Event</label>
                <input type="file" id="banner" name="banner" accept="image/*"
                    class="w-full rounded-lg border border-line/80 bg-surface-soft/40 p-2 text-xs text-slate-600 focus:outline-none">
                <span class="text-xs text-muted mt-1 block">Format: JPG, PNG, WEBP (Maksimal 3MB)</span>
            </div>

            <div class="pt-4 border-t border-line/60 flex items-center justify-between gap-4">
                <a href="{{ route('organizer.events.index') }}" class="text-xs font-semibold text-slate-600 hover:text-ink">
                    ← Batal
                </a>

                <div class="flex items-center gap-2.5">
                    <button type="submit" name="action" value="draft"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-surface-soft px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                        <x-icon name="download" class="size-3.5" /> Simpan Sebagai Draft
                    </button>
                    <button type="submit" name="action" value="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-5 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
                        Lanjut Atur Tiket <x-icon name="tag" class="size-3.5" /> →
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
