<?php

namespace App\Infrastructure;

use App\Contracts\CertificateRenderer;
use App\Models\Certificate;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;

class DompdfCertificateRenderer implements CertificateRenderer
{
    public function render(Certificate $certificate)
    {
        $registration = $certificate->registration->load(['user', 'event.organization']);
        $verificationUrl = route('certificates.verify', ['code' => $certificate->certificate_number]);
        $qrCodeBase64 = QrCodeService::generateBase64Svg($verificationUrl, 160);

        return Pdf::loadView('certificates.template_pdf', [
            'certificate' => $certificate,
            'registration' => $registration,
            'user' => $registration->user,
            'event' => $registration->event,
            'organization' => $registration->event->organization,
            'qrCode' => $qrCodeBase64,
            'verificationUrl' => $verificationUrl,
        ])->setPaper('a4', 'landscape');
    }
}
