@extends('layouts.app')

@section('title', 'Katalog Event Kampus — EventHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Title -->
    <div class="mb-8 border-b border-line pb-6">
        <span class="bg-surface border border-line px-3 py-1 text-xs font-bold  tracking-wider shadow-card rounded-2xl">
            Katalog Lengkap
        </span>
        <h1 class="text-4xl font-bold mt-2">Jelajah Event Kampus</h1>
        <p class="text-sm font-bold text-muted mt-1">Temukan berbagai event resmi kampus: seminar, workshop, festival, hingga kompetisi.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filter Form -->
        <aside class="lg:col-span-1">
            <form action="{{ route('events.index') }}" method="GET" class="bg-surface border border-line p-5 shadow-card space-y-5 rounded-2xl">
                <div class="flex items-center justify-between border-b border-line pb-2">
                    <span class="font-bold text-sm ">Filter Pencarian</span>
                    <a href="{{ route('events.index') }}" class="text-xs font-bold text-ink hover:underline">Reset</a>
                </div>

                <!-- Search Input -->
                <div>
                    <label class="block font-bold text-xs  mb-1">Cari Kata Kunci</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul / topik event..."
                        class="w-full bg-surface border border-line p-2 font-bold text-xs focus:outline-none focus:bg-surface">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block font-bold text-xs  mb-1">Kategori</label>
                    <select name="category" class="w-full bg-surface border border-line p-2 font-bold text-xs focus:outline-none focus:bg-surface">
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
                    <label class="block font-bold text-xs  mb-1">Harga Tiket</label>
                    <div class="space-y-1 text-xs font-bold">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" value="" {{ !request('price') ? 'checked' : '' }} class="border border-line accent-black">
                            <span>Semua Harga</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" value="free" {{ request('price') === 'free' ? 'checked' : '' }} class="border border-line accent-black">
                            <span class="text-ink">Gratis Saja</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" value="paid" {{ request('price') === 'paid' ? 'checked' : '' }} class="border border-line accent-black">
                            <span>Berbayar</span>
                        </label>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div>
                    <label class="block font-bold text-xs  mb-1">Waktu Pelaksanaan</label>
                    <select name="date" class="w-full bg-surface border border-line p-2 font-bold text-xs focus:outline-none focus:bg-surface">
                        <option value="upcoming" {{ request('date') === 'upcoming' ? 'selected' : '' }}>Event Mendatang</option>
                        <option value="today" {{ request('date') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="this_week" {{ request('date') === 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    </select>
                </div>

                <!-- City Filter -->
                @if($cities->isNotEmpty())
                    <div>
                        <label class="block font-bold text-xs  mb-1">Kota / Lokasi</label>
                        <select name="city" class="w-full bg-surface border border-line p-2 font-bold text-xs focus:outline-none focus:bg-surface">
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
                    <label class="block font-bold text-xs  mb-1">Urutan</label>
                    <select name="sort" class="w-full bg-surface border border-line p-2 font-bold text-xs focus:outline-none focus:bg-surface">
                        <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Tanggal Terdekat</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Paling Baru Dibuat</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Paling Banyak Peminat</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-brand-600 text-white hover:bg-brand-700 border border-line p-2.5 font-bold text-xs  tracking-wider shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                    Terapkan Filter
                </button>
            </form>
        </aside>

        <!-- Events Grid -->
        <main class="lg:col-span-3 space-y-6">
            <div class="flex items-center justify-between text-xs font-bold">
                <span>Ditemukan <span class="bg-surface px-1.5 py-0.5 border border-line font-mono">{{ $events->total() }}</span> event aktif</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div class="bg-surface border border-line shadow-card flex flex-col justify-between hover:-translate-y-1 transition-transform rounded-2xl">
                        <div>
                            <!-- Header & Category Badge -->
                            <div class="p-3 border-b border-line bg-surface-soft flex items-center justify-between">
                                <span class="bg-surface border border-line px-2 py-0.5 text-[11px] font-bold ">
                                    {{ $event->category->name }}
                                </span>
                                @if($event->isFree())
                                    <span class="bg-surface border border-line px-2 py-0.5 text-[11px] font-bold font-mono">
                                        GRATIS
                                    </span>
                                @else
                                    <span class="bg-surface border border-line px-2 py-0.5 text-[11px] font-bold font-mono">
                                        Rp {{ number_format($event->min_price, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Body -->
                            <div class="p-4 space-y-3">
                                <h3 class="font-bold text-base leading-snug line-clamp-2 hover:text-ink">
                                    <a href="{{ route('events.show', $event->slug) }}">
                                        {{ $event->title }}
                                    </a>
                                </h3>

                                <p class="text-xs font-bold text-muted line-clamp-2">
                                    {{ $event->description }}
                                </p>

                                <div class="pt-2 border-t border-line space-y-1 text-[11px] font-bold">
                                    <div class="flex items-center gap-1.5">
                                        <span><x-icon name="calendar" class="size-4" /></span>
                                        <span class="font-mono">{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span><x-icon name="map-pin" class="size-4" /></span>
                                        <span class="truncate">{{ $event->venue ? $event->venue->name : 'Daring / Online' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Link Button -->
                        <div class="p-3 border-t border-line bg-surface-soft">
                            <a href="{{ route('events.show', $event->slug) }}"
                                class="block w-full text-center bg-brand-600 text-white hover:bg-brand-600 text-white border border-line py-2 font-bold text-xs  tracking-wider shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                                Buka Tiket →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-16 bg-surface border border-line shadow-card p-6 rounded-2xl">
                        <span class="text-4xl block mb-2"><x-icon name="search" class="size-4" /></span>
                        <h3 class="font-bold text-lg">Tidak ada event yang sesuai</h3>
                        <p class="text-xs font-bold text-muted mt-1">Coba gunakan kata kunci lain atau reset filter pencarian.</p>
                        <a href="{{ route('events.index') }}" class="inline-block mt-4 bg-surface border border-line px-4 py-2 font-bold text-xs  shadow-card rounded-2xl">
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
