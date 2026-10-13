<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Services\AuditLogger;
use App\Services\CertificateService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function __construct(
        protected CertificateService $certificateService
    ) {}

    /**
     * Authorize organizer event ownership.
     */
    protected function authorizeEvent(Event $event): void
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return;
        }

        if (! $user->organization || $event->organization_id !== $user->organization->id) {
            abort(403, 'Anda tidak memiliki otoritas atas event ini.');
        }
    }

    /**
     * Display attended participants and their certificate statuses (FR-13).
     */
    public function index(Event $event): View
    {
        $this->authorizeEvent($event);

        $attendedRegistrations = Registration::with(['user', 'ticket', 'certificate', 'checkin'])
            ->where('event_id', $event->id)
            ->whereIn('status', ['ATTENDED', 'COMPLETED'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_attended' => $attendedRegistrations->count(),
            'certificates_issued' => $attendedRegistrations->whereNotNull('certificate')->count(),
            'pending_issuance' => $attendedRegistrations->whereNull('certificate')->count(),
        ];

        return view('organizer.certificates.index', compact('event', 'attendedRegistrations', 'stats'));
    }

    /**
     * Generate / Issue certificate for a single attended participant.
     */
    public function generate(Registration $registration): RedirectResponse
    {
        $this->authorizeEvent($registration->event);

        try {
            $certificate = $this->certificateService->issueCertificate($registration);

            return back()->with('success', 'Sertifikat '.$certificate->certificate_number.' untuk '.$registration->user->name.' berhasil diterbitkan!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Generate certificates in bulk for all attended participants.
     */
    public function generateAll(Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);

        $unissued = Registration::where('event_id', $event->id)
            ->whereIn('status', ['ATTENDED', 'COMPLETED'])
            ->doesntHave('certificate')
            ->get();

        $count = 0;
        foreach ($unissued as $reg) {
            try {
                $this->certificateService->issueCertificate($reg);
                $count++;
            } catch (Exception $e) {
                // Continue to next
            }
        }

        AuditLogger::log('certificates.bulk_issued', 'Event', $event->id, [
            'issued_count' => $count,
        ]);

        return back()->with('success', "Berhasil menerbitkan {$count} e-sertifikat untuk peserta yang hadir!");
    }
}
