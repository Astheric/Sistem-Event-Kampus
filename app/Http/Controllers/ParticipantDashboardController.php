<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Review;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\CertificateService;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParticipantDashboardController extends Controller
{
    public function __construct(
        protected CertificateService $certificateService
    ) {}

    /**
     * Display all registered tickets for the logged in participant.
     */
    public function tickets(Request $request): View
    {
        $status = $request->input('status');
        $query = Registration::with(['event.venue', 'ticket', 'payment', 'checkin'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', strtoupper($status));
        }

        $registrations = $query->paginate(10)->withQueryString();

        return view('participant.tickets', compact('registrations', 'status'));
    }

    /**
     * Display individual E-Ticket with dynamic QR Code.
     */
    public function showTicket(Registration $registration): View
    {
        if ($registration->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $registration->load(['event.venue', 'event.organization', 'ticket', 'user', 'payment', 'checkin']);

        // Generate dynamic SVG QR code for the registration code
        $qrCodeSvg = QrCodeService::generateSvg($registration->registration_code, 220);

        return view('participant.ticket_detail', compact('registration', 'qrCodeSvg'));
    }

    /**
     * Download printable E-Ticket PDF.
     */
    public function downloadTicket(Registration $registration)
    {
        if ($registration->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $registration->load(['event.venue', 'event.organization', 'ticket', 'user']);
        $qrCodeBase64 = QrCodeService::generateBase64Svg($registration->registration_code, 180);

        $pdf = Pdf::loadView('participant.ticket_pdf', [
            'registration' => $registration,
            'qrCode' => $qrCodeBase64,
        ])->setPaper('a5', 'landscape');

        return $pdf->download('E-Ticket-'.$registration->registration_code.'.pdf');
    }

    /**
     * Display participant certificates.
     */
    public function certificates(): View
    {
        $user = Auth::user();

        // Registrations that qualify for certificates (ATTENDED or COMPLETED)
        $attendedRegistrations = Registration::with(['event.organization', 'certificate', 'ticket'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['ATTENDED', 'COMPLETED'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('participant.certificates', compact('attendedRegistrations'));
    }

    /**
     * Claim / Issue digital certificate for an attended event.
     */
    public function claimCertificate(Registration $registration): RedirectResponse
    {
        if ($registration->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        try {
            $certificate = $this->certificateService->issueCertificate($registration);

            return back()->with('success', 'Sertifikat Anda ('.$certificate->certificate_number.') berhasil diterbitkan!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Download digital certificate PDF.
     */
    public function downloadCertificate(Certificate $certificate)
    {
        if ($certificate->registration->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $pdf = $this->certificateService->generatePdf($certificate);

        return $pdf->download('Sertifikat-'.$certificate->certificate_number.'.pdf');
    }

    /**
     * Submit rating & review for an attended event (FR-15).
     */
    public function storeReview(Request $request, Event $event): RedirectResponse
    {
        $user = Auth::user();

        // Must be attended to submit review
        $attended = Registration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['ATTENDED', 'COMPLETED'])
            ->exists();

        if (! $attended) {
            return back()->with('error', 'Hanya peserta yang telah hadir di event ini yang dapat memberikan ulasan.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => $user->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        AuditLogger::log('review.submitted', 'Review', $event->id, [
            'rating' => $validated['rating'],
        ]);

        return back()->with('success', 'Terima kasih! Ulasan dan rating Anda berhasil disimpan.');
    }

    /**
     * View and update user profile.
     */
    public function profile(): View
    {
        $user = Auth::user();
        $organization = $user->organization;

        return view('participant.profile', compact('user', 'organization'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'org_name' => ['nullable', 'string', 'max:255'],
            'org_description' => ['nullable', 'string', 'max:1000'],
            'org_contact' => ['nullable', 'string', 'max:255'],
            'org_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? $user->phone,
            'bio' => $validated['bio'] ?? $user->bio,
            'avatar' => $validated['avatar'] ?? $user->avatar,
        ]);

        if ($user->isOrganizer() || $user->organization) {
            $orgData = [
                'name' => $validated['org_name'] ?? ($user->organization?->name ?? $user->name.' Org'),
                'description' => $validated['org_description'] ?? $user->organization?->description,
                'contact' => $validated['org_contact'] ?? ($validated['phone'] ?? $user->organization?->contact),
            ];

            if ($request->hasFile('org_logo')) {
                $orgData['logo'] = $request->file('org_logo')->store('organizations', 'public');
            }

            $user->organization()->updateOrCreate(['user_id' => $user->id], $orgData);
        }

        return back()->with('success', 'Profil dan identitas akun Anda berhasil diperbarui!');
    }
}
