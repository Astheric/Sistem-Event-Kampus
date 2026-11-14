<?php

namespace App\Contracts;

interface PaymentProofStorage
{
    public function store($file): string;

    public function url(string $path): string;

    public function exists(string $path): bool;
}
