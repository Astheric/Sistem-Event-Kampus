@extends('layouts.app')

@section('title', 'EventHub — Platform Pengelolaan Event Kampus Terpadu')

@section('content')
<div class="space-y-16 py-8">
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 bg-[#FFE600] border-2 border-black px-3 py-1 text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000]">
                    <span>⚡ THE ULTIMATE CAMPUS EVENT PLATFORM</span>
                </div>

                <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-[1.05]">
                    CREATE.<br>
                    <span class="bg-[#00F0FF] px-2 py-0.5 border-3 border-black shadow-[4px_4px_0px_0px_#000] inline-block mt-1">MANAGE.</span><br>
                    JOIN EVENT.
                </h1>

                <p class="text-base sm:text-lg font-bold text-zinc-700 max-w-xl leading-relaxed">
                    Sistem satu pintu untuk seluruh aktivitas kampus. Temukan seminar, workshop, festival, dan kompetisi. Dapatkan tiket QR real-time dan unduh e-sertifikat terverifikasi langsung setelah hadir!
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ route('events.index') }}"
                        class="bg-[#FFE600] hover:bg-[#FFD700] border-3 border-black px-6 py-4 font-black text-base uppercase tracking-wider shadow-[5px_5px_0px_0px_#000] hover:-translate-y-0.5 active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                        🔍 Jelajah Katalog Event →
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-white hover:bg-zinc-100 border-3 border-black px-6 py-4 font-black text-base uppercase tracking-wider shadow-[5px_5px_0px_0px_#000] hover:-translate-y-0.5 active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                        🏢 Buat Event Kampus
                    </a>
                </div>

                <!-- Feature Badges -->
                <div class="flex flex-wrap items-center gap-3 pt-4 text-xs font-black">
                    <span class="bg-white border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000]">
                        ✓ Anti-Double Check-in
                    </span>
                    <span class="bg-[#57EB74] border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000]">
                        ✓ E-Sertifikat Otomatis
                    </span>
                    <span class="bg-[#FF70A6] border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000]">
                        ✓ Verifikasi Publik
                    </span>
                </div>
            </div>

            <!-- Hero Graphic Card (Neo-Brutalism Ticket Mockup) -->
            <div class="lg:col-span-5">
                <div class="bg-white border-4 border-black p-6 shadow-[10px_10px_0px_0px_#000] relative">
                    <!-- Sticker -->
                    <div class="absolute -top-4 -right-4 bg-[#FF6B4A] text-white border-3 border-black px-3 py-1 text-xs font-black uppercase tracking-widest rotate-6 shadow-[3px_3px_0px_0px_#000]">
                        TIKET RESMI
                    </div>

                    <div class="border-b-3 border-black pb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">EVENT RESMI KAMPUS</span>
                        <h3 class="text-xl font-black mt-1 leading-snug">National Tech Summit 2026: AI & Cyber Security Horizons</h3>
                        <p class="text-xs font-bold text-zinc-600 mt-1">Oleh: BEM Fakultas Ilmu Komputer</p>
                    </div>

                    <div class="py-4 space-y-3 text-xs font-bold border-b-3 border-dashed border-black">
                        <div class="flex justify-between">
                            <span class="text-zinc-500">WAKTU:</span>
                            <span class="font-mono font-black">14 Hari Lagi • 09:00 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">LOKASI:</span>
                            <span class="font-black">Auditorium Graha Sasana</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">KODE TIKET:</span>
                            <span class="font-mono bg-[#FFE600] px-1 border border-black font-black">EVT-2026-TK8812</span>
                        </div>
                    </div>

                    <!-- Bottom Mockup Details with QR Frame -->
                    <div class="pt-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase text-zinc-500">STATUS TIKET:</span>
                            <div class="bg-[#57EB74] border-2 border-black px-2 py-0.5 text-xs font-black uppercase inline-block mt-0.5">
                                CONFIRMED
                            </div>
                        </div>

                        <!-- Simulated QR Code Icon Box -->
                        <div class="w-16 h-16 bg-[#00F0FF] border-3 border-black p-1 flex items-center justify-center font-mono text-[10px] font-black shadow-[3px_3px_0px_0px_#000] text-center">
                            QR SCAN VALID
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Platform Stats Bar -->
    <section class="border-y-4 border-black bg-[#FFE600] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="bg-white border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000]">
                    <div class="text-3xl sm:text-4xl font-black font-mono">{{ $stats['total_events'] }}</div>
                    <div class="text-xs font-black uppercase mt-1">Event Terlaksana</div>
                </div>
                <div class="bg-white border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000]">
                    <div class="text-3xl sm:text-4xl font-black font-mono">{{ $stats['total_participants'] }}</div>
                    <div class="text-xs font-black uppercase mt-1">Mahasiswa Bergabung</div>
                </div>
                <div class="bg-white border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000]">
                    <div class="text-3xl sm:text-4xl font-black font-mono">{{ $stats['certificates_issued'] }}</div>
                    <div class="text-xs font-black uppercase mt-1">Sertifikat Terbit</div>
                </div>
                <div class="bg-white border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000]">
                    <div class="text-3xl sm:text-4xl font-black font-mono">{{ $stats['active_organizers'] }}</div>
                    <div class="text-xs font-black uppercase mt-1">Organisasi Kampus</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <span class="bg-[#4DEEEA] border-2 border-black px-3 py-1 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                    Kategori Pilihan
                </span>
                <h2 class="text-3xl font-black mt-2">Temukan Minat & Bakatmu</h2>
            </div>
            <a href="{{ route('events.index') }}" class="font-black text-sm hover:underline flex items-center gap-1">
                Lihat Semua Kategori →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('events.index', ['category' => $category->slug]) }}"
                    class="bg-white border-3 border-black p-4 shadow-[4px_4px_0px_0px_#000] hover:bg-[#FFE600] hover:-translate-y-1 active:translate-x-1 active:translate-y-1 active:shadow-none transition-all text-center group">
                    <span class="text-3xl block mb-2">{{ $category->icon ?? '📌' }}</span>
                    <h3 class="font-black text-sm group-hover:underline">{{ $category->name }}</h3>
                    <span class="inline-block mt-2 text-[10px] font-mono font-bold bg-black text-white px-2 py-0.5">
                        {{ $category->events_count }} Event
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Featured Events Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <span class="bg-[#FF70A6] border-2 border-black px-3 py-1 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                    Mendatang
                </span>
                <h2 class="text-3xl font-black mt-2">Event Pilihan Terhangat</h2>
            </div>
            <a href="{{ route('events.index') }}" class="font-black text-sm hover:underline flex items-center gap-1">
                Buka Katalog Lengkap →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredEvents as $event)
                <div class="bg-white border-4 border-black shadow-[6px_6px_0px_0px_#000] flex flex-col justify-between hover:-translate-y-1 transition-transform">
                    <div>
                        <!-- Card Header & Badges -->
                        <div class="p-4 border-b-3 border-black bg-zinc-50 flex items-center justify-between">
                            <span class="bg-[#00F0FF] border-2 border-black px-2 py-0.5 text-xs font-black uppercase">
                                {{ $event->category->name }}
                            </span>
                            @if($event->isFree())
                                <span class="bg-[#57EB74] border-2 border-black px-2 py-0.5 text-xs font-black uppercase font-mono">
                                    GRATIS
                                </span>
                            @else
                                <span class="bg-[#FFE600] border-2 border-black px-2 py-0.5 text-xs font-black uppercase font-mono">
                                    Mulai Rp {{ number_format($event->min_price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 space-y-4">
                            <h3 class="text-xl font-black leading-snug line-clamp-2 hover:text-[#FF6B4A]">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <p class="text-xs font-bold text-zinc-600 line-clamp-3 leading-relaxed">
                                {{ $event->description }}
                            </p>

                            <div class="pt-2 border-t-2 border-dashed border-black space-y-2 text-xs font-bold">
                                <div class="flex items-center gap-2">
                                    <span>📅</span>
                                    <span class="font-mono">{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>📍</span>
                                    <span class="truncate">{{ $event->venue ? $event->venue->name : 'Daring / Online' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>🏢</span>
                                    <span class="text-zinc-500 truncate">{{ $event->organization->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Button -->
                    <div class="p-4 border-t-3 border-black bg-zinc-50">
                        <a href="{{ route('events.show', $event->slug) }}"
                            class="block w-full text-center bg-[#FFE600] hover:bg-[#ffd000] border-2 border-black py-2.5 font-black text-sm uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                            Lihat Tiket & Detail →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-white border-3 border-black shadow-[4px_4px_0px_0px_#000] p-6">
                    <p class="font-black text-base">Belum ada event mendatang yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Past Events & Testimonials -->
    @if($pastEvents->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <span class="bg-[#57EB74] border-2 border-black px-3 py-1 text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000]">
                    Evaluasi & Ulasan
                </span>
                <h2 class="text-3xl font-black mt-2">Event Sukses Sebelumnya</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($pastEvents as $past)
                    <div class="bg-white border-3 border-black p-5 shadow-[5px_5px_0px_0px_#000]">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-black text-white px-2 py-0.5 text-[10px] font-black uppercase font-mono">SELESAI</span>
                            <div class="flex text-amber-500 font-black text-sm">
                                ★ {{ number_format($past->average_rating, 1) }} ({{ $past->review_count }})
                            </div>
                        </div>
                        <h4 class="font-black text-base leading-snug line-clamp-2">{{ $past->title }}</h4>
                        @if($past->reviews->isNotEmpty())
                            <p class="mt-3 text-xs italic font-bold text-zinc-700 bg-zinc-100 p-3 border-2 border-black">
                                "{{ mb_strimwidth($past->reviews->first()->comment, 0, 100, '...') }}"
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
