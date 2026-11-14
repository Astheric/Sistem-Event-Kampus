@extends('layouts.app')

@section('title', 'Pendaftaran Tiket — ' . $event->title)

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8" x-data="{ paymentMethod: 'Bank Transfer (BCA)' }">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-ink">Pendaftaran & Pemesanan Tiket</h1>
        <p class="mt-2 text-sm text-muted">Periksa detail event dan lengkapi informasi pendaftaran sebelum konfirmasi.</p>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-[.9fr_1.1fr]">
        <aside class="card h-fit p-6 lg:sticky lg:top-24">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-muted">Ringkasan</h2>
            <h3 class="mt-3 text-xl font-bold leading-snug text-ink">{{ $event->title }}</h3>
            <p class="mt-1 text-sm text-muted">{{ $event->organization->name }}</p>

            <dl class="mt-5 space-y-3 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-muted">Tiket</dt><dd class="font-semibold text-ink text-right">{{ $ticket->name }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">Jadwal</dt><dd class="font-semibold text-ink text-right">{{ $event->start_time->format('d M Y, H:i') }} WIB</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">Lokasi</dt><dd class="font-semibold text-ink text-right">{{ $event->venue ? $event->venue->name : 'Online' }}</dd></div>
            </dl>

            <div class="mt-6 flex items-center justify-between border-t border-line pt-5">
                <span class="text-sm font-medium text-muted">Total</span>
                <span class="text-xl font-extrabold text-ink">
                    @if($ticket->price == 0) Gratis @else Rp {{ number_format($ticket->price, 0, ',', '.') }} @endif
                </span>
            </div>
        </aside>

        <section>
            <form action="{{ route('events.checkout.store', ['event' => $event->id, 'ticket' => $ticket->id]) }}" method="POST" enctype="multipart/form-data" class="card space-y-6 p-6 sm:p-8">
                @csrf

                <div>
                    <h2 class="text-lg font-bold text-ink">Data peserta</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-[14px] border border-line bg-surface-soft p-4">
                            <p class="text-xs font-medium text-muted">Nama lengkap</p>
                            <p class="mt-1 text-sm font-semibold text-ink">{{ $user->name }}</p>
                        </div>
                        <div class="rounded-[14px] border border-line bg-surface-soft p-4">
                            <p class="text-xs font-medium text-muted">Email</p>
                            <p class="mt-1 truncate text-sm font-semibold text-ink">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                @if($ticket->price > 0)
                    <div class="space-y-4 border-t border-line pt-6">
                        <div>
                            <h2 class="text-lg font-bold text-ink">Pembayaran (simulasi)</h2>
                            <p class="mt-1 text-sm text-muted">Pilih metode pembayaran dan unggah bukti transfer simulasi untuk diverifikasi panitia.</p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach(['Bank Transfer (BCA)', 'Bank Transfer (Mandiri)', 'E-Wallet (GoPay/Dana)'] as $method)
                                <label class="cursor-pointer rounded-[14px] border p-4 text-sm font-semibold transition"
                                    :class="paymentMethod === '{{ $method }}' ? 'border-brand-500 bg-brand-50 text-brand-900 ring-1 ring-brand-200' : 'border-line bg-surface text-muted hover:bg-surface-soft'">
                                    <input type="radio" name="payment_method" value="{{ $method }}" x-model="paymentMethod" class="sr-only">
                                    {{ $method }}
                                </label>
                            @endforeach
                        </div>

                        <div class="rounded-[14px] border border-line bg-surface-soft p-4 text-sm text-muted">
                            <p class="font-semibold text-ink">Instruksi transfer</p>
                            <p class="mt-2" x-show="paymentMethod === 'Bank Transfer (BCA)'">BCA <span class="font-mono font-semibold text-ink">8830-1928-3921</span> a.n. EventHub Kampus</p>
                            <p class="mt-2" x-show="paymentMethod === 'Bank Transfer (Mandiri)'">Mandiri <span class="font-mono font-semibold text-ink">132-00-9831-291</span> a.n. EventHub Kampus</p>
                            <p class="mt-2" x-show="paymentMethod === 'E-Wallet (GoPay/Dana)'">E-Wallet <span class="font-mono font-semibold text-ink">0821-9876-5432</span> (Admin Kampus)</p>
                            <p class="mt-3 text-xs">Ini simulasi internal. Unggah screenshot/bukti uji coba (JPG/PNG/WEBP, maks. 3MB).</p>
                        </div>

                        <div>
                            <label for="proof_file" class="label">Bukti pembayaran</label>
                            <input id="proof_file" type="file" name="proof_file" accept="image/*" class="input file:mr-3 file:rounded-full file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-700">
                        </div>
                    </div>
                @else
                    <input type="hidden" name="payment_method" value="Gratis">
                    <div class="rounded-[14px] border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                        Tiket ini gratis. E-tiket akan diterbitkan setelah konfirmasi pendaftaran.
                    </div>
                @endif

                <div>
                    <label for="notes" class="label">Catatan untuk panitia (opsional)</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Contoh: kebutuhan khusus, perwakilan tim, dll." class="input">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary w-full py-3 text-base">Konfirmasi pendaftaran</button>
            </form>
        </section>
    </div>
</div>
@endsection
