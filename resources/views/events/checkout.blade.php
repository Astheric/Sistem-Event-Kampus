@extends('layouts.app')

@section('title', 'Pendaftaran Tiket — ' . $event->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ paymentMethod: 'Bank Transfer (BCA)' }">
    <div class="border-b-4 border-black pb-4 mb-8">
        <span class="bg-[#FFE600] border-2 border-black px-3 py-1 text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000]">
            Konfirmasi Pendaftaran
        </span>
        <h1 class="text-3xl sm:text-4xl font-black mt-2">Pemesanan Tiket Event</h1>
        <p class="text-sm font-bold text-zinc-600 mt-1">Lengkapi data pendaftaran dan selesaikan simulasi pembayaran.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Summary Box -->
        <div class="md:col-span-1 space-y-4">
            <div class="bg-white border-4 border-black p-5 shadow-[6px_6px_0px_0px_#000] space-y-4">
                <span class="bg-black text-white px-2 py-0.5 text-[10px] font-black uppercase font-mono">RINGKASAN EVENT</span>

                <div>
                    <h3 class="font-black text-base leading-snug">{{ $event->title }}</h3>
                    <p class="text-xs font-bold text-zinc-500 mt-1">{{ $event->organization->name }}</p>
                </div>

                <div class="border-t-2 border-dashed border-black pt-3 space-y-2 text-xs font-bold">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Tipe Tiket:</span>
                        <span class="font-black">{{ $ticket->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Jadwal:</span>
                        <span class="font-mono">{{ $event->start_time->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Lokasi:</span>
                        <span class="truncate max-w-[120px]">{{ $event->venue ? $event->venue->name : 'Online' }}</span>
                    </div>
                </div>

                <!-- Price Box -->
                <div class="border-t-3 border-black pt-3 bg-zinc-50 -mx-5 -mb-5 p-5">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-xs uppercase">Total Bayar:</span>
                        @if($ticket->price == 0)
                            <span class="font-black text-lg font-mono text-[#2e9c45]">GRATIS</span>
                        @else
                            <span class="font-black text-xl font-mono text-[#000]">
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
                class="bg-white border-4 border-black p-6 sm:p-8 shadow-[6px_6px_0px_0px_#000] space-y-6">
                @csrf

                <!-- Attendee Info -->
                <div class="border-b-3 border-black pb-4">
                    <h3 class="font-black text-base uppercase mb-3">1. Data Peserta Terdaftar</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-bold">
                        <div class="bg-zinc-100 p-3 border-2 border-black">
                            <span class="text-[10px] text-zinc-500 uppercase block font-black">Nama Lengkap</span>
                            <span class="font-black text-sm">{{ $user->name }}</span>
                        </div>
                        <div class="bg-zinc-100 p-3 border-2 border-black">
                            <span class="text-[10px] text-zinc-500 uppercase block font-black">Alamat Email</span>
                            <span class="font-black text-sm truncate block">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method (FR-08) -->
                @if($ticket->price > 0)
                    <div class="border-b-3 border-black pb-4 space-y-4">
                        <h3 class="font-black text-base uppercase">2. Simulasi Metode Pembayaran</h3>
                        <p class="text-xs font-bold text-zinc-600">Pilih salah satu metode simulasi transfer internal di bawah ini:</p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="cursor-pointer border-3 border-black p-3 text-center transition-all"
                                :class="paymentMethod === 'Bank Transfer (BCA)' ? 'bg-[#FFE600] shadow-[3px_3px_0px_0px_#000] font-black' : 'bg-zinc-50 font-bold'">
                                <input type="radio" name="payment_method" value="Bank Transfer (BCA)" x-model="paymentMethod" class="sr-only">
                                <span class="block text-xs uppercase">🏦 Transfer BCA</span>
                            </label>

                            <label class="cursor-pointer border-3 border-black p-3 text-center transition-all"
                                :class="paymentMethod === 'Bank Transfer (Mandiri)' ? 'bg-[#4DEEEA] shadow-[3px_3px_0px_0px_#000] font-black' : 'bg-zinc-50 font-bold'">
                                <input type="radio" name="payment_method" value="Bank Transfer (Mandiri)" x-model="paymentMethod" class="sr-only">
                                <span class="block text-xs uppercase">🏦 Transfer Mandiri</span>
                            </label>

                            <label class="cursor-pointer border-3 border-black p-3 text-center transition-all"
                                :class="paymentMethod === 'E-Wallet (GoPay/Dana)' ? 'bg-[#57EB74] shadow-[3px_3px_0px_0px_#000] font-black' : 'bg-zinc-50 font-bold'">
                                <input type="radio" name="payment_method" value="E-Wallet (GoPay/Dana)" x-model="paymentMethod" class="sr-only">
                                <span class="block text-xs uppercase">📱 E-Wallet</span>
                            </label>
                        </div>

                        <!-- Account Details Box -->
                        <div class="bg-[#FFFDF8] border-2 border-black p-4 space-y-2 text-xs font-bold">
                            <p class="font-black text-xs uppercase">Petunjuk Pembayaran Simulasi:</p>
                            <div x-show="paymentMethod === 'Bank Transfer (BCA)'">
                                <p>Silakan transfer ke rekening BCA: <span class="font-mono font-black bg-[#FFE600] px-1 border border-black">8830-1928-3921</span> a/n EventHub Kampus</p>
                            </div>
                            <div x-show="paymentMethod === 'Bank Transfer (Mandiri)'">
                                <p>Silakan transfer ke Mandiri: <span class="font-mono font-black bg-[#4DEEEA] px-1 border border-black">132-00-9831-291</span> a/n EventHub Kampus</p>
                            </div>
                            <div x-show="paymentMethod === 'E-Wallet (GoPay/Dana)'">
                                <p>Scan / Transfer ke nomor E-Wallet: <span class="font-mono font-black bg-[#57EB74] px-1 border border-black">0821-9876-5432</span> (Admin Kampus)</p>
                            </div>
                            <p class="text-[11px] text-zinc-500 italic">*Proyek ini menggunakan simulasi internal. Unggah bukti transfer uji coba / screenshot untuk diverifikasi panitia.</p>
                        </div>

                        <!-- Proof of Payment Upload -->
                        <div>
                            <label class="block font-black text-xs uppercase mb-1">Unggah Bukti Pembayaran Simulasi (Foto / Screenshot)</label>
                            <input type="file" name="proof_file" accept="image/*"
                                class="w-full bg-white border-2 border-black p-2 font-bold text-xs focus:outline-none">
                            <p class="text-[10px] text-zinc-500 font-bold mt-1">Format: JPG, PNG, WEBP (Maksimal 3MB)</p>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="payment_method" value="Gratis">
                    <div class="bg-[#57EB74] border-2 border-black p-4 text-xs font-black">
                        🎉 Tiket ini GRATIS! Tidak memerlukan pembayaran. E-Tiket akan langsung diterbitkan secara instan setelah konfirmasi.
                    </div>
                @endif

                <!-- Notes -->
                <div>
                    <label class="block font-black text-xs uppercase mb-1">Catatan Tambahan untuk Panitia (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Alergi makanan khusus, perwakilan tim, dll..."
                        class="w-full bg-[#FFFDF8] border-2 border-black p-2.5 font-bold text-xs focus:outline-none focus:bg-[#FFE600]">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-[#FFE600] hover:bg-[#ffd700] border-3 border-black p-4 font-black text-base uppercase tracking-wider shadow-[4px_4px_0px_0px_#000] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all">
                    Konfirmasi & Terbitkan Tiket 🎟️
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
