<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use App\Services\CertificateService;
use App\Services\CheckinService;
use App\Services\TicketService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EventHubCoreTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $organizer;

    protected User $participant;

    protected Organization $organization;

    protected Category $category;

    protected Event $event;

    protected Ticket $ticket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Kampus',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->organizer = User::create([
            'name' => 'Organizer Kampus',
            'email' => 'organizer@test.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
        ]);

        $this->organization = Organization::create([
            'user_id' => $this->organizer->id,
            'name' => 'BEM Fasilkom Test',
            'description' => 'Test Org',
        ]);

        $this->participant = User::create([
            'name' => 'Peserta Test',
            'email' => 'peserta@test.com',
            'password' => Hash::make('password'),
            'role' => 'participant',
        ]);

        $this->category = Category::create([
            'name' => 'Seminar Teknologi',
            'slug' => 'seminar-teknologi',
            'icon' => '💻',
        ]);

        $now = Carbon::now();

        $this->event = Event::create([
            'organization_id' => $this->organization->id,
            'category_id' => $this->category->id,
            'title' => 'AI Innovation Summit 2026',
            'slug' => 'ai-innovation-summit-2026',
            'description' => 'Event deskripsi lengkap pengujian otomatis.',
            'start_time' => $now->copy()->addDays(5),
            'end_time' => $now->copy()->addDays(5)->addHours(4),
            'capacity' => 100,
            'status' => 'PUBLISHED',
        ]);

        $this->ticket = Ticket::create([
            'event_id' => $this->event->id,
            'name' => 'Tiket Regular',
            'price' => 50000,
            'quota' => 50,
            'available_quota' => 50,
            'start_sale' => $now->copy()->subDays(1),
            'end_sale' => $now->copy()->addDays(4),
        ]);
    }

    public function test_landing_page_and_catalog_are_accessible_publicly(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('EventHub');
        $response->assertSee('AI Innovation Summit 2026');

        $catalogResponse = $this->get(route('events.index'));
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('Jelajah Event Kampus');
    }

    public function test_participant_cannot_access_admin_or_organizer_routes(): void
    {
        $this->actingAs($this->participant);

        $adminResponse = $this->get(route('admin.dashboard'));
        $adminResponse->assertStatus(403);

        $organizerResponse = $this->get(route('organizer.dashboard'));
        $organizerResponse->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Pusat Kendali & Pengawasan Kampus', false);
    }

    public function test_business_rule_01_participant_cannot_register_same_event_twice(): void
    {
        $ticketService = app(TicketService::class);

        // First registration succeeds
        $reg1 = $ticketService->register($this->participant, $this->ticket, [
            'payment_method' => 'Bank Transfer',
        ]);

        $this->assertNotNull($reg1);
        $this->assertEquals('PENDING', $reg1->status);
        $this->assertEquals(49, $this->ticket->fresh()->available_quota);

        // Second registration for same event must throw exception (BR-01)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Anda sudah terdaftar pada event ini');

        $ticketService->register($this->participant, $this->ticket, [
            'payment_method' => 'Bank Transfer',
        ]);
    }

    public function test_business_rule_03_anti_double_checkin(): void
    {
        $ticketService = app(TicketService::class);
        $checkinService = app(CheckinService::class);

        // Register free ticket for instant confirmation
        $freeTicket = Ticket::create([
            'event_id' => $this->event->id,
            'name' => 'Tiket Mahasiswa Gratis',
            'price' => 0,
            'quota' => 20,
            'available_quota' => 20,
            'start_sale' => Carbon::now()->subDays(1),
            'end_sale' => Carbon::now()->addDays(2),
        ]);

        $reg = $ticketService->register($this->participant, $freeTicket);
        $this->assertEquals('CONFIRMED', $reg->status);

        // First Check-in: SUCCESS
        $scan1 = $checkinService->processCheckin($reg->registration_code, $this->event, $this->organizer);
        $this->assertTrue($scan1['success']);
        $this->assertEquals('SUCCESS', $scan1['status']);
        $this->assertEquals('ATTENDED', $reg->fresh()->status);

        // Second Check-in with SAME code: ALREADY_CHECKED_IN (BR-03)
        $scan2 = $checkinService->processCheckin($reg->registration_code, $this->event, $this->organizer);
        $this->assertFalse($scan2['success']);
        $this->assertEquals('ALREADY_CHECKED_IN', $scan2['status']);
        $this->assertStringContainsString('TIKET SUDAH DIGUNAKAN', $scan2['message']);
    }

    public function test_business_rule_04_certificate_only_for_attended_participants(): void
    {
        $certService = app(CertificateService::class);

        $reg = Registration::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => $this->participant->id,
            'event_id' => $this->event->id,
            'registration_code' => 'EVT-2026-UNATTD',
            'status' => 'CONFIRMED', // NOT ATTENDED!
        ]);

        // Attempting to issue certificate for CONFIRMED (not ATTENDED) must throw Exception
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Sertifikat hanya dapat diterbitkan untuk peserta yang telah terkonfirmasi hadir');

        $certService->issueCertificate($reg);
    }

    public function test_certificate_issuance_and_public_verification(): void
    {
        $certService = app(CertificateService::class);

        $reg = Registration::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => $this->participant->id,
            'event_id' => $this->event->id,
            'registration_code' => 'EVT-2026-VAL001',
            'status' => 'ATTENDED', // ATTENDED qualifies!
        ]);

        $certificate = $certService->issueCertificate($reg);

        $this->assertNotNull($certificate);
        $this->assertStringStartsWith('CERT-2026-', $certificate->certificate_number);

        // Test Public Verification without authentication (FR-14)
        $verifyResponse = $this->get(route('certificates.verify', ['code' => $certificate->certificate_number]));
        $verifyResponse->assertStatus(200);
        $verifyResponse->assertSee('SERTIFIKAT VALID & TERDAFTAR', false);
        $verifyResponse->assertSee($this->participant->name);
        $verifyResponse->assertSee($this->event->title);
    }

    public function test_admin_event_moderation_workflow(): void
    {
        $this->actingAs($this->admin);

        $submittedEvent = Event::create([
            'organization_id' => $this->organization->id,
            'category_id' => $this->category->id,
            'title' => 'Workshop Pending Review',
            'slug' => 'workshop-pending-review',
            'description' => 'Menunggu persetujuan admin kampus.',
            'start_time' => Carbon::now()->addDays(10),
            'end_time' => Carbon::now()->addDays(10)->addHours(2),
            'capacity' => 50,
            'status' => 'SUBMITTED',
        ]);

        Ticket::create([
            'event_id' => $submittedEvent->id,
            'name' => 'Regular',
            'price' => 20000,
            'quota' => 50,
            'available_quota' => 50,
            'start_sale' => Carbon::now(),
            'end_sale' => Carbon::now()->addDays(9),
        ]);

        // 1. Admin rejects with revision reason
        $rejectResponse = $this->post(route('admin.moderation.reject', $submittedEvent->id), [
            'rejection_reason' => 'Perbaiki informasi ruangan dan narahubung panitia.',
        ]);

        $rejectResponse->assertRedirect(route('admin.moderation.index'));
        $this->assertEquals('REJECTED', $submittedEvent->fresh()->status);
        $this->assertEquals('Perbaiki informasi ruangan dan narahubung panitia.', $submittedEvent->fresh()->rejection_reason);

        // 2. Admin approves event
        $approveResponse = $this->post(route('admin.moderation.approve', $submittedEvent->id));
        $approveResponse->assertRedirect(route('admin.moderation.index'));
        $this->assertEquals('PUBLISHED', $submittedEvent->fresh()->status);
    }
}
