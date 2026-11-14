<?php

namespace App\Infrastructure;

use App\Contracts\PaymentProofStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PrivateDiskProofStorage implements PaymentProofStorage
{
    public function store($file): string
    {
        if ($file instanceof UploadedFile) {
            return $file->store('proofs', 'private');
        }

        return (string) $file;
    }

    public function url(string $path): string
    {
        return route('payments.proof', ['path' => $path]);
    }

    public function exists(string $path): bool
    {
        return Storage::disk('private')->exists($path);
    }
}
