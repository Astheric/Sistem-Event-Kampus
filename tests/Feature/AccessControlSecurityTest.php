<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccessControlSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_cannot_access_admin_routes(): void
    {
        $participant = User::create([
            'name' => 'Participant',
            'email' => 'participant@test.com',
            'password' => Hash::make('password'),
            'role' => 'participant',
        ]);

        $this->actingAs($participant);

        $this->get(route('admin.dashboard'))->assertRedirect(route('home'));
        $this->get(route('admin.moderation.index'))->assertRedirect(route('home'));
        $this->get(route('admin.categories.index'))->assertRedirect(route('home'));
        $this->get(route('admin.users.index'))->assertRedirect(route('home'));
        $this->get(route('admin.audit_logs.index'))->assertRedirect(route('home'));
    }

    public function test_guest_is_redirected_from_admin_routes(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }
}
