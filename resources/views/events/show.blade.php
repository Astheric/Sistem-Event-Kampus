@extends('layouts.app')

@section('title', $event->title . ' — EventHub')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-muted hover:text-ink"><x-icon name="chevron-left" class="size-4" /> Kembali ke daftar event</a>

    <div class="mt-6 grid gap-8 lg:grid-cols-[1.4fr_.8fr]">
        <section class="space-y-6">
            <div class="card p-6 sm:p-8">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="badge bg-brand-50 text-brand-700">{{ $event->category->name }}</span>
                    <span class="badge bg-surface-soft text-muted border border-line">{{ $event->status }}</span>
                    @if($event->average_rating > 0)
                        <span class="badge bg-amber-50 text-amber-700"><x-icon name="star" class="size-3.5" /> {{ number_format($event->average_rating, 1) }} ({{ $event->review_count }})</span>
                    @endif
                </div>

                <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-ink sm:text-4xl">{{ $event->title }}</h1>

                <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[14px] border border-line bg-surface-soft p-4">
                        <dt class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-muted"><x-icon name="calendar" class="size-4" /> Waktu</dt>
                        <dd class="mt-2 text-sm font-semibold text-ink">{{ $event->start_time->translatedFormat('l, d F Y') }}</dd>
                        <dd class="mt-1 text-sm text-muted">{{ $event->start_time->format('H:i') }} – {{ $event->end_time->format('H:i') }} WIB</dd>
                    </div>
                    <div class="rounded-[14px] border border-line bg-surface-soft p-4">
                        <dt class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-muted"><x-icon name="map-pin" class="size-4" /> Lokasi</dt>
                        <dd class="mt-2 text-sm font-semibold text-ink">{{ $event->venue ? $event->venue->name : 'Daring / Online' }}</dd>
                        @if($event->venue)
                            <dd class="mt-1 text-sm text-muted">{{ $event->venue->address }}, {{ $event->venue->city }}</dd>
                            @if($event->venue->maps_url)
                                <a href="{{ $event->venue->maps_url }}" target="_blank" class="mt-2 inline-flex text-sm font-semibold text-brand-700 hover:text-brand-600">Buka peta</a>
                            @endif
                        @endif
                    </div>
                    <div class="rounded-[14px] border border-line bg-surface-soft p-4 sm:col-span-2">
                        <dt class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-muted"><x-icon name="users" class="size-4" /> Penyelenggara</dt>
                        <dd class="mt-2 text-sm font-semibold text-ink">{{ $event->organization->name }}</dd>
                    </div>
                </dl>
            </div>

            @if($userRegistration)
                <div class="rounded-[16px] border border-green-200 bg-green-50 p-5 sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-green-800">Kamu sudah terdaftar di event ini</p>
                            <p class="mt-1 text-sm text-green-700">Kode: <span class="font-semibold">{{ $userRegistration->registration_code }}</span> · Status: {{ $userRegistration->status }}</p>
                        </div>
                        <a href="{{ route('participant.tickets.show', $userRegistration->id) }}" class="btn btn-primary">Lihat tiket</a>
                    </div>
                </div>
            @endif

            <div class="card p-6 sm:p-8">
                <h2 class="text-xl font-bold text-ink">Tentang event</h2>
                <div class="mt-4 whitespace-pre-line text-sm leading-relaxed text-muted sm:text-base">{{ $event->description }}</div>
            </div>

            <div class="card p-6 sm:p-8">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-ink">Ulasan peserta</h2>
                        <p class="mt-1 text-sm text-muted">Rating {{ number_format($event->average_rating, 1) }} dari {{ $event->review_count }} ulasan.</p>
                    </div>
                </div>

                @auth
                    @php
                        $hasAttended = \App\Models\Registration::where('event_id', $event->id)
                            ->where('user_id', Auth::id())
                            ->whereIn('status', ['ATTENDED', 'COMPLETED'])
                            ->exists();
                        $myReview = \App\Models\Review::where('event_id', $event->id)->where('user_id', Auth::id())->first();
                    @endphp
                    @if($hasAttended)
                        <form action="{{ route('events.reviews.store', $event->id) }}" method="POST" class="mt-6 rounded-[14px] border border-line bg-surface-soft p-5 space-y-4">
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
                        <div class="rounded-[14px] border border-line p-4">
                            <div class="flex items-center justify-between gap-4">
                                <p class="text-sm font-semibold text-ink">{{ $review->user->name }}</p>
                                <p class="text-sm font-medium text-amber-700">{{ $review->rating }}/5</p>
                            </div>
                            <p class="mt-2 text-sm leading-relaxed text-muted">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-muted">Belum ada ulasan.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
            <div class="card p-6">
                <h2 class="text-xl font-bold text-ink">Tiket & pendaftaran</h2>
                <div class="mt-5 space-y-4">
                    @forelse($event->tickets as $ticket)
                        @php $isAvailable = $ticket->isAvailable(); @endphp
                        <div class="rounded-[14px] border border-line p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-bold text-ink">{{ $ticket->name }}</h3>
                                    <p class="mt-1 text-xs text-muted">Sisa {{ $ticket->available_quota }} dari {{ $ticket->quota }} · penjualan s/d {{ $ticket->end_sale->format('d M Y') }}</p>
                                </div>
                                <div class="text-right text-sm font-bold text-ink whitespace-nowrap">
                                    @if($ticket->price == 0) Gratis @else Rp {{ number_format($ticket->price, 0, ',', '.') }} @endif
                                </div>
                            </div>
                            <div class="mt-4">
                                @if($userRegistration)
                                    <span class="inline-flex rounded-full bg-surface-soft px-4 py-2 text-sm font-semibold text-muted">Sudah terdaftar</span>
                                @elseif($isAvailable)
                                    <a href="{{ route('events.checkout', ['event' => $event->id, 'ticket' => $ticket->id]) }}" class="btn btn-primary w-full">Daftar / pesan tiket</a>
                                @else
                                    <span class="inline-flex w-full justify-center rounded-full bg-surface-soft px-4 py-2 text-sm font-semibold text-muted">{{ $ticket->isSoldOut() ? 'Tiket habis' : 'Penjualan ditutup' }}</span>
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
