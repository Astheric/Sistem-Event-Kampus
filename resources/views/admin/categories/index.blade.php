@extends('layouts.dashboard')

@section('title', 'Master Kategori — Admin EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-surface border border-line p-6 shadow-card rounded-2xl">
        <span class="bg-surface border border-line px-2.5 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
            Master Data (FR-04)
        </span>
        <h1 class="text-3xl font-bold mt-2">Kategori Event Kampus</h1>
        <p class="text-xs font-bold text-muted mt-1">Kelola daftar klasifikasi event yang dapat dipilih oleh penyelenggara.</p>
    </div>

    <!-- Form Add Category -->
    <div class="bg-surface border border-line p-6 shadow-card rounded-2xl">
        <h3 class="font-bold text-base  mb-3 border-b border-line pb-2">+ Tambah Kategori Baru</h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <div class="sm:w-24">
                <label for="icon" class="block font-bold text-xs  mb-1">Ikon Emoji</label>
                <input type="text" id="icon" name="icon" value="<x-icon name="map-pin" class="size-4" />" required maxlength="10"
                    class="w-full bg-surface border border-line p-2.5 font-bold text-center text-lg focus:outline-none focus:bg-surface">
            </div>

            <div class="flex-grow">
                <label for="name" class="block font-bold text-xs  mb-1">Nama Kategori *</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: Konser Musik / Webinar Internasional"
                    class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">
                @error('name')
                    <p class="text-ink font-bold text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:self-end">
                <button type="submit"
                    class="w-full sm:w-auto bg-brand-600 text-white hover:bg-brand-700 border border-line px-5 py-3 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                    + Simpan Kategori
                </button>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div x-data="{ editModalOpen: false, editId: null, editName: '', editIcon: '', editAction: '' }"
        class="bg-surface border border-line p-6 shadow-card space-y-4 rounded-2xl">
        <h3 class="font-bold text-base  border-b border-line pb-2">Daftar Kategori Tersedia</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-danger text-white font-mono  text-[10px]">
                        <th class="p-3 border border-line text-center w-16">Ikon</th>
                        <th class="p-3 border border-line">Nama Kategori</th>
                        <th class="p-3 border border-line">Slug URL</th>
                        <th class="p-3 border border-line text-center">Jumlah Event</th>
                        <th class="p-3 border border-line text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                        <tr class="hover:bg-surface-soft border-b border-line">
                            <td class="p-3 border border-line text-center text-xl">{{ $cat->icon }}</td>
                            <td class="p-3 border border-line font-bold text-sm">{{ $cat->name }}</td>
                            <td class="p-3 border border-line font-mono text-muted">{{ $cat->slug }}</td>
                            <td class="p-3 border border-line text-center font-mono">
                                <span class="bg-surface border border-line px-2 py-0.5 text-xs font-bold">
                                    {{ $cat->events_count }} Event
                                </span>
                            </td>
                            <td class="p-3 border border-line text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button"
                                        @click="editModalOpen = true; editId = {{ $cat->id }}; editName = '{{ addslashes($cat->name) }}'; editIcon = '{{ addslashes($cat->icon) }}'; editAction = '{{ route('admin.categories.update', $cat->id) }}'"
                                        class="bg-brand-600 text-white hover:bg-brand-600 text-white text-black border border-line px-2.5 py-1 font-bold text-[10px]  shadow-card active:translate-x-0.5 active:translate-y-0.5 rounded-full">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-brand-600 text-white hover:bg-red-600 text-white border border-line px-2.5 py-1 font-bold text-[10px]  shadow-card rounded-full">
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
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div @click.away="editModalOpen = false"
                class="bg-surface border border-line p-6 w-full max-w-md shadow-card space-y-4 rounded-2xl">
                <div class="flex items-center justify-between border-b border-line pb-2">
                    <h4 class="font-bold text-base ">Edit Kategori</h4>
                    <button type="button" @click="editModalOpen = false" class="font-bold text-lg px-2 border border-line hover:bg-surface-soft">&times;</button>
                </div>

                <form :action="editAction" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-bold text-xs  mb-1">Ikon Emoji</label>
                        <input type="text" name="icon" x-model="editIcon" maxlength="10" required
                            class="w-full bg-surface border border-line p-2 font-bold text-center text-xl focus:outline-none focus:bg-surface">
                    </div>

                    <div>
                        <label class="block font-bold text-xs  mb-1">Nama Kategori</label>
                        <input type="text" name="name" x-model="editName" required
                            class="w-full bg-surface border border-line p-2 font-bold text-sm focus:outline-none focus:bg-surface">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-line">
                        <button type="button" @click="editModalOpen = false"
                            class="bg-surface-soft border border-line px-4 py-2 font-bold text-xs ">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-brand-600 text-white border border-line px-5 py-2 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
