@extends('layouts.dashboard')

@section('title', 'Master Kategori')

@section('dashboard_content')
@php
    $iconOptions = [
        'calendar', 'mic', 'code', 'aperture', 'award', 'briefcase', 'globe', 'star',
        'users', 'tag', 'book-open', 'compass', 'camera', 'grid', 'map-pin', 'file-text',
    ];
@endphp
<div class="space-y-6">
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6">
        <h1 class="text-xl sm:text-2xl font-bold text-ink">Kategori event</h1>
    </div>

    <!-- Form Add Category -->
    <div class="bg-surface rounded-xl border border-line p-5 sm:p-6 space-y-4">
        <h3 class="font-bold text-base text-ink pb-2 border-b border-line">Tambah kategori baru</h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <div class="sm:w-40">
                <label for="icon" class="block font-semibold text-xs text-ink mb-1">Ikon</label>
                <select id="icon" name="icon" required
                    class="w-full rounded-lg border border-line/80 bg-surface-soft/40 p-2.5 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                    @foreach($iconOptions as $option)
                        <option value="{{ $option }}" @selected(old('icon', 'calendar') === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-grow">
                <label for="name" class="block font-semibold text-xs text-ink mb-1">Nama Kategori *</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: Konser Musik / Webinar Nasional"
                    class="w-full rounded-lg border border-line/80 bg-surface-soft/40 p-2.5 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none focus:ring-1 focus:ring-brand-700">
                @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:self-end">
                <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand-700 px-5 py-2.5 text-xs font-semibold text-white hover:bg-brand-800 transition">
                    + Simpan Kategori
                </button>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div x-data="{ editModalOpen: false, editId: null, editName: '', editIcon: '', editAction: '' }"
        class="bg-surface rounded-2xl border border-line/60 p-6 space-y-4">
        <h3 class="font-bold text-base text-ink pb-2 border-b border-line/60">Daftar Kategori Tersedia</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-soft text-xs font-semibold text-muted uppercase tracking-wider">
                        <th class="py-3 px-4 text-center w-16 rounded-l-lg">Ikon</th>
                        <th class="py-3 px-4">Nama Kategori</th>
                        <th class="py-3 px-4">Slug URL</th>
                        <th class="py-3 px-4 text-center">Jumlah Event</th>
                        <th class="py-3 px-4 text-center rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/60">
                    @foreach($categories as $cat)
                        <tr class="hover:bg-surface-soft/60 transition-colors">
                            <td class="py-3.5 px-4 text-center"><x-icon name="{{ $cat->icon }}" class="mx-auto size-4 text-brand-700" /></td>
                            <td class="py-3.5 px-4 font-semibold text-ink">{{ $cat->name }}</td>
                            <td class="py-3.5 px-4 text-xs text-muted font-mono">{{ $cat->slug }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center rounded-md bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">
                                    {{ $cat->events_count }} Event
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button"
                                        @click="editModalOpen = true; editId = {{ $cat->id }}; editName = '{{ addslashes($cat->name) }}'; editIcon = '{{ addslashes($cat->icon ?? '') }}'; editAction = '{{ route('admin.categories.update', $cat->id) }}'"
                                        class="rounded-lg bg-slate-100 hover:bg-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="rounded-lg bg-red-50 hover:bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Edit Category Modal -->
        <div x-show="editModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
            <div @click.away="editModalOpen = false"
                class="bg-surface rounded-2xl border border-line/60 p-6 sm:p-8 w-full max-w-md shadow-xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-line/60">
                    <h4 class="font-bold text-lg text-ink">Edit Kategori</h4>
                    <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-ink text-xl font-bold">&times;</button>
                </div>

                <form :action="editAction" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-semibold text-xs text-ink mb-1">Ikon</label>
                        <select name="icon" x-model="editIcon" required
                            class="w-full rounded-lg border border-line/80 bg-surface-soft/40 p-2.5 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                            @foreach($iconOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-xs text-ink mb-1">Nama Kategori</label>
                        <input type="text" name="name" x-model="editName" required
                            class="w-full rounded-lg border border-line/80 bg-surface-soft/40 p-2.5 text-sm focus:border-brand-700 focus:bg-surface focus:outline-none">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-line/60">
                        <button type="button" @click="editModalOpen = false"
                            class="rounded-lg bg-surface-soft px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-lg bg-brand-700 px-5 py-2 text-xs font-semibold text-white hover:bg-brand-800 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
