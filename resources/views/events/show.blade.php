@extends('layouts.app')

@section('title', $event->title . ' — EventHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs font-black uppercase text-zinc-500">
        <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
        <span>/</span>
        <a href="{{ route('events.index') }}" class="hover:underline">Event</a>
        <span>/</span>
        <span class="text-black truncate max-w-xs">{{ $event->title }}</span>
    </div>

    <!-- Event Header Card -->
    <div class="bg-white border-4 border-black p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000]">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="bg-[#00F0FF] border-2 border-black px-3 py-1 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                {{ $event->category->name }}
            </span>
            <span class="bg-black text-white px-3 py-1 text-xs font-black uppercase font-mono">
                STATUS: {{ $event->status }}
            </span>
            @if($event->average_rating > 0)
                <span class="bg-[#FFE600] border-2 border-black px-3 py-1 text-xs font-black shadow-[2px_2px_0px_0px_#000]">
                    ★ {{ number_format($event->average_rating, 1) }} ({{ $event->review_count }} Ulasan)
                </span>
            @endif
        </div>

        <h1 class="text-3xl sm:text-5xl font-black leading-tight">{{ $event->title }}</h1>

        <!-- Key Meta Strip -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 pt-6 border-t-3 border-black text-xs font-bold">
            <div class="flex items-start gap-3 bg-[#FFFDF8] border-2 border-black p-3 shadow-[3px_3px_0px_0px_#000]">
                <span class="text-2xl">📅</span>
                <div>
                    <span class="text-[10px] text-zinc-500 uppercase font-black">WAKTU ACARA:</span>
                    <p class="font-black text-sm">{{ $event->start_time->format('d M Y, H:i') }} WIB</p>
                    <p class="text-zinc-500">Sampai {{ $event->end_time->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>

            <div class="flex items-start gap-3 bg-[#FFFDF8] border-2 border-black p-3 shadow-[3px_3px_0px_0px_#000]">
                <span class="text-2xl">📍</span>
                <div>
                    <span class="text-[10px] text-zinc-500 uppercase font-black">LOKASI VENUE:</span>
                    <p class="font-black text-sm">{{ $event->venue ? $event->venue->name : 'Daring (Online)' }}</p>
                    @if($event->venue)
                        <p class="text-zinc-500">{{ $event->venue->address }}, {{ $event->venue->city }}</p>
                        @if($event->venue->maps_url)
                            <a href="{{ $event->venue->maps_url }}" target="_blank" class="text-[#FF6B4A] underline font-black text-[11px] mt-1 inline-block">
                                Buka Google Maps ↗
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <div class="flex items-start gap-3 bg-[#FFFDF8] border-2 border-black p-3 shadow-[3px_3px_0px_0px_#000]">
                <span class="text-2xl">🏢</span>
                <div>
                    <span class="text-[10px] text-zinc-500 uppercase font-black">PENYELENGGARA:</span>
                    <p class="font-black text-sm">{{ $event->organization->name }}</p>
                    <p class="text-zinc-500">{{ $event->organization->contact ?? 'Resmi Kampus' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert if already registered (BR-01) -->
    @if($userRegistration)
        <div class="bg-[#57EB74] border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <span class="text-4xl">🎟️</span>
                <div>
                    <h3 class="font-black text-lg">Anda Sudah Terdaftar pada Event Ini!</h3>
                    <p class="text-xs font-bold">Kode Registrasi: <span class="font-mono font-black bg-black text-white px-2 py-0.5">{{ $userRegistration->registration_code }}</span> | Status: {{ $userRegistration->status }}</p>
                </div>
            </div>
            <a href="{{ route('participant.tickets.show', $userRegistration->id) }}"
                class="bg-white hover:bg-zinc-100 border-3 border-black px-4 py-2.5 font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap">
                Lihat E-Tiket & QR Code →
            </a>
        </div>
    @endif

    <!-- Main Grid: Description & Ticket Selection -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Event Details Description (2 Cols) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white border-4 border-black p-6 sm:p-8 shadow-[6px_6px_0px_0px_#000]">
                <h2 class="text-2xl font-black mb-4 border-b-3 border-black pb-2">Deskripsi & Rincian Event</h2>
                <div class="prose max-w-none text-sm sm:text-base font-bold text-zinc-800 leading-relaxed whitespace-pre-line">
                    {{ $event->description }}
                </div>
            </div>

            <!-- Reviews & Rating Section (FR-15) -->
            <div class="bg-white border-4 border-black p-6 sm:p-8 shadow-[6px_6px_0px_0px_#000] space-y-6">
                <div class="flex items-center justify-between border-b-3 border-black pb-4">
                    <div>
                        <h2 class="text-2xl font-black">Ulasan & Rating Peserta</h2>
                        <p class="text-xs font-bold text-zinc-500">Khusus ulasan dari peserta yang terverifikasi hadir.</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-black text-amber-500 font-mono">
                            ★ {{ number_format($event->average_rating, 1) }}
                        </div>
                        <span class="text-xs font-bold text-zinc-500">dari {{ $event->review_count }} ulasan</span>
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
                        <div class="bg-[#FFFDF8] border-3 border-black p-5 shadow-[4px_4px_0px_0px_#000]">
                            <h3 class="font-black text-sm uppercase mb-2">
                                {{ $myReview ? 'Perbarui Ulasan Anda' : 'Tulis Ulasan Pengalaman Anda' }}
                            </h3>
                            <form action="{{ route('events.reviews.store', $event->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block font-black text-xs uppercase mb-1">Rating Bintang (1 - 5)</label>
                                    <select name="rating" class="bg-white border-2 border-black p-2 font-black text-sm">
                                        <option value="5" {{ ($myReview?->rating ?? 5) == 5 ? 'selected' : '' }}>★★★★★ (5 - Sangat Puas)</option>
                                        <option value="4" {{ ($myReview?->rating ?? 5) == 4 ? 'selected' : '' }}>★★★★☆ (4 - Puas)</option>
                                        <option value="3" {{ ($myReview?->rating ?? 5) == 3 ? 'selected' : '' }}>★★★☆☆ (3 - Cukup)</option>
                                        <option value="2" {{ ($myReview?->rating ?? 5) == 2 ? 'selected' : '' }}>★★☆☆☆ (2 - Kurang)</option>
                                        <option value="1" {{ ($myReview?->rating ?? 5) == 1 ? 'selected' : '' }}>★☆☆☆☆ (1 - Sangat Kurang)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-black text-xs uppercase mb-1">Komentar / Saran</label>
                                    <textarea name="comment" rows="3" required
                                        placeholder="Bagikan pengalaman Anda mengenai pembicara, fasilitas, atau check-in..."
                                        class="w-full bg-white border-2 border-black p-2.5 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">{{ $myReview?->comment }}</textarea>
                                </div>
                                <button type="submit"
                                    class="bg-[#FFE600] border-2 border-black px-4 py-2 font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-1 active:translate-y-1">
                                    Simpan Ulasan
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                <!-- Reviews Feed -->
                <div class="space-y-4">
                    @forelse($event->reviews as $review)
                        <div class="border-2 border-black p-4 bg-zinc-50 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-[#4DEEEA] border border-black flex items-center justify-center font-black text-xs uppercase">
                                        {{ substr($review->user->name, 0, 2) }}
                                    </div>
                                    <span class="font-black text-xs">{{ $review->user->name }}</span>
                                    <span class="bg-[#57EB74] text-black text-[9px] font-black px-1.5 py-0.2 border border-black uppercase">Terverifikasi Hadir</span>
                                </div>
                                <div class="text-amber-500 font-black text-sm">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                            </div>
                            <p class="text-xs font-bold text-zinc-700 leading-relaxed">{{ $review->comment }}</p>
                            <span class="text-[10px] font-mono text-zinc-500 block">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-zinc-500 text-center py-6">Belum ada ulasan untuk event ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Side: Tiered Ticket Options (FR-06) -->
        <div class="space-y-6">
            <div class="bg-white border-4 border-black p-6 shadow-[6px_6px_0px_0px_#000] sticky top-28">
                <div class="border-b-3 border-black pb-3 mb-4">
                    <span class="bg-[#FFE600] border-2 border-black px-2 py-0.5 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                        Pilihan Tiket
                    </span>
                    <h2 class="text-xl font-black mt-2">Daftar Tiket Tersedia</h2>
                    <p class="text-xs font-bold text-zinc-500">Pilih tier tiket yang sesuai dengan kebutuhan Anda.</p>
                </div>

                <div class="space-y-4">
                    @forelse($event->tickets as $ticket)
                        @php
                            $isAvailable = $ticket->isAvailable();
                            $percentRemaining = $ticket->quota > 0 ? round(($ticket->available_quota / $ticket->quota) * 100) : 0;
                        @endphp

                        <div class="border-3 border-black p-4 {{ $isAvailable ? 'bg-white' : 'bg-zinc-100 opacity-80' }} space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h4 class="font-black text-base leading-tight">{{ $ticket->name }}</h4>
                                    <p class="text-xs text-zinc-500 font-mono mt-0.5">
                                        s/d {{ $ticket->end_sale->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @if($ticket->price == 0)
                                        <span class="bg-[#57EB74] border-2 border-black px-2 py-0.5 font-black text-xs font-mono inline-block">
                                            GRATIS
                                        </span>
                                    @else
                                        <span class="bg-[#FFE600] border-2 border-black px-2 py-0.5 font-black text-xs font-mono inline-block">
                                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Quota Progress Bar -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-[11px] font-bold">
                                    <span class="text-zinc-600">Sisa Kuota:</span>
                                    <span class="font-mono font-black {{ $ticket->available_quota < 10 ? 'text-[#FF6B4A]' : '' }}">
                                        {{ $ticket->available_quota }} / {{ $ticket->quota }} tiket
                                    </span>
                                </div>
                                <div class="w-full bg-zinc-200 border-2 border-black h-2.5 overflow-hidden">
                                    <div class="bg-[#00F0FF] h-full" style="width: {{ $percentRemaining }}%"></div>
                                </div>
                            </div>

                            <!-- Booking CTA -->
                            @if($userRegistration)
                                <button disabled class="w-full bg-zinc-300 border-2 border-black p-2 font-black text-xs uppercase cursor-not-allowed">
                                    Sudah Terdaftar
                                </button>
                            @elseif($isAvailable)
                                <a href="{{ route('events.checkout', ['event' => $event->id, 'ticket' => $ticket->id]) }}"
                                    class="block w-full text-center bg-[#FFE600] hover:bg-[#ffd000] border-2 border-black p-2.5 font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                                    Pesan Tiket Ini →
                                </a>
                            @else
                                <div class="w-full text-center bg-[#FF70A6] text-black border-2 border-black p-2 font-black text-xs uppercase">
                                    {{ $ticket->isSoldOut() ? 'Kuota Habis' : 'Penjualan Ditutup' }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs font-bold text-zinc-500 py-4 text-center">Belum ada tipe tiket yang dipublikasikan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
