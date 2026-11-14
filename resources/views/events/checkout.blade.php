@extends('layouts.app')

@section('title', 'Pendaftaran Tiket — ' . $event->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ paymentMethod: 'Bank Transfer (BCA)' }">
    <div class="border-b border-line pb-4 mb-8">
        <span class="bg-surface border border-line px-3 py-1 text-xs font-bold  tracking-wider shadow-card rounded-2xl">
            Konfirmasi Pendaftaran
        </span>
        <h1 class="text-3xl sm:text-4xl font-bold mt-2">Pemesanan Tiket Event</h1>
        <p class="text-sm font-bold text-muted mt-1">Lengkapi data pendaftaran dan selesaikan simulasi pembayaran.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Summary Box -->
        <div class="md:col-span-1 space-y-4">
            <div class="bg-surface border border-line p-5 shadow-card space-y-4 rounded-2xl">
                <span class="bg-danger text-white px-2 py-0.5 text-[10px] font-bold  font-mono">RINGKASAN EVENT</span>

                <div>
                    <h3 class="font-bold text-base leading-snug">{{ $event->title }}</h3>
                    <p class="text-xs font-bold text-muted mt-1">{{ $event->organization->name }}</p>
                </div>

                <div class="border-t border-dashed border-line pt-3 space-y-2 text-xs font-bold">
                    <div class="flex justify-between">
                        <span class="text-muted">Tipe Tiket:</span>
                        <span class="font-bold">{{ $ticket->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted">Jadwal:</span>
                        <span class="font-mono">{{ $event->start_time->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted">Lokasi:</span>
                        <span class="truncate max-w-[120px]">{{ $event->venue ? $event->venue->name : 'Online' }}</span>
                    </div>
                </div>

                <!-- Price Box -->
                <div class="border-t border-line pt-3 bg-surface-soft -mx-5 -mb-5 p-5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs ">Total Bayar:</span>
                        @if($ticket->price == 0)
                            <span class="font-bold text-lg font-mono text-ink">GRATIS</span>
                        @else
                            <span class="font-bold text-xl font-mono text-ink">
                                Rp {{ number_format($ticket->price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Registration & Payment Simulation -->
        <div class="md:col-span-2">
            <form action="{{ route('events.checkout.store', ['event' => $event->id, 'ticket' => $ticket->id]) }}"
                method="POST" enctype="multipart/form-data"
                class="bg-surface border border-line p-6 sm:p-8 shadow-card space-y-6 rounded-2xl">
                @csrf

                <!-- Attendee Info -->
                <div class="border-b border-line pb-4">
                    <h3 class="font-bold text-base  mb-3">1. Data Peserta Terdaftar</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-bold">
                        <div class="bg-surface-soft p-3 border border-line">
                            <span class="text-[10px] text-muted  block font-bold">Nama Lengkap</span>
                            <span class="font-bold text-sm">{{ $user->name }}</span>
                        </div>
                        <div class="bg-surface-soft p-3 border border-line">
                            <span class="text-[10px] text-muted  block font-bold">Alamat Email</span>
                            <span class="font-bold text-sm truncate block">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method (FR-08) -->
                @if($ticket->price > 0)
                    <div class="border-b border-line pb-4 space-y-4">
                        <h3 class="font-bold text-base ">2. Simulasi Metode Pembayaran</h3>
                        <p class="text-xs font-bold text-muted">Pilih salah satu metode simulasi transfer internal di bawah ini:</p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="cursor-pointer border border-line p-3 text-center transition-all"
                                :class="paymentMethod === 'Bank Transfer (BCA)' ? 'bg-surface shadow-card font-bold' : 'bg-surface-soft font-bold' rounded-2xl">
                                <input type="radio" name="payment_method" value="Bank Transfer (BCA)" x-model="paymentMethod" class="sr-only">
                                <span class="block text-xs "><x-icon name="home" class="size-4" /> Transfer BCA</span>
                            </label>

                            <label class="cursor-pointer border border-line p-3 text-center transition-all"
                                :class="paymentMethod === 'Bank Transfer (Mandiri)' ? 'bg-surface shadow-card font-bold' : 'bg-surface-soft font-bold' rounded-2xl">
                                <input type="radio" name="payment_method" value="Bank Transfer (Mandiri)" x-model="paymentMethod" class="sr-only">
                                <span class="block text-xs "><x-icon name="home" class="size-4" /> Transfer Mandiri</span>
                            </label>

                            <label class="cursor-pointer border border-line p-3 text-center transition-all"
                                :class="paymentMethod === 'E-Wallet (GoPay/Dana)' ? 'bg-surface shadow-card font-bold' : 'bg-surface-soft font-bold' rounded-2xl">
                                <input type="radio" name="payment_method" value="E-Wallet (GoPay/Dana)" x-model="paymentMethod" class="sr-only">
                                <span class="block text-xs "><x-icon name="smartphone" class="size-4" /> E-Wallet</span>
                            </label>
                        </div>

                        <!-- Account Details Box -->
                        <div class="bg-surface border border-line p-4 space-y-2 text-xs font-bold">
                            <p class="font-bold text-xs ">Petunjuk Pembayaran Simulasi:</p>
                            <div x-show="paymentMethod === 'Bank Transfer (BCA)'">
                                <p>Silakan transfer ke rekening BCA: <span class="font-mono font-bold bg-surface px-1 border border-line">8830-1928-3921</span> a/n EventHub Kampus</p>
                            </div>
                            <div x-show="paymentMethod === 'Bank Transfer (Mandiri)'">
                                <p>Silakan transfer ke Mandiri: <span class="font-mono font-bold bg-surface px-1 border border-line">132-00-9831-291</span> a/n EventHub Kampus</p>
                            </div>
                            <div x-show="paymentMethod === 'E-Wallet (GoPay/Dana)'">
                                <p>Scan / Transfer ke nomor E-Wallet: <span class="font-mono font-bold bg-surface px-1 border border-line">0821-9876-5432</span> (Admin Kampus)</p>
                            </div>
                            <p class="text-[11px] text-muted italic">*Proyek ini menggunakan simulasi internal. Unggah bukti transfer uji coba / screenshot untuk diverifikasi panitia.</p>
                        </div>

                        <!-- Proof of Payment Upload -->
                        <div>
                            <label class="block font-bold text-xs  mb-1">Unggah Bukti Pembayaran Simulasi (Foto / Screenshot)</label>
                            <input type="file" name="proof_file" accept="image/*"
                                class="w-full bg-surface border border-line p-2 font-bold text-xs focus:outline-none">
                            <p class="text-[10px] text-muted font-bold mt-1">Format: JPG, PNG, WEBP (Maksimal 3MB)</p>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="payment_method" value="Gratis">
                    <div class="bg-surface border border-line p-4 text-xs font-bold">
                        <x-icon name="gift" class="size-4" /> Tiket ini GRATIS! Tidak memerlukan pembayaran. E-Tiket akan langsung diterbitkan secara instan setelah konfirmasi.
                    </div>
                @endif

                <!-- Notes -->
                <div>
                    <label class="block font-bold text-xs  mb-1">Catatan Tambahan untuk Panitia (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Alergi makanan khusus, perwakilan tim, dll..."
                        class="w-full bg-surface border border-line p-2.5 font-bold text-xs focus:outline-none focus:bg-surface">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-brand-600 text-white hover:bg-brand-700 border border-line p-4 font-bold text-base  tracking-wider shadow-card active:translate-x-1 active:translate-y-1 active:shadow-none transition-all rounded-full">
                    Konfirmasi & Terbitkan Tiket <x-icon name="tag" class="size-4" />
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
