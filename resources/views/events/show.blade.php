@extends('layouts.app')

@section('title', $event->title . ' — EventHub')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <a href="{{ route('events.index') }}" class="text-sm font-bold uppercase tracking-wide text-muted hover:text-ink">← Kembali ke agenda</a>

    <div class="mt-6 border-b border-line pb-10">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-muted">{{ $event->category->name }} · {{ $event->organization->name }}</p>
        <h1 class="mt-3 max-w-5xl text-[clamp(2.2rem,5vw,4.5rem)] font-black uppercase leading-[0.92] tracking-tight">{{ $event->title }}</h1>
        <dl class="mt-8 grid gap-px border border-line bg-line sm:grid-cols-3">
            <div class="bg-surface p-5">
                <dt class="text-xs font-bold uppercase tracking-wide text-muted">Tanggal & waktu</dt>
                <dd class="mt-2 text-lg font-bold">{{ $event->start_time->translatedFormat('l, d F Y') }}</dd>
                <dd class="mt-1 text-sm text-muted">{{ $event->start_time->format('H:i') }} – {{ $event->end_time->format('H:i') }} WIB</dd>
            </div>
            <div class="bg-surface p-5">
                <dt class="text-xs font-bold uppercase tracking-wide text-muted">Lokasi</dt>
                <dd class="mt-2 text-lg font-bold">{{ $event->venue ? $event->venue->name : 'Daring / Online' }}</dd>
                @if($event->venue)
                    <dd class="mt-1 text-sm text-muted">{{ $event->venue->address }}, {{ $event->venue->city }}</dd>
                    @if($event->venue->maps_url)
                        <a href="{{ $event->venue->maps_url }}" target="_blank" class="mt-2 inline-block text-sm font-bold uppercase tracking-wide text-accent">Buka peta</a>
                    @endif
                @endif
            </div>
            <div class="bg-surface p-5">
                <dt class="text-xs font-bold uppercase tracking-wide text-muted">Status & ulasan</dt>
                <dd class="mt-2 text-lg font-bold">{{ $event->status }}</dd>
                <dd class="mt-1 text-sm text-muted">@if($event->average_rating > 0) Rating {{ number_format($event->average_rating, 1) }} ({{ $event->review_count }} ulasan) @else Belum ada ulasan @endif</dd>
            </div>
        </dl>
    </div>

    @if($userRegistration)
        <div class="mt-8 border border-line bg-success-soft p-5 sm:flex sm:items-center sm:justify-between sm:gap-4">
            <div>
                <p class="font-bold text-success-ink">Kamu sudah terdaftar.</p>
                <p class="mt-1 text-sm text-success-ink">Kode {{ $userRegistration->registration_code }} · {{ $userRegistration->status }}</p>
            </div>
            <a href="{{ route('participant.tickets.show', $userRegistration->id) }}" class="btn btn-primary mt-4 sm:mt-0">Lihat tiket</a>
        </div>
    @endif

    <div class="mt-10 grid gap-10 lg:grid-cols-[1.35fr_.65fr]">
        <section class="space-y-12">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tight">Tentang event</h2>
                <div class="mt-4 max-w-3xl whitespace-pre-line text-base leading-relaxed text-muted sm:text-lg">{{ $event->description }}</div>
            </div>

            <div>
                <h2 class="text-2xl font-black uppercase tracking-tight">Ulasan</h2>
                @auth
                    @php
                        $hasAttended = \App\Models\Registration::where('event_id', $event->id)
                            ->where('user_id', Auth::id())
                            ->whereIn('status', ['ATTENDED', 'COMPLETED'])
                            ->exists();
                        $myReview = \App\Models\Review::where('event_id', $event->id)->where('user_id', Auth::id())->first();
                    @endphp
                    @if($hasAttended)
                        <form action="{{ route('events.reviews.store', $event->id) }}" method="POST" class="mt-6 card space-y-4 p-5">
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

                <div class="mt-6 divide-y divide-line border-y border-line">
                    @forelse($event->reviews as $review)
                        <div class="py-5">
                            <div class="flex items-center justify-between gap-4">
                                <p class="font-bold">{{ $review->user->name }}</p>
                                <p class="text-sm font-bold uppercase tracking-wide text-muted">{{ $review->rating }}/5</p>
                            </div>
                            <p class="mt-2 text-base leading-relaxed text-muted">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="py-6 text-muted">Belum ada ulasan.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <aside class="lg:sticky lg:top-24 lg:self-start">
            <div class="border border-ink bg-ink p-6 text-field-4-ink">
                <h2 class="text-xl font-black uppercase tracking-tight">Tiket</h2>
                <div class="mt-5 space-y-4">
                    @forelse($event->tickets as $ticket)
                        @php $isAvailable = $ticket->isAvailable(); @endphp
                        <div class="border border-white/20 bg-white/5 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-bold text-white">{{ $ticket->name }}</h3>
                                    <p class="mt-1 text-xs text-white/70">Sisa {{ $ticket->available_quota }}/{{ $ticket->quota }} · s/d {{ $ticket->end_sale->format('d M Y') }}</p>
                                </div>
                                <p class="text-sm font-bold text-white whitespace-nowrap">@if($ticket->price == 0) Gratis @else Rp {{ number_format($ticket->price, 0, ',', '.') }} @endif</p>
                            </div>
                            <div class="mt-4">
                                @if($userRegistration)
                                    <span class="inline-flex border border-white/30 px-4 py-2 text-sm font-bold uppercase tracking-wide text-white/70">Terdaftar</span>
                                @elseif($isAvailable)
                                    <a href="{{ route('events.checkout', ['event' => $event->id, 'ticket' => $ticket->id]) }}" class="btn btn-primary w-full">Daftar</a>
                                @else
                                    <span class="inline-flex w-full justify-center border border-white/30 px-4 py-2 text-sm font-bold uppercase tracking-wide text-white/70">{{ $ticket->isSoldOut() ? 'Habis' : 'Ditutup' }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-white/70">Belum ada tiket tersedia.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
