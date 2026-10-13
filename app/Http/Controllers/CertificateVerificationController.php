<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificateVerificationController extends Controller
{
    /**
     * Public certificate verification page (FR-14).
     */
    public function index(Request $request): View
    {
        $code = trim(strtoupper($request->input('code', '')));
        $certificate = null;
        $searched = false;

        if ($code) {
            $searched = true;
            $certificate = Certificate::with(['registration.user', 'registration.event.organization', 'registration.event.venue'])
                ->where('certificate_number', $code)
                ->first();
        }

        return view('certificates.verify', compact('code', 'certificate', 'searched'));
    }

    /**
     * Direct verification by certificate code parameter.
     */
    public function show(string $code): View
    {
        $code = trim(strtoupper($code));
        $certificate = Certificate::with(['registration.user', 'registration.event.organization', 'registration.event.venue'])
            ->where('certificate_number', $code)
            ->first();

        $searched = true;

        return view('certificates.verify', compact('code', 'certificate', 'searched'));
    }
}
