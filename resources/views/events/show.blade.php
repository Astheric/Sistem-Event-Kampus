@extends(Auth::check() ? 'layouts.dashboard' : 'layouts.app')

@section('title', $event->title . ' : EventHub')

@section('content')
<div class="{{ Auth::check() ? 'space-y-6' : 'mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8' }}">
    <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted hover:text-ink"><x-icon name="chevron-left" class="size-3.5" /> Kembali ke daftar event</a>

    <div class="mt-6 grid gap-8 lg:grid-cols-[1.4fr_.8fr]">
        <section class="space-y-6">
            <div class="card p-6 sm:p-8">
                <img src="{{ $event->bannerUrl() }}" alt="{{ $event->title }}" class="mb-6 aspect-[16/9] w-full rounded-xl object-cover bg-slate-100" loading="lazy">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="badge border border-brand-100 bg-brand-50 text-brand-700">{{ $event->category->name }}</span>
                    <span class="badge border border-line bg-surface-soft text-muted">{{ $event->status }}</span>
                    @if($event->average_rating > 0)
                        <span class="badge border border-amber-200 bg-amber-50 text-amber-700">{{ number_format($event->average_rating, 1) }} ({{ $event->review_count }} ulasan)</span>
                    @endif
                </div>

                <h1 class="mt-4 text-2xl font-bold tracking-tight text-ink sm:text-3xl">{{ $event->title }}</h1>
                <p class="mt-2 text-sm text-muted">Diselenggarakan oleh <span class="font-medium text-ink">{{ $event->organization->name }}</span></p>

                <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-line bg-surface-soft p-4">
                        <dt class="text-xs font-medium text-muted">Tanggal dan waktu</dt>
                        <dd class="mt-1 font-semibold text-ink text-sm">{{ $event->start_time->translatedFormat('l, d F Y') }}</dd>
                        <dd class="text-xs text-muted">{{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }} WIB</dd>
                    </div>
                    <div class="rounded-xl border border-line bg-surface-soft p-4">
                        <dt class="text-xs font-medium text-muted">Lokasi</dt>
                        <dd class="mt-1 font-semibold text-ink text-sm">{{ $event->venue ? $event->venue->name : 'Daring / Online' }}</dd>
                        @if($event->venue)
                            <dd class="text-xs text-muted">{{ $event->venue->address }}, {{ $event->venue->city }}</dd>
                            @if($event->venue->maps_url)
                                <a href="{{ $event->venue->maps_url }}" target="_blank" class="mt-2 inline-flex text-xs font-semibold text-brand-700 hover:text-brand-600">Lihat di peta</a>
                            @endif
                        @endif
                    </div>
                </dl>
            </div>

            @if($userRegistration)
                <div class="rounded-xl border border-green-200 bg-green-50 p-5 sm:flex sm:items-center sm:justify-between sm:gap-4">
                    <div>
                        <p class="font-semibold text-green-800 text-sm">Anda sudah terdaftar pada event ini</p>
                        <p class="mt-1 text-xs text-green-700">Kode: {{ $userRegistration->registration_code }} (Status: {{ $userRegistration->status }})</p>
                    </div>
                    <a href="{{ route('participant.tickets.show', $userRegistration->id) }}" class="btn btn-primary text-xs mt-3 sm:mt-0">Lihat tiket</a>
                </div>
            @endif

            <div class="card p-6 sm:p-8">
                <h2 class="text-xl font-bold text-ink">Tentang event</h2>
                <div class="mt-4 whitespace-pre-line leading-relaxed text-muted">{{ $event->description }}</div>
            </div>

            <div class="card p-6 sm:p-8">
                <h2 class="text-xl font-bold text-ink">Ulasan peserta</h2>
                <p class="mt-1 text-sm text-muted">Rating rata-rata {{ number_format($event->average_rating, 1) }} dari {{ $event->review_count }} ulasan.</p>

                @auth
                    @php
                        $hasAttended = \App\Models\Registration::where('event_id', $event->id)
                            ->where('user_id', Auth::id())
                            ->whereIn('status', ['ATTENDED', 'COMPLETED'])
                            ->exists();
                        $myReview = \App\Models\Review::where('event_id', $event->id)->where('user_id', Auth::id())->first();
                    @endphp
                    @if($hasAttended)
                        <form action="{{ route('events.reviews.store', $event->id) }}" method="POST" class="mt-6 space-y-4 rounded-xl border border-line bg-surface-soft p-5">
                            @csrf
                            <div>
                                <label for="rating" class="label">Rating</label>
                                <select id="rating" name="rating" class="input max-w-xs">
                                    @for($i=5;$i>=1;$i--)
                                        <option value="{{ $i }}" {{ ($myReview?->rating ?? 5) == $i ? 'selected' : '' }}>{{ $i }} / 5</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="comment" class="label">Komentar</label>
                                <textarea id="comment" name="comment" rows="4" required class="input">{{ $myReview?->comment }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan ulasan</button>
                        </form>
                    @endif
                @endauth

                <div class="mt-6 space-y-4">
                    @forelse($event->reviews as $review)
                        <div class="rounded-xl border border-line p-4">
                            <div class="flex items-center justify-between gap-4">
                                <p class="font-semibold text-ink">{{ $review->user->name }}</p>
                                <p class="text-sm font-medium text-muted">{{ $review->rating }}/5</p>
                            </div>
                            <p class="mt-2 leading-relaxed text-muted">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-muted">Belum ada ulasan.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <aside class="lg:sticky lg:top-24 lg:self-start">
            <div class="card p-6">
                <h2 class="text-lg font-bold text-ink">Tiket & pendaftaran</h2>
                <div class="mt-5 space-y-4">
                    @forelse($event->tickets as $ticket)
                        @php $isAvailable = $ticket->isAvailable(); @endphp
                        <div class="rounded-xl border border-line p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-semibold text-ink">{{ $ticket->name }}</h3>
                                    <p class="mt-1 text-xs text-muted">Sisa {{ $ticket->available_quota }} dari {{ $ticket->quota }} (sampai {{ $ticket->end_sale->format('d M Y') }})</p>
                                </div>
                                <p class="whitespace-nowrap text-sm font-bold text-ink">@if($ticket->price == 0) Gratis @else Rp {{ number_format($ticket->price, 0, ',', '.') }} @endif</p>
                            </div>
                            <div class="mt-4">
                                @if($userRegistration)
                                    <span class="inline-flex w-full justify-center rounded-xl bg-surface-soft px-4 py-2.5 text-sm font-semibold text-muted">Sudah terdaftar</span>
                                @elseif($isAvailable)
                                    <a href="{{ route('events.checkout', ['event' => $event->id, 'ticket' => $ticket->id]) }}" class="btn btn-primary w-full text-sm">Pesan tiket</a>
                                @else
                                    <span class="inline-flex w-full justify-center rounded-xl bg-surface-soft px-4 py-2.5 text-sm font-semibold text-muted">{{ $ticket->isSoldOut() ? 'Tiket habis' : 'Penjualan ditutup' }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-muted">Belum ada tiket tersedia untuk event ini.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
