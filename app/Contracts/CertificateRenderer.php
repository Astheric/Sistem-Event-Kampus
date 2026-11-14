<?php

namespace App\Contracts;

use App\Models\Certificate;

interface CertificateRenderer
{
    public function render(Certificate $certificate);
}
