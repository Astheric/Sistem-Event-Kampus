@extends('layouts.app')

@section('title', 'Event — EventHub')

@section('content')
<div class="bg-bg">
    <section class="px-6 pt-16 pb-10 sm:px-8 sm:pt-20">
        <h1 class="max-w-4xl font-extrabold uppercase leading-[0.9] tracking-[-0.04em]" style="font-size:clamp(48px,10vw,140px); font-stretch:150%;">
            SEMUA EVENT
        </h1>
        <p class="mt-6 max-w-xl text-[20px] leading-[1.4] text-ink">Seminar, workshop, festival, dan kompetisi kampus. Pilih, daftar, hadir.</p>
    </section>

    <section class="grid gap-0 lg:grid-cols-[320px_1fr]">
        <aside class="bg-field-2 px-6 py-10 text-field-2-ink sm:px-8 lg:min-h-[60vh]">
            <form action="{{ route('events.index') }}" method="GET" class="space-y-6">
                <div>
                    <label for="q" class="block text-sm font-medium">Cari</label>
                    <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Judul event"
                        class="mt-2 w-full bg-bg px-3 py-3 text-base text-ink outline-none placeholder:text-muted">
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium">Kategori</label>
                    <select id="category" name="category" class="mt-2 w-full bg-bg px-3 py-3 text-base text-ink outline-none">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium">Harga</label>
                    <select id="price" name="price" class="mt-2 w-full bg-bg px-3 py-3 text-base text-ink outline-none">
                        <option value="">Semua</option>
                        <option value="free" {{ request('price') === 'free' ? 'selected' : '' }}>Gratis</option>
                        <option value="paid" {{ request('price') === 'paid' ? 'selected' : '' }}>Berbayar</option>
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium">Waktu</label>
                    <select id="date" name="date" class="mt-2 w-full bg-bg px-3 py-3 text-base text-ink outline-none">
                        <option value="upcoming" {{ request('date', 'upcoming') === 'upcoming' ? 'selected' : '' }}>Mendatang</option>
                        <option value="today" {{ request('date') === 'today' ? 'selected' : '' }}>Hari ini</option>
                        <option value="this_week" {{ request('date') === 'this_week' ? 'selected' : '' }}>Minggu ini</option>
                    </select>
                </div>

                @if($cities->isNotEmpty())
                <div>
                    <label for="city" class="block text-sm font-medium">Kota</label>
                    <select id="city" name="city" class="mt-2 w-full bg-bg px-3 py-3 text-base text-ink outline-none">
                        <option value="">Semua lokasi</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label for="sort" class="block text-sm font-medium">Urutan</label>
                    <select id="sort" name="sort" class="mt-2 w-full bg-bg px-3 py-3 text-base text-ink outline-none">
                        <option value="date_asc" {{ request('sort', 'date_asc') === 'date_asc' ? 'selected' : '' }}>Tanggal terdekat</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Paling diminati</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="bg-ink px-5 py-3 font-semibold text-white">Terapkan</button>
                    <a href="{{ route('events.index') }}" class="px-5 py-3 font-semibold underline">Reset</a>
                </div>
            </form>
        </aside>

        <div class="px-6 py-10 sm:px-8">
            <p class="mb-8 text-lg"><span class="font-semibold">{{ $events->total() }}</span> event ditemukan</p>

            <div class="space-y-0">
                @forelse($events as $event)
                    <a href="{{ route('events.show', $event->slug) }}" class="grid grid-cols-12 gap-y-2 border-t border-ink/15 py-6 transition hover:bg-surface">
                        <div class="col-span-12 lg:col-span-6">
                            <h2 class="text-[clamp(22px,2.4vw,34px)] font-extrabold leading-[1.1]">{{ $event->title }}</h2>
                            <p class="mt-2 max-w-xl text-base text-muted line-clamp-2">{{ $event->description }}</p>
                        </div>
                        <div class="col-span-6 text-base lg:col-span-2 lg:pt-1">{{ $event->category->name }}</div>
                        <div class="col-span-6 text-base lg:col-span-2 lg:pt-1">{{ $event->venue ? $event->venue->name : 'Online' }}</div>
                        <div class="col-span-12 flex items-baseline justify-between gap-4 pt-2 lg:col-span-2 lg:block lg:pt-1 lg:text-right">
                            <div class="text-base">{{ $event->start_time->format('d M Y') }}</div>
                            <div class="mt-1 font-semibold">@if($event->isFree()) Gratis @else Rp {{ number_format($event->min_price, 0, ',', '.') }} @endif</div>
                        </div>
                    </a>
                @empty
                    <div class="border-t border-ink/15 py-16">
                        <p class="text-2xl font-bold">Tidak ada event yang sesuai.</p>
                        <p class="mt-2 text-base text-muted">Coba ubah filter atau kata kunci.</p>
                        <a href="{{ route('events.index') }}" class="mt-6 inline-block bg-ink px-5 py-3 font-semibold text-white">Reset filter</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-10 border-t border-ink/15 pt-8">
                {{ $events->links() }}
            </div>
        </div>
    </section>
</div>
@endsection
