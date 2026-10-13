<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Notification;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CertificateService
{
    /**
     * Issue or retrieve certificate for an attended participant.
     *
     * @throws Exception
     */
    public function issueCertificate(Registration $registration): Certificate
    {
        // BR-04: Digital certificate only for ATTENDED participants
        if ($registration->status !== 'ATTENDED' && $registration->status !== 'COMPLETED') {
            throw new Exception('Sertifikat hanya dapat diterbitkan untuk peserta yang telah terkonfirmasi hadir (ATTENDED).');
        }

        // Return existing certificate if already generated
        $existing = Certificate::where('registration_id', $registration->id)->first();
        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($registration) {
            // BR-07: Unique certificate code CERT-2026-XXXXXX
            do {
                $certCode = 'CERT-2026-'.strtoupper(Str::random(6));
            } while (Certificate::where('certificate_number', $certCode)->exists());

            $certificate = Certificate::create([
                'registration_id' => $registration->id,
                'certificate_number' => $certCode,
                'pdf_path' => null,
                'issued_at' => Carbon::now(),
            ]);

            // Notification
            Notification::create([
                'user_id' => $registration->user_id,
                'title' => 'E-Sertifikat Tersedia!',
                'message' => 'Sertifikat kehadiran untuk '.$registration->event->title.' telah terbit ('.$certCode.').',
                'link' => route('participant.certificates.index'),
                'is_read' => false,
            ]);

            AuditLogger::log('certificate.issued', 'Certificate', $certificate->id, [
                'code' => $certCode,
                'recipient' => $registration->user->name,
                'event' => $registration->event->title,
            ]);

            return $certificate;
        });
    }

    /**
     * Generate PDF stream/download for a certificate.
     */
    public function generatePdf(Certificate $certificate)
    {
        $registration = $certificate->registration->load(['user', 'event.organization']);
        $verificationUrl = route('certificates.verify', ['code' => $certificate->certificate_number]);
        $qrCodeBase64 = QrCodeService::generateBase64Svg($verificationUrl, 160);

        $pdf = Pdf::loadView('certificates.template_pdf', [
            'certificate' => $certificate,
            'registration' => $registration,
            'user' => $registration->user,
            'event' => $registration->event,
            'organization' => $registration->event->organization,
            'qrCode' => $qrCodeBase64,
            'verificationUrl' => $verificationUrl,
        ])->setPaper('a4', 'landscape');

        return $pdf;
    }
}
