<?php

namespace App\Services\Export;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParticipantCsvExporter
{
    public function export(Event $event): StreamedResponse
    {
        $fileName = 'peserta-'.Str::slug($event->title).'-'.date('Ymd_His').'.csv';

        $registrations = Registration::with(['user', 'ticket', 'payment', 'checkin'])
            ->where('event_id', $event->id)
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($registrations) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Kode Tiket', 'Nama Peserta', 'Email', 'No. Telepon', 'Tipe Tiket',
                'Harga Tiket', 'Status Pendaftaran', 'Metode Pembayaran',
                'Status Pembayaran', 'Waktu Check-in', 'Tanggal Daftar',
            ]);

            foreach ($registrations as $reg) {
                fputcsv($handle, [
                    $reg->registration_code,
                    $reg->user->name,
                    $reg->user->email,
                    $reg->user->phone ?? '-',
                    $reg->ticket->name,
                    $reg->ticket->price,
                    $reg->status,
                    $reg->payment->payment_method ?? 'Gratis',
                    $reg->payment->status ?? 'N/A',
                    $reg->checkin ? Carbon::parse($reg->checkin->checked_in_at)->format('Y-m-d H:i:s') : 'Belum Hadir',
                    $reg->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
