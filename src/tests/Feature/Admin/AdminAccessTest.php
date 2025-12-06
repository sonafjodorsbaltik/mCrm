<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

/**
 * Feature tests for Admin panel access control.
 * 
 * Tests authentication and role-based access.
 */
class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure roles exist (may already exist from migrations)
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'manager']);
    }

    /**
     * Test that unauthenticated users cannot access admin panel.
     */
    public function test_guest_cannot_access_admin(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test that manager can view tickets list.
     */
    public function test_manager_can_view_tickets(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        // Create a test ticket
        Ticket::factory()->create();

        $response = $this->actingAs($manager)->get('/admin/tickets');

        $response->assertStatus(200)
            ->assertViewIs('admin.tickets.index');
    }
}
