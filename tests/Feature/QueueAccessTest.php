<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class QueueAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_view_queue_but_cannot_operate_it(): void
    {
        $client = $this->createUserWithRole('user');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-QUEUE-1',
            'created_by' => $client->id,
            'assigned_to' => null,
            'status' => 'Open',
        ]);

        $this->actingAs($client)
            ->get(route('queues.index'))
            ->assertOk()
            ->assertSeeText('ICTO–TICKET Queuing System')
            ->assertSeeText($ticket->ticket_number)
            ->assertSeeText('Open')
            ->assertDontSeeText('Serve / Call To Counter')
            ->assertDontSeeText('CLOSE TICKET');

        $this->patch(route('queues.serve', $ticket), ['counter' => 'Jhune'])
            ->assertForbidden();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'Open',
        ]);
    }

    public function test_it_staff_can_see_queue_operation_controls(): void
    {
        $staff = $this->createUserWithRole('it_staff');

        $this->actingAs($staff)
            ->get(route('queues.index'))
            ->assertOk()
            ->assertSeeText('Serve / Call To Counter');
    }

    private function createUserWithRole(string $role): User
    {
        foreach (['admin', 'it_staff', 'user'] as $availableRole) {
            Role::firstOrCreate(['name' => $availableRole, 'guard_name' => 'web']);
        }

        $user = User::factory()->create(['role' => $role]);
        $user->assignRole($role);

        return $user;
    }
}
