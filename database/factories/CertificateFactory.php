<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Certificate>
 */
class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition(): array
    {
        return [
            'registration_id' => Registration::factory(),
            'certificate_number' => 'CERT-2026-'.strtoupper(Str::random(6)),
            'pdf_path' => null,
            'issued_at' => now(),
        ];
    }
}
