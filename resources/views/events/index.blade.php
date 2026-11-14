@extends('layouts.app')

@section('title', 'Jelajah Event Kampus — EventHub')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">Jelajah Event Kampus</h1>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-muted sm:text-base">Temukan seminar, workshop, festival, kompetisi, dan kegiatan kampus lainnya. Gunakan filter untuk mempersempit pencarian.</p>
        </div>
        <p class="text-sm text-muted"><span class="font-semibold text-ink">{{ $events->total() }}</span> event ditemukan</p>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-[280px_1fr]">
        <aside class="h-fit lg:sticky lg:top-24">
            <form action="{{ route('events.index') }}" method="GET" class="card space-y-5 p-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-ink">Filter</h2>
                    <a href="{{ route('events.index') }}" class="text-xs font-semibold text-brand-700 hover:text-brand-600">Reset</a>
                </div>

                <div>
                    <label for="q" class="label">Pencarian</label>
                    <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Judul atau kata kunci" class="input">
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

                <button type="submit" class="btn btn-primary w-full">Terapkan filter</button>
            </form>
        </aside>

        <section>
            @if($events->isEmpty())
                <div class="card p-10 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600"><x-icon name="calendar" class="size-6" /></div>
                    <h2 class="mt-4 text-lg font-bold text-ink">Tidak ada event yang sesuai</h2>
                    <p class="mt-2 text-sm text-muted">Coba ubah kata kunci atau longgarkan filter pencarian.</p>
                    <a href="{{ route('events.index') }}" class="btn btn-secondary mt-6">Reset filter</a>
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach($events as $event)
                        <a href="{{ route('events.show', $event->slug) }}" class="card group flex flex-col p-5 transition hover:-translate-y-0.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="badge bg-brand-50 text-brand-700">{{ $event->category->name }}</span>
                                @if($event->isFree())
                                    <span class="badge bg-green-50 text-green-700">Gratis</span>
                                @endif
                            </div>
                            <h2 class="mt-4 text-xl font-bold leading-snug text-ink group-hover:text-brand-700">{{ $event->title }}</h2>
                            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-muted">{{ $event->description }}</p>

                            <dl class="mt-4 grid gap-2 text-sm text-muted">
                                <div class="flex items-center gap-2"><x-icon name="calendar" class="size-4" /><dd>{{ $event->start_time->translatedFormat('d M Y') }} · {{ $event->start_time->format('H:i') }} WIB</dd></div>
                                <div class="flex items-center gap-2"><x-icon name="map-pin" class="size-4" /><dd class="truncate">{{ $event->venue ? $event->venue->name : 'Online' }}</dd></div>
                            </dl>

                            <div class="mt-5 flex items-center justify-between border-t border-line pt-4">
                                <span class="text-sm font-bold text-ink">
                                    @if($event->isFree()) Gratis @else Mulai Rp {{ number_format($event->min_price, 0, ',', '.') }} @endif
                                </span>
                                <span class="text-sm font-semibold text-brand-700">Lihat detail</span>
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
