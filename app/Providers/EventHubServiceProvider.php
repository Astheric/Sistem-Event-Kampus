<?php

namespace App\Providers;

use App\Contracts\AuditTrail;
use App\Contracts\CertificateRenderer;
use App\Contracts\Notifier;
use App\Contracts\PaymentProofStorage;
use App\Contracts\QrCodeGenerator;
use App\Infrastructure\BaconQrCodeGenerator;
use App\Infrastructure\DatabaseAuditTrail;
use App\Infrastructure\DatabaseNotifier;
use App\Infrastructure\DompdfCertificateRenderer;
use App\Infrastructure\PrivateDiskProofStorage;
use App\Services\Registration\Rules\AllEligibility;
use App\Services\Registration\Rules\LecturerEligibility;
use App\Services\Registration\Rules\StudentEligibility;
use Illuminate\Support\ServiceProvider;

class EventHubServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuditTrail::class, DatabaseAuditTrail::class);
        $this->app->bind(Notifier::class, DatabaseNotifier::class);
        $this->app->bind(QrCodeGenerator::class, BaconQrCodeGenerator::class);
        $this->app->bind(CertificateRenderer::class, DompdfCertificateRenderer::class);
        $this->app->bind(PaymentProofStorage::class, PrivateDiskProofStorage::class);

        $this->app->tag([], 'registration.rules');
        $this->app->tag([
            AllEligibility::class,
            StudentEligibility::class,
            LecturerEligibility::class,
        ], 'audience.eligibility');
    }
}
