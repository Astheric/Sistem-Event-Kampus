@extends(Auth::check() ? 'layouts.dashboard' : 'layouts.app')

@section('title', 'Katalog Event : EventHub')

@section('content')
<div class="{{ Auth::check() ? 'space-y-6' : 'mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8' }}">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between {{ Auth::check() ? 'bg-surface rounded-xl border border-line p-5 sm:p-6' : '' }}">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-ink">Katalog event</h1>
        </div>
        <p class="text-xs sm:text-sm text-muted"><span class="font-semibold text-ink">{{ $events->total() }}</span> event tersedia</p>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-[260px_1fr]">
        <aside class="h-fit lg:sticky lg:top-24">
            <form action="{{ route('events.index') }}" method="GET" class="space-y-4 p-5 rounded-xl border border-line bg-surface">
                <div class="flex items-center justify-between pb-2 border-b border-line">
                    <h2 class="text-xs font-bold text-ink">Filter</h2>
                    <a href="{{ route('events.index') }}" class="text-xs font-semibold text-brand-700 hover:text-brand-800">Reset</a>
                </div>
                <div>
                    <label for="q" class="label">Kata kunci</label>
                    <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul event..." class="input">
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
                <button type="submit" class="btn btn-primary w-full text-xs font-semibold py-2.5">Terapkan filter</button>
            </form>
        </aside>

        <section>
            @if($events->isEmpty())
                <div class="bg-surface rounded-2xl border border-line/60 p-12 text-center">
                    <x-icon name="calendar" class="size-10 mx-auto text-slate-300 mb-2" />
                    <h2 class="text-lg font-bold text-ink">Tidak ada event ditemukan</h2>
                    <p class="mt-1 text-xs text-muted">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                    <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 mt-4 rounded-lg bg-surface-soft px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                        Reset filter
                    </a>
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach($events as $event)
                        <a href="{{ route('events.show', $event->slug) }}" class="card block p-5 rounded-2xl border border-line/60 bg-surface transition hover:border-slate-300">
                            <img src="{{ $event->bannerUrl() }}" alt="{{ $event->title }}" class="mb-4 aspect-[16/9] w-full rounded-xl object-cover bg-slate-100" loading="lazy">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center rounded-md bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">{{ $event->category->name }}</span>
                                @if($event->isFree())
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700">Gratis</span>
                                @endif
                            </div>
                            <h2 class="mt-3 text-lg font-bold leading-snug text-ink">{{ $event->title }}</h2>
                            <p class="mt-1.5 line-clamp-2 text-xs leading-relaxed text-muted">{{ $event->description }}</p>
                            <dl class="mt-4 space-y-1.5 text-xs text-muted">
                                <div class="flex items-center gap-2"><x-icon name="calendar" class="size-3.5 text-slate-400" /><dd>{{ $event->start_time->translatedFormat('d M Y') }} · {{ $event->start_time->format('H:i') }} WIB</dd></div>
                                <div class="flex items-center gap-2"><x-icon name="map-pin" class="size-3.5 text-slate-400" /><dd class="truncate">{{ $event->venue ? $event->venue->name : 'Online' }}</dd></div>
                            </dl>
                            <div class="mt-4 flex items-center justify-between border-t border-line/60 pt-3">
                                <span class="text-xs font-bold text-ink">@if($event->isFree()) Gratis @else Rp {{ number_format($event->min_price, 0, ',', '.') }} @endif</span>
                                <span class="text-xs font-semibold text-brand-700">Lihat detail</span>
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
