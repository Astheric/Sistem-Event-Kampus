@extends('layouts.app')

@section('title', $event->title . ' — EventHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs font-bold  text-muted">
        <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
        <span>/</span>
        <a href="{{ route('events.index') }}" class="hover:underline">Event</a>
        <span>/</span>
        <span class="text-black truncate max-w-xs">{{ $event->title }}</span>
    </div>

    <!-- Event Header Card -->
    <div class="bg-surface border border-line p-6 sm:p-8 shadow-card rounded-2xl">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="bg-surface border border-line px-3 py-1 text-xs font-bold  shadow-card rounded-2xl">
                {{ $event->category->name }}
            </span>
            <span class="bg-danger text-white px-3 py-1 text-xs font-bold  font-mono">
                STATUS: {{ $event->status }}
            </span>
            @if($event->average_rating > 0)
                <span class="bg-surface border border-line px-3 py-1 text-xs font-bold shadow-card rounded-2xl">
                    <x-icon name="star" class="size-4" /> {{ number_format($event->average_rating, 1) }} ({{ $event->review_count }} Ulasan)
                </span>
            @endif
        </div>

        <h1 class="text-3xl sm:text-5xl font-bold leading-tight">{{ $event->title }}</h1>

        <!-- Key Meta Strip -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 pt-6 border-t border-line text-xs font-bold">
            <div class="flex items-start gap-3 bg-surface border border-line p-3 shadow-card rounded-2xl">
                <span class="text-2xl"><x-icon name="calendar" class="size-4" /></span>
                <div>
                    <span class="text-[10px] text-muted  font-bold">WAKTU ACARA:</span>
                    <p class="font-bold text-sm">{{ $event->start_time->format('d M Y, H:i') }} WIB</p>
                    <p class="text-muted">Sampai {{ $event->end_time->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>

            <div class="flex items-start gap-3 bg-surface border border-line p-3 shadow-card rounded-2xl">
                <span class="text-2xl"><x-icon name="map-pin" class="size-4" /></span>
                <div>
                    <span class="text-[10px] text-muted  font-bold">LOKASI VENUE:</span>
                    <p class="font-bold text-sm">{{ $event->venue ? $event->venue->name : 'Daring (Online)' }}</p>
                    @if($event->venue)
                        <p class="text-muted">{{ $event->venue->address }}, {{ $event->venue->city }}</p>
                        @if($event->venue->maps_url)
                            <a href="{{ $event->venue->maps_url }}" target="_blank" class="text-ink underline font-bold text-[11px] mt-1 inline-block">
                                Buka Google Maps ↗
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <div class="flex items-start gap-3 bg-surface border border-line p-3 shadow-card rounded-2xl">
                <span class="text-2xl"><x-icon name="home" class="size-4" /></span>
                <div>
                    <span class="text-[10px] text-muted  font-bold">PENYELENGGARA:</span>
                    <p class="font-bold text-sm">{{ $event->organization->name }}</p>
                    <p class="text-muted">{{ $event->organization->contact ?? 'Resmi Kampus' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert if already registered (BR-01) -->
    @if($userRegistration)
        <div class="bg-surface border border-line p-6 shadow-card flex flex-col sm:flex-row items-center justify-between gap-4 rounded-2xl">
            <div class="flex items-center gap-4">
                <span class="text-4xl"><x-icon name="tag" class="size-4" /></span>
                <div>
                    <h3 class="font-bold text-lg">Anda Sudah Terdaftar pada Event Ini!</h3>
                    <p class="text-xs font-bold">Kode Registrasi: <span class="font-mono font-bold bg-danger text-white px-2 py-0.5">{{ $userRegistration->registration_code }}</span> | Status: {{ $userRegistration->status }}</p>
                </div>
            </div>
            <a href="{{ route('participant.tickets.show', $userRegistration->id) }}"
                class="bg-brand-600 text-white hover:bg-brand-600 text-white-soft border border-line px-4 py-2.5 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap rounded-full">
                Lihat E-Tiket & QR Code →
            </a>
        </div>
    @endif

    <!-- Main Grid: Description & Ticket Selection -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Event Details Description (2 Cols) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-surface border border-line p-6 sm:p-8 shadow-card rounded-2xl">
                <h2 class="text-2xl font-bold mb-4 border-b border-line pb-2">Deskripsi & Rincian Event</h2>
                <div class="prose max-w-none text-sm sm:text-base font-bold text-muted leading-relaxed whitespace-pre-line">
                    {{ $event->description }}
                </div>
            </div>

            <!-- Reviews & Rating Section (FR-15) -->
            <div class="bg-surface border border-line p-6 sm:p-8 shadow-card space-y-6 rounded-2xl">
                <div class="flex items-center justify-between border-b border-line pb-4">
                    <div>
                        <h2 class="text-2xl font-bold">Ulasan & Rating Peserta</h2>
                        <p class="text-xs font-bold text-muted">Khusus ulasan dari peserta yang terverifikasi hadir.</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-amber-500 font-mono">
                            <x-icon name="star" class="size-4" /> {{ number_format($event->average_rating, 1) }}
                        </div>
                        <span class="text-xs font-bold text-muted">dari {{ $event->review_count }} ulasan</span>
                    </div>
                </div>

                <!-- Review Form for Attended Participants -->
                @auth
                    @php
                        $hasAttended = \App\Models\Registration::where('event_id', $event->id)
                            ->where('user_id', Auth::id())
                            ->whereIn('status', ['ATTENDED', 'COMPLETED'])
                            ->exists();
                        $myReview = \App\Models\Review::where('event_id', $event->id)->where('user_id', Auth::id())->first();
                    @endphp

                    @if($hasAttended)
                        <div class="bg-surface border border-line p-5 shadow-card rounded-2xl">
                            <h3 class="font-bold text-sm  mb-2">
                                {{ $myReview ? 'Perbarui Ulasan Anda' : 'Tulis Ulasan Pengalaman Anda' }}
                            </h3>
                            <form action="{{ route('events.reviews.store', $event->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block font-bold text-xs  mb-1">Rating Bintang (1 - 5)</label>
                                    <select name="rating" class="bg-surface border border-line p-2 font-bold text-sm">
                                        <option value="5" {{ ($myReview?->rating ?? 5) == 5 ? 'selected' : '' }}><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /> (5 - Sangat Puas)</option>
                                        <option value="4" {{ ($myReview?->rating ?? 5) == 4 ? 'selected' : '' }}><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /> (4 - Puas)</option>
                                        <option value="3" {{ ($myReview?->rating ?? 5) == 3 ? 'selected' : '' }}><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /> (3 - Cukup)</option>
                                        <option value="2" {{ ($myReview?->rating ?? 5) == 2 ? 'selected' : '' }}><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /> (2 - Kurang)</option>
                                        <option value="1" {{ ($myReview?->rating ?? 5) == 1 ? 'selected' : '' }}><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /><x-icon name="star" class="size-4" /> (1 - Sangat Kurang)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-bold text-xs  mb-1">Komentar / Saran</label>
                                    <textarea name="comment" rows="3" required
                                        placeholder="Bagikan pengalaman Anda mengenai pembicara, fasilitas, atau check-in..."
                                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">{{ $myReview?->comment }}</textarea>
                                </div>
                                <button type="submit"
                                    class="bg-brand-600 text-white border border-line px-4 py-2 font-bold text-xs  shadow-card active:translate-x-1 active:translate-y-1 rounded-full">
                                    Simpan Ulasan
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                <!-- Reviews Feed -->
                <div class="space-y-4">
                    @forelse($event->reviews as $review)
                        <div class="border border-line p-4 bg-surface-soft space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-surface border border-line flex items-center justify-center font-bold text-xs ">
                                        {{ substr($review->user->name, 0, 2) }}
                                    </div>
                                    <span class="font-bold text-xs">{{ $review->user->name }}</span>
                                    <span class="bg-surface text-black text-[9px] font-bold px-1.5 py-0.2 border border-line ">Terverifikasi Hadir</span>
                                </div>
                                <div class="text-amber-500 font-bold text-sm">
                                    {{ str_repeat('<x-icon name="star" class="size-4" />', $review->rating) }}{{ str_repeat('<x-icon name="star" class="size-4" />', 5 - $review->rating) }}
                                </div>
                            </div>
                            <p class="text-xs font-bold text-muted leading-relaxed">{{ $review->comment }}</p>
                            <span class="text-[10px] font-mono text-muted block">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-muted text-center py-6">Belum ada ulasan untuk event ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Side: Tiered Ticket Options (FR-06) -->
        <div class="space-y-6">
            <div class="bg-surface border border-line p-6 shadow-card sticky top-28 rounded-2xl">
                <div class="border-b border-line pb-3 mb-4">
                    <span class="bg-surface border border-line px-2 py-0.5 text-xs font-bold  shadow-card rounded-2xl">
                        Pilihan Tiket
                    </span>
                    <h2 class="text-xl font-bold mt-2">Daftar Tiket Tersedia</h2>
                    <p class="text-xs font-bold text-muted">Pilih tier tiket yang sesuai dengan kebutuhan Anda.</p>
                </div>

                <div class="space-y-4">
                    @forelse($event->tickets as $ticket)
                        @php
                            $isAvailable = $ticket->isAvailable();
                            $percentRemaining = $ticket->quota > 0 ? round(($ticket->available_quota / $ticket->quota) * 100) : 0;
                        @endphp

                        <div class="border border-line p-4 {{ $isAvailable ? 'bg-surface' : 'bg-surface-soft opacity-80' }} space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h4 class="font-bold text-base leading-tight">{{ $ticket->name }}</h4>
                                    <p class="text-xs text-muted font-mono mt-0.5">
                                        s/d {{ $ticket->end_sale->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @if($ticket->price == 0)
                                        <span class="bg-surface border border-line px-2 py-0.5 font-bold text-xs font-mono inline-block">
                                            GRATIS
                                        </span>
                                    @else
                                        <span class="bg-surface border border-line px-2 py-0.5 font-bold text-xs font-mono inline-block">
                                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Quota Progress Bar -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-[11px] font-bold">
                                    <span class="text-muted">Sisa Kuota:</span>
                                    <span class="font-mono font-bold {{ $ticket->available_quota < 10 ? 'text-ink' : '' }}">
                                        {{ $ticket->available_quota }} / {{ $ticket->quota }} tiket
                                    </span>
                                </div>
                                <div class="w-full bg-surface-soft border border-line h-2.5 overflow-hidden">
                                    <div class="bg-surface h-full" style="width: {{ $percentRemaining }}%"></div>
                                </div>
                            </div>

                            <!-- Booking CTA -->
                            @if($userRegistration)
                                <button disabled class="w-full bg-surface-soft border border-line p-2 font-bold text-xs  cursor-not-allowed">
                                    Sudah Terdaftar
                                </button>
                            @elseif($isAvailable)
                                <a href="{{ route('events.checkout', ['event' => $event->id, 'ticket' => $ticket->id]) }}"
                                    class="block w-full text-center bg-brand-600 text-white hover:bg-brand-600 text-white border border-line p-2.5 font-bold text-xs  tracking-wider shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                                    Pesan Tiket Ini →
                                </a>
                            @else
                                <div class="w-full text-center bg-surface text-black border border-line p-2 font-bold text-xs ">
                                    {{ $ticket->isSoldOut() ? 'Kuota Habis' : 'Penjualan Ditutup' }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs font-bold text-muted py-4 text-center">Belum ada tipe tiket yang dipublikasikan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
