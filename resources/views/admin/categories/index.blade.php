@extends('layouts.dashboard')

@section('title', 'Master Kategori — Admin EventHub')

@section('dashboard_content')
<div class="space-y-6">
    <div class="bg-white border-3 border-black p-6 shadow-[5px_5px_0px_0px_#000]">
        <span class="bg-[#4DEEEA] border-2 border-black px-2.5 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
            Master Data (FR-04)
        </span>
        <h1 class="text-3xl font-black mt-2">Kategori Event Kampus</h1>
        <p class="text-xs font-bold text-zinc-600 mt-1">Kelola daftar klasifikasi event yang dapat dipilih oleh penyelenggara.</p>
    </div>

    <!-- Form Add Category -->
    <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000]">
        <h3 class="font-black text-base uppercase mb-3 border-b-2 border-black pb-2">+ Tambah Kategori Baru</h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <div class="sm:w-24">
                <label for="icon" class="block font-black text-xs uppercase mb-1">Ikon Emoji</label>
                <input type="text" id="icon" name="icon" value="📌" required maxlength="10"
                    class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-center text-lg focus:outline-none focus:bg-[#FFE600]">
            </div>

            <div class="flex-grow">
                <label for="name" class="block font-black text-xs uppercase mb-1">Nama Kategori *</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: Konser Musik / Webinar Internasional"
                    class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                @error('name')
                    <p class="text-[#FF6B4A] font-black text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:self-end">
                <button type="submit"
                    class="w-full sm:w-auto bg-[#FFE600] hover:bg-[#ffd700] border-2 border-black px-5 py-3 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                    + Simpan Kategori
                </button>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div x-data="{ editModalOpen: false, editId: null, editName: '', editIcon: '', editAction: '' }"
        class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] space-y-4">
        <h3 class="font-black text-base uppercase border-b-2 border-black pb-2">Daftar Kategori Tersedia</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-bold">
                <thead>
                    <tr class="bg-black text-white font-mono uppercase text-[10px]">
                        <th class="p-3 border border-black text-center w-16">Ikon</th>
                        <th class="p-3 border border-black">Nama Kategori</th>
                        <th class="p-3 border border-black">Slug URL</th>
                        <th class="p-3 border border-black text-center">Jumlah Event</th>
                        <th class="p-3 border border-black text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                        <tr class="hover:bg-zinc-50 border-b border-black">
                            <td class="p-3 border border-black text-center text-xl">{{ $cat->icon }}</td>
                            <td class="p-3 border border-black font-black text-sm">{{ $cat->name }}</td>
                            <td class="p-3 border border-black font-mono text-zinc-500">{{ $cat->slug }}</td>
                            <td class="p-3 border border-black text-center font-mono">
                                <span class="bg-[#FFE600] border border-black px-2 py-0.5 text-xs font-black">
                                    {{ $cat->events_count }} Event
                                </span>
                            </td>
                            <td class="p-3 border border-black text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button"
                                        @click="editModalOpen = true; editId = {{ $cat->id }}; editName = '{{ addslashes($cat->name) }}'; editIcon = '{{ addslashes($cat->icon) }}'; editAction = '{{ route('admin.categories.update', $cat->id) }}'"
                                        class="bg-[#4DEEEA] hover:bg-[#38d4d0] text-black border border-black px-2.5 py-1 font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-0.5 active:translate-y-0.5">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-[#FF6B4A] hover:bg-red-600 text-white border border-black px-2.5 py-1 font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000]">
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
                class="bg-white border-4 border-black p-6 w-full max-w-md shadow-[8px_8px_0px_0px_#000] space-y-4">
                <div class="flex items-center justify-between border-b-2 border-black pb-2">
                    <h4 class="font-black text-base uppercase">Edit Kategori</h4>
                    <button type="button" @click="editModalOpen = false" class="font-black text-lg px-2 border border-black hover:bg-zinc-200">&times;</button>
                </div>

                <form :action="editAction" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-black text-xs uppercase mb-1">Ikon Emoji</label>
                        <input type="text" name="icon" x-model="editIcon" maxlength="10" required
                            class="w-full bg-[#FFFDF8] border-2 border-black p-2 font-bold text-center text-xl focus:outline-none focus:bg-[#FFE600]">
                    </div>

                    <div>
                        <label class="block font-black text-xs uppercase mb-1">Nama Kategori</label>
                        <input type="text" name="name" x-model="editName" required
                            class="w-full bg-[#FFFDF8] border-2 border-black p-2 font-bold text-sm focus:outline-none focus:bg-[#FFE600]">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t-2 border-black">
                        <button type="button" @click="editModalOpen = false"
                            class="bg-zinc-200 border-2 border-black px-4 py-2 font-black text-xs uppercase">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-[#FFE600] border-2 border-black px-5 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
