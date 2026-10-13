<?php

namespace App\Services;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeService
{
    /**
     * Generate an SVG string representation of a QR code.
     */
    public static function generateSvg(string $data, int $size = 220): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, 1),
            new SvgImageBackEnd
        );

        $writer = new Writer($renderer);

        return $writer->writeString($data);
    }

    /**
     * Generate base64 data URI of the SVG QR Code for embedding in PDF or HTML.
     */
    public static function generateBase64Svg(string $data, int $size = 220): string
    {
        $svg = self::generateSvg($data, $size);

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}
