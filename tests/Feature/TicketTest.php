<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_ticket()
    {
        $user = User::factory()->create();

        $ticket = Ticket::factory()->create([
            'created_by' => $user->id,
        ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'created_by' => $user->id,
        ]);
    }
}