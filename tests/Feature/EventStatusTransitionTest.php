<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Organization;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventStatusTransitionTest extends TestCase
{
    use RefreshDatabase;

    protected User $organizer;

    protected Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organizer = User::factory()->create(['role' => 'organizer']);
        $this->organization = Organization::factory()->create(['user_id' => $this->organizer->id]);
    }

    private function eventWithStatus(string $status): Event
    {
        return Event::factory()->create([
            'organization_id' => $this->organization->id,
            'status' => $status,
        ]);
    }

    public function test_organizer_cannot_publish_draft_event(): void
    {
        $event = $this->eventWithStatus('DRAFT');

        $response = $this->actingAs($this->organizer)->post(route('organizer.events.status', $event->id), [
            'status' => 'PUBLISHED',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertSame('DRAFT', $event->fresh()->status);
    }

    public function test_organizer_can_complete_published_event(): void
    {
        $event = $this->eventWithStatus('PUBLISHED');

        $response = $this->actingAs($this->organizer)->post(route('organizer.events.status', $event->id), [
            'status' => 'COMPLETED',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame('COMPLETED', $event->fresh()->status);
    }

    public function test_organizer_can_cancel_published_event_and_active_registrations(): void
    {
        $event = $this->eventWithStatus('PUBLISHED');
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'quota' => 10,
            'available_quota' => 9,
        ]);
        $registration = Registration::factory()->create([
            'event_id' => $event->id,
            'ticket_id' => $ticket->id,
            'status' => 'CONFIRMED',
        ]);

        $response = $this->actingAs($this->organizer)->post(route('organizer.events.status', $event->id), [
            'status' => 'CANCELLED',
        ]);

        $response->assertRedirect();
        $this->assertSame('CANCELLED', $event->fresh()->status);
        $this->assertSame('CANCELLED', $registration->fresh()->status);
        $this->assertSame(10, $ticket->fresh()->available_quota);
    }

    public function test_completed_event_cannot_be_cancelled(): void
    {
        $event = $this->eventWithStatus('COMPLETED');

        $response = $this->actingAs($this->organizer)->post(route('organizer.events.status', $event->id), [
            'status' => 'CANCELLED',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertSame('COMPLETED', $event->fresh()->status);
    }

    public function test_admin_can_publish_submitted_event_through_status_endpoint(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $event = $this->eventWithStatus('SUBMITTED');

        $response = $this->actingAs($admin)->post(route('organizer.events.status', $event->id), [
            'status' => 'PUBLISHED',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame('PUBLISHED', $event->fresh()->status);
    }
}
