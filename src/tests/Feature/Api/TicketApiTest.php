<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Feature tests for Ticket API endpoints.
 * 
 * Tests ticket creation, validation, and rate limiting.
 */
class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a ticket can be created with valid data.
     */
    public function test_can_create_ticket(): void
    {
        $response = $this->postJson('/api/v1/tickets', [
            'name' => 'Test Customer',
            'phone' => '+380501234567',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'content' => 'Test content message',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'subject',
                    'content',
                    'status',
                    'status_code',
                    'created_at',
                    'customer' => ['name', 'email', 'phone'],
                ],
            ]);

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Test Subject',
        ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test that ticket creation fails with invalid phone number.
     */
    public function test_cannot_create_ticket_with_invalid_phone(): void
    {
        $response = $this->postJson('/api/v1/tickets', [
            'name' => 'Test Customer',
            'phone' => 'invalid-phone',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'content' => 'Test content message',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    /**
     * Test that rate limit blocks duplicate submissions within 24 hours.
     */
    public function test_rate_limit_blocks_duplicate_submission(): void
    {
        $data = [
            'name' => 'Test Customer',
            'phone' => '+380501234567',
            'email' => 'test@example.com',
            'subject' => 'First Ticket',
            'content' => 'First content',
        ];

        // First request should succeed
        $this->postJson('/api/v1/tickets', $data)
            ->assertStatus(201);

        // Second request with same phone AND email should fail
        $response = $this->postJson('/api/v1/tickets', array_merge($data, [
            'subject' => 'Second Ticket',
            'content' => 'Second content',
        ]));

        $response->assertStatus(429);
    }
}
