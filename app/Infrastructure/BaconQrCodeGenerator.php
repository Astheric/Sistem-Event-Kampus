<?php

namespace App\Infrastructure;

use App\Contracts\QrCodeGenerator;
use App\Services\QrCodeService;

class BaconQrCodeGenerator implements QrCodeGenerator
{
    public function svg(string $data, int $size = 220): string
    {
        return QrCodeService::generateSvg($data, $size);
    }

    public function base64Svg(string $data, int $size = 220): string
    {
        return QrCodeService::generateBase64Svg($data, $size);
    }
}
