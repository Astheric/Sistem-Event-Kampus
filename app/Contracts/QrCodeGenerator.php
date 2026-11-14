<?php

namespace App\Contracts;

interface QrCodeGenerator
{
    public function svg(string $data, int $size = 220): string;

    public function base64Svg(string $data, int $size = 220): string;
}
