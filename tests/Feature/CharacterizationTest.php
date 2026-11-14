<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Organization;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use App\Services\CertificateService;
use App\Services\CheckinService;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CharacterizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_registration_confirms_and_decrements_quota(): void
    {
        $user = User::factory()->create(['role' => 'participant']);
        $event = Event::factory()->create(['status' => 'PUBLISHED']);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'price' => 0,
            'quota' => 10,
            'available_quota' => 10,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('events.checkout.store', [$event, $ticket]), [
            'notes' => 'gratis',
        ]);

        $response->assertRedirect(route('participant.tickets.index'));
        $registration = Registration::where('user_id', $user->id)->where('event_id', $event->id)->first();
        $this->assertNotNull($registration);
        $this->assertSame('CONFIRMED', $registration->status);
        $this->assertSame(9, $ticket->fresh()->available_quota);
    }

    public function test_paid_registration_creates_pending_payment(): void
    {
        $user = User::factory()->create(['role' => 'participant']);
        $event = Event::factory()->create(['status' => 'PUBLISHED']);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'price' => 50000,
            'quota' => 10,
            'available_quota' => 10,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('events.checkout.store', [$event, $ticket]), [
            'payment_method' => 'Bank Transfer',
        ]);

        $response->assertRedirect(route('participant.tickets.index'));
        $registration = Registration::where('user_id', $user->id)->first();
        $this->assertSame('PENDING', $registration->status);
        $this->assertNotNull($registration->payment);
        $this->assertSame('PENDING', $registration->payment->status);
    }

    public function test_duplicate_registration_is_rejected_with_friendly_message(): void
    {
        $user = User::factory()->create(['role' => 'participant']);
        $event = Event::factory()->create(['status' => 'PUBLISHED']);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'price' => 0,
            'quota' => 10,
            'available_quota' => 10,
        ]);

        $ticketService = app(TicketService::class);
        $ticketService->register($user, $ticket);

        $this->actingAs($user);
        $response = $this->post(route('events.checkout.store', [$event, $ticket]), [
            'payment_method' => 'Bank Transfer',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_cancel_registration_restores_quota(): void
    {
        $user = User::factory()->create(['role' => 'participant']);
        $event = Event::factory()->create(['status' => 'PUBLISHED']);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'price' => 50000,
            'quota' => 10,
            'available_quota' => 9,
        ]);
        $registration = Registration::factory()->create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'PENDING',
        ]);

        app(TicketService::class)->cancel($registration, 'test cancel');

        $this->assertSame('CANCELLED', $registration->fresh()->status);
        $this->assertSame(10, $ticket->fresh()->available_quota);
    }

    public function test_checkin_success_and_double_checkin(): void
    {
        $user = User::factory()->create(['role' => 'participant']);
        $scanner = User::factory()->create(['role' => 'organizer']);
        $event = Event::factory()->create(['status' => 'PUBLISHED']);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'price' => 0,
            'quota' => 10,
            'available_quota' => 9,
        ]);
        $registration = Registration::factory()->create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'event_id' => $event->id,
            'registration_code' => 'EVT-2026-CHK001',
            'status' => 'CONFIRMED',
        ]);

        $checkinService = app(CheckinService::class);
        $first = $checkinService->processCheckin($registration->registration_code, $event, $scanner);
        $this->assertTrue($first['success']);
        $this->assertSame('ATTENDED', $registration->fresh()->status);

        $second = $checkinService->processCheckin($registration->registration_code, $event, $scanner);
        $this->assertFalse($second['success']);
        $this->assertSame('ALREADY_CHECKED_IN', $second['status']);
    }

    public function test_certificate_only_for_attended(): void
    {
        $user = User::factory()->create(['role' => 'participant']);
        $event = Event::factory()->create(['status' => 'PUBLISHED']);
        $ticket = Ticket::factory()->create(['event_id' => $event->id]);
        $registration = Registration::factory()->create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'CONFIRMED',
        ]);

        $this->expectException(\Exception::class);
        app(CertificateService::class)->issueCertificate($registration);
    }

    public function test_non_published_event_hidden_from_public(): void
    {
        $event = Event::factory()->create(['status' => 'DRAFT', 'slug' => 'draft-event']);
        $response = $this->get(route('events.show', $event->slug));
        $response->assertStatus(404);

        $organizer = User::factory()->create(['role' => 'organizer']);
        $org = Organization::factory()->create(['user_id' => $organizer->id]);
        $event->update(['organization_id' => $org->id]);
        $this->actingAs($organizer);
        $response2 = $this->get(route('events.show', $event->slug));
        $response2->assertStatus(200);
    }

    public function test_review_requires_attendance(): void
    {
        $user = User::factory()->create(['role' => 'participant']);
        $event = Event::factory()->create(['status' => 'PUBLISHED']);

        $this->actingAs($user);
        $response = $this->post(route('events.reviews.store', $event), [
            'rating' => 5,
            'comment' => 'bagus',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_check_role_middleware_blocks_participant_from_admin(): void
    {
        $user = User::factory()->create(['role' => 'participant']);
        $this->actingAs($user);
        $this->get(route('admin.dashboard'))->assertStatus(403);
    }
}
