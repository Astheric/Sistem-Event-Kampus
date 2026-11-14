@extends('layouts.app')

@section('title', $event->title . ' — EventHub')

@section('content')
<div class="bg-bg">
    <section class="px-6 pt-10 pb-12 sm:px-8 sm:pt-14">
        <a href="{{ route('events.index') }}" class="text-base underline">← Semua event</a>
        <h1 class="mt-6 max-w-5xl font-extrabold uppercase leading-[0.9] tracking-[-0.04em]" style="font-size:clamp(40px,7vw,96px); font-stretch:150%;">
            {{ $event->title }}
        </h1>
        <div class="mt-8 grid gap-6 text-lg sm:grid-cols-3">
            <div class="bg-field-2 p-5 text-field-2-ink">
                <div class="text-sm font-medium">Waktu</div>
                <div class="mt-2 font-semibold">{{ $event->start_time->format('d M Y, H:i') }} WIB</div>
                <div class="mt-1 text-base">sampai {{ $event->end_time->format('d M Y, H:i') }} WIB</div>
            </div>
            <div class="bg-surface p-5">
                <div class="text-sm font-medium text-muted">Lokasi</div>
                <div class="mt-2 font-semibold">{{ $event->venue ? $event->venue->name : 'Daring / Online' }}</div>
                @if($event->venue)
                    <div class="mt-1 text-base text-muted">{{ $event->venue->address }}, {{ $event->venue->city }}</div>
                    @if($event->venue->maps_url)
                        <a href="{{ $event->venue->maps_url }}" target="_blank" class="mt-2 inline-block underline">Buka peta</a>
                    @endif
                @endif
            </div>
            <div class="bg-field-3 p-5 text-field-3-ink">
                <div class="text-sm font-medium">Penyelenggara</div>
                <div class="mt-2 font-semibold">{{ $event->organization->name }}</div>
                <div class="mt-1 text-base">{{ $event->category->name }}</div>
            </div>
        </div>
    </section>

    @if($userRegistration)
        <section class="bg-field-1 px-6 py-8 text-field-1-ink sm:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-lg font-semibold">Kamu sudah terdaftar.</p>
                    <p class="mt-1 text-base">Kode: <span class="font-semibold">{{ $userRegistration->registration_code }}</span> · Status: {{ $userRegistration->status }}</p>
                </div>
                <a href="{{ route('participant.tickets.show', $userRegistration->id) }}" class="inline-flex bg-ink px-6 py-3 font-semibold text-white">Lihat tiket</a>
            </div>
        </section>
    @endif

    <section class="grid gap-0 lg:grid-cols-[1.3fr_.7fr]">
        <div class="px-6 py-12 sm:px-8 sm:py-16">
            <h2 class="text-[clamp(28px,3.5vw,44px)] font-extrabold">Tentang event</h2>
            <div class="mt-6 max-w-2xl whitespace-pre-line text-lg leading-relaxed text-ink">{{ $event->description }}</div>

            <div class="mt-16">
                <h2 class="text-[clamp(28px,3.5vw,44px)] font-extrabold">Ulasan</h2>
                <p class="mt-2 text-base text-muted">Rating {{ number_format($event->average_rating, 1) }} dari {{ $event->review_count }} ulasan.</p>

                @auth
                    @php
                        $hasAttended = \App\Models\Registration::where('event_id', $event->id)
                            ->where('user_id', Auth::id())
                            ->whereIn('status', ['ATTENDED', 'COMPLETED'])
                            ->exists();
                        $myReview = \App\Models\Review::where('event_id', $event->id)->where('user_id', Auth::id())->first();
                    @endphp
                    @if($hasAttended)
                        <form action="{{ route('events.reviews.store', $event->id) }}" method="POST" class="mt-8 max-w-xl space-y-4 bg-surface p-6">
                            @csrf
                            <div>
                                <label for="rating" class="block text-sm font-medium">Rating</label>
                                <select id="rating" name="rating" class="mt-2 w-full bg-bg px-3 py-3 text-base">
                                    @for($i=5;$i>=1;$i--)
                                        <option value="{{ $i }}" {{ ($myReview?->rating ?? 5) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="comment" class="block text-sm font-medium">Komentar</label>
                                <textarea id="comment" name="comment" rows="4" required class="mt-2 w-full bg-bg px-3 py-3 text-base">{{ $myReview?->comment }}</textarea>
                            </div>
                            <button type="submit" class="bg-ink px-6 py-3 font-semibold text-white">Simpan ulasan</button>
                        </form>
                    @endif
                @endauth

                <div class="mt-10 space-y-8">
                    @forelse($event->reviews as $review)
                        <div class="max-w-2xl">
                            <div class="flex items-baseline justify-between gap-4">
                                <p class="font-semibold">{{ $review->user->name }}</p>
                                <p class="text-base text-muted">{{ $review->rating }}/5</p>
                            </div>
                            <p class="mt-2 text-base leading-relaxed">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="text-base text-muted">Belum ada ulasan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <aside class="bg-field-4 px-6 py-12 text-field-4-ink sm:px-8 sm:py-16">
            <h2 class="text-[clamp(28px,3vw,40px)] font-extrabold">Tiket</h2>
            <div class="mt-8 space-y-6">
                @forelse($event->tickets as $ticket)
                    @php $isAvailable = $ticket->isAvailable(); @endphp
                    <div class="bg-bg p-5 text-ink">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold">{{ $ticket->name }}</h3>
                                <p class="mt-1 text-sm text-muted">Sisa {{ $ticket->available_quota }} dari {{ $ticket->quota }} · s/d {{ $ticket->end_sale->format('d M Y') }}</p>
                            </div>
                            <div class="text-right text-lg font-bold">
                                @if($ticket->price == 0) Gratis @else Rp {{ number_format($ticket->price, 0, ',', '.') }} @endif
                            </div>
                        </div>
                        <div class="mt-5">
                            @if($userRegistration)
                                <span class="inline-block bg-surface px-4 py-3 font-semibold text-muted">Sudah terdaftar</span>
                            @elseif($isAvailable)
                                <a href="{{ route('events.checkout', ['event' => $event->id, 'ticket' => $ticket->id]) }}" class="inline-block bg-accent px-5 py-3 font-semibold text-accent-ink">Pesan tiket</a>
                            @else
                                <span class="inline-block bg-surface px-4 py-3 font-semibold text-muted">{{ $ticket->isSoldOut() ? 'Habis' : 'Ditutup' }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-base">Belum ada tiket tersedia.</p>
                @endforelse
            </div>
        </aside>
    </section>
</div>
@endsection
