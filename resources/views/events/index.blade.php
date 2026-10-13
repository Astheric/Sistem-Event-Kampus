@extends('layouts.app')

@section('title', 'Katalog Event Kampus — EventHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Title -->
    <div class="mb-8 border-b-4 border-black pb-6">
        <span class="bg-[#00F0FF] border-2 border-black px-3 py-1 text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000]">
            Katalog Lengkap
        </span>
        <h1 class="text-4xl font-black mt-2">Jelajah Event Kampus</h1>
        <p class="text-sm font-bold text-zinc-600 mt-1">Temukan berbagai event resmi kampus: seminar, workshop, festival, hingga kompetisi.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filter Form -->
        <aside class="lg:col-span-1">
            <form action="{{ route('events.index') }}" method="GET" class="bg-white border-3 border-black p-5 shadow-[6px_6px_0px_0px_#000] space-y-5">
                <div class="flex items-center justify-between border-b-2 border-black pb-2">
                    <span class="font-black text-sm uppercase">Filter Pencarian</span>
                    <a href="{{ route('events.index') }}" class="text-xs font-bold text-[#FF6B4A] hover:underline">Reset</a>
                </div>

                <!-- Search Input -->
                <div>
                    <label class="block font-black text-xs uppercase mb-1">Cari Kata Kunci</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul / topik event..."
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block font-black text-xs uppercase mb-1">Kategori</label>
                    <select name="category" class="w-full bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Filter -->
                <div>
                    <label class="block font-black text-xs uppercase mb-1">Harga Tiket</label>
                    <div class="space-y-1 text-xs font-bold">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" value="" {{ !request('price') ? 'checked' : '' }} class="border-2 border-black accent-black">
                            <span>Semua Harga</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" value="free" {{ request('price') === 'free' ? 'checked' : '' }} class="border-2 border-black accent-black">
                            <span class="text-[#2e9c45]">Gratis Saja</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" value="paid" {{ request('price') === 'paid' ? 'checked' : '' }} class="border-2 border-black accent-black">
                            <span>Berbayar</span>
                        </label>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div>
                    <label class="block font-black text-xs uppercase mb-1">Waktu Pelaksanaan</label>
                    <select name="date" class="w-full bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                        <option value="upcoming" {{ request('date') === 'upcoming' ? 'selected' : '' }}>Event Mendatang</option>
                        <option value="today" {{ request('date') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="this_week" {{ request('date') === 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    </select>
                </div>

                <!-- City Filter -->
                @if($cities->isNotEmpty())
                    <div>
                        <label class="block font-black text-xs uppercase mb-1">Kota / Lokasi</label>
                        <select name="city" class="w-full bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                            <option value="">Semua Lokasi</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Sorting -->
                <div>
                    <label class="block font-black text-xs uppercase mb-1">Urutan</label>
                    <select name="sort" class="w-full bg-[#FFFDF8] border-2 border-black p-2 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">
                        <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Tanggal Terdekat</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Paling Baru Dibuat</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Paling Banyak Peminat</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-[#FFE600] hover:bg-[#FFD700] border-2 border-black p-2.5 font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                    Terapkan Filter
                </button>
            </form>
        </aside>

        <!-- Events Grid -->
        <main class="lg:col-span-3 space-y-6">
            <div class="flex items-center justify-between text-xs font-black">
                <span>Ditemukan <span class="bg-[#FFE600] px-1.5 py-0.5 border border-black font-mono">{{ $events->total() }}</span> event aktif</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div class="bg-white border-3 border-black shadow-[5px_5px_0px_0px_#000] flex flex-col justify-between hover:-translate-y-1 transition-transform">
                        <div>
                            <!-- Header & Category Badge -->
                            <div class="p-3 border-b-2 border-black bg-zinc-50 flex items-center justify-between">
                                <span class="bg-[#00F0FF] border border-black px-2 py-0.5 text-[11px] font-black uppercase">
                                    {{ $event->category->name }}
                                </span>
                                @if($event->isFree())
                                    <span class="bg-[#57EB74] border border-black px-2 py-0.5 text-[11px] font-black font-mono">
                                        GRATIS
                                    </span>
                                @else
                                    <span class="bg-[#FFE600] border border-black px-2 py-0.5 text-[11px] font-black font-mono">
                                        Rp {{ number_format($event->min_price, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Body -->
                            <div class="p-4 space-y-3">
                                <h3 class="font-black text-base leading-snug line-clamp-2 hover:text-[#FF6B4A]">
                                    <a href="{{ route('events.show', $event->slug) }}">
                                        {{ $event->title }}
                                    </a>
                                </h3>

                                <p class="text-xs font-bold text-zinc-600 line-clamp-2">
                                    {{ $event->description }}
                                </p>

                                <div class="pt-2 border-t border-black space-y-1 text-[11px] font-bold">
                                    <div class="flex items-center gap-1.5">
                                        <span>📅</span>
                                        <span class="font-mono">{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span>📍</span>
                                        <span class="truncate">{{ $event->venue ? $event->venue->name : 'Daring / Online' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Link Button -->
                        <div class="p-3 border-t-2 border-black bg-zinc-50">
                            <a href="{{ route('events.show', $event->slug) }}"
                                class="block w-full text-center bg-[#FFE600] hover:bg-[#ffd000] border-2 border-black py-2 font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                                Buka Tiket →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-16 bg-white border-3 border-black shadow-[4px_4px_0px_0px_#000] p-6">
                        <span class="text-4xl block mb-2">🔍</span>
                        <h3 class="font-black text-lg">Tidak ada event yang sesuai</h3>
                        <p class="text-xs font-bold text-zinc-600 mt-1">Coba gunakan kata kunci lain atau reset filter pencarian.</p>
                        <a href="{{ route('events.index') }}" class="inline-block mt-4 bg-[#FFE600] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000]">
                            Reset Semua Filter
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $events->links() }}
            </div>
        </main>
    </div>
</div>
@endsection
