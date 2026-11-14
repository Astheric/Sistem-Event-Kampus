@extends('layouts.app')

@section('title', 'Agenda Event — EventHub')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="border-b border-line pb-8">
        <h1 class="text-[clamp(2.4rem,6vw,5rem)] font-black uppercase leading-[0.9] tracking-tight">Agenda Event</h1>
        <p class="mt-4 max-w-2xl text-lg text-muted">Seminar, workshop, kompetisi, dan kegiatan kampus. Filter berdasarkan kategori, waktu, lokasi, dan harga.</p>
    </div>

    <div class="mt-10 grid gap-10 lg:grid-cols-[300px_1fr]">
        <aside class="lg:sticky lg:top-24 lg:self-start">
            <form action="{{ route('events.index') }}" method="GET" class="card space-y-5 p-5">
                <div class="flex items-center justify-between border-b border-line pb-3">
                    <h2 class="text-sm font-black uppercase tracking-wide">Filter</h2>
                    <a href="{{ route('events.index') }}" class="text-xs font-bold uppercase tracking-wide text-accent">Reset</a>
                </div>

                <div>
                    <label for="q" class="label">Kata kunci</label>
                    <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Judul event" class="input">
                </div>
                <div>
                    <label for="category" class="label">Kategori</label>
                    <select id="category" name="category" class="input">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3 lg:grid-cols-1">
                    <div>
                        <label for="price" class="label">Harga</label>
                        <select id="price" name="price" class="input">
                            <option value="">Semua</option>
                            <option value="free" {{ request('price') === 'free' ? 'selected' : '' }}>Gratis</option>
                            <option value="paid" {{ request('price') === 'paid' ? 'selected' : '' }}>Berbayar</option>
                        </select>
                    </div>
                    <div>
                        <label for="date" class="label">Waktu</label>
                        <select id="date" name="date" class="input">
                            <option value="upcoming" {{ request('date', 'upcoming') === 'upcoming' ? 'selected' : '' }}>Mendatang</option>
                            <option value="today" {{ request('date') === 'today' ? 'selected' : '' }}>Hari ini</option>
                            <option value="this_week" {{ request('date') === 'this_week' ? 'selected' : '' }}>Minggu ini</option>
                        </select>
                    </div>
                </div>
                @if($cities->isNotEmpty())
                <div>
                    <label for="city" class="label">Lokasi</label>
                    <select id="city" name="city" class="input">
                        <option value="">Semua lokasi</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label for="sort" class="label">Urutkan</label>
                    <select id="sort" name="sort" class="input">
                        <option value="date_asc" {{ request('sort', 'date_asc') === 'date_asc' ? 'selected' : '' }}>Tanggal terdekat</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Paling diminati</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-full">Terapkan</button>
            </form>
        </aside>

        <section>
            <p class="mb-4 text-sm font-bold uppercase tracking-wide text-muted"><span class="text-ink">{{ $events->total() }}</span> event ditemukan</p>

            @if($events->isEmpty())
                <div class="card p-10">
                    <h2 class="text-2xl font-black uppercase">Tidak ada hasil</h2>
                    <p class="mt-2 text-muted">Coba ubah filter atau kata kunci pencarian.</p>
                    <a href="{{ route('events.index') }}" class="btn btn-secondary mt-6">Reset filter</a>
                </div>
            @else
                <div class="divide-y divide-line border-y border-line">
                    @foreach($events as $event)
                        <a href="{{ route('events.show', $event->slug) }}" class="group grid gap-3 py-6 transition hover:bg-surface md:grid-cols-[120px_1fr_200px_130px] md:items-start">
                            <div class="text-sm font-bold uppercase tracking-wide text-muted">
                                <div class="text-2xl font-black text-ink">{{ $event->start_time->format('d') }}</div>
                                <div>{{ $event->start_time->translatedFormat('M Y') }}</div>
                                <div class="mt-1 font-medium normal-case tracking-normal">{{ $event->start_time->format('H:i') }}</div>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-muted">{{ $event->category->name }}</p>
                                <h2 class="mt-1 text-xl font-black leading-tight tracking-tight group-hover:text-accent sm:text-2xl">{{ $event->title }}</h2>
                                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-muted line-clamp-2">{{ $event->description }}</p>
                            </div>
                            <div class="text-sm text-muted">
                                <p class="font-bold uppercase tracking-wide text-ink">Lokasi</p>
                                <p class="mt-1">{{ $event->venue ? $event->venue->name : 'Online' }}</p>
                            </div>
                            <div class="md:text-right">
                                <p class="text-lg font-black text-ink">@if($event->isFree()) Gratis @else Rp {{ number_format($event->min_price, 0, ',', '.') }} @endif</p>
                                <p class="mt-2 text-sm font-bold uppercase tracking-wide text-accent">Buka</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $events->links() }}</div>
            @endif
        </section>
    </div>
</div>
@endsection
