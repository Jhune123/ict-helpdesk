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
        $this->patch(route('queues.cancel-call', $ticket))
            ->assertForbidden();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'Open',
        ]);
    }

    public function test_it_staff_can_see_queue_operation_controls(): void
    {
        $staff = $this->createUserWithRole('it_staff');
        $additionalStaff = User::factory()->create(['name' => 'Additional IT Personnel']);
        $additionalStaff->assignRole('it_staff');
        User::factory()->create(['name' => 'Legacy IT Personnel', 'role' => 'it_staff']);
        Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-QUEUE-STAFF-LIST',
            'created_by' => $staff->id,
            'assigned_to' => null,
            'status' => 'Open',
        ]);

        $this->actingAs($staff)
            ->get(route('queues.index'))
            ->assertOk()
            ->assertSeeText('Serve / Call To Counter')
            ->assertSee('value="' . e($staff->name) . '"', false)
            ->assertSee('value="' . e($additionalStaff->name) . '"', false)
            ->assertSee('value="Legacy IT Personnel"', false);
    }

    public function test_queue_serve_rejects_unregistered_counter_name(): void
    {
        $staff = $this->createUserWithRole('it_staff');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-QUEUE-INVALID-COUNTER',
            'created_by' => $staff->id,
            'assigned_to' => null,
            'status' => 'Open',
        ]);

        $this->actingAs($staff)
            ->patch(route('queues.serve', $ticket), ['counter' => 'Not Registered'])
            ->assertSessionHasErrors('counter');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'Open',
        ]);
    }

    public function test_registered_it_staff_can_be_selected_to_serve_ticket(): void
    {
        $staff = $this->createUserWithRole('it_staff');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-QUEUE-REGISTERED-COUNTER',
            'created_by' => $staff->id,
            'assigned_to' => null,
            'status' => 'Open',
        ]);

        $this->actingAs($staff)
            ->patch(route('queues.serve', $ticket), ['counter' => $staff->name])
            ->assertRedirect();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'In Progress',
            'remarks' => 'Serving at Counter: ' . $staff->name,
        ]);
    }

    public function test_it_staff_can_cancel_a_call_and_return_ticket_to_waiting_queue(): void
    {
        $staff = $this->createUserWithRole('it_staff');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-QUEUE-CANCEL-CALL',
            'created_by' => $staff->id,
            'assigned_to' => null,
            'status' => 'In Progress',
            'remarks' => 'Serving at Counter: ' . $staff->name,
            'date_finished' => now(),
        ]);

        $this->actingAs($staff)
            ->patch(route('queues.cancel-call', $ticket))
            ->assertRedirect();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'Open',
            'remarks' => null,
            'date_finished' => null,
        ]);
    }

    public function test_queue_dashboard_shows_cancel_call_next_to_close_ticket(): void
    {
        $staff = $this->createUserWithRole('it_staff');
        Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-QUEUE-CANCEL-CONTROL',
            'created_by' => $staff->id,
            'assigned_to' => null,
            'status' => 'In Progress',
            'remarks' => 'Serving at Counter: ' . $staff->name,
        ]);

        $this->actingAs($staff)
            ->get(route('queues.index'))
            ->assertOk()
            ->assertSeeText('CLOSE TICKET')
            ->assertSeeText('CANCEL CALL');
    }

    public function test_live_monitor_displays_all_registered_it_staff_and_their_serving_ticket(): void
    {
        $this->createUserWithRole('it_staff');
        $additionalStaff = User::factory()->create(['name' => 'Additional Monitor Staff']);
        $additionalStaff->assignRole('it_staff');
        User::factory()->create(['name' => 'Legacy Monitor Staff', 'role' => 'it_staff']);
        User::factory()->create(['name' => 'Regular User']);

        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-LIVE-MONITOR',
            'assigned_to' => null,
            'status' => 'In Progress',
            'remarks' => 'Serving at Counter: ' . $additionalStaff->name,
        ]);

        $this->get(route('queues.live-tv'))
            ->assertOk()
            ->assertSeeText('ICTO LIVE MONITOR')
            ->assertSeeText('Enable announcements')
            ->assertSeeText($additionalStaff->name)
            ->assertSeeText('Legacy Monitor Staff')
            ->assertSeeText($ticket->ticket_number)
            ->assertDontSeeText('Regular User');
    }

    public function test_live_monitor_lists_all_unfinished_queue_tickets_and_excludes_closed_tickets(): void
    {
        $creator = $this->createUserWithRole('user');
        $waitingTicketNumbers = [];
        for ($number = 1; $number <= 6; $number++) {
            $ticketNumber = 'KSU-ICTO-TIC-LIVE-WAIT-' . $number;
            $waitingTicketNumbers[] = $ticketNumber;
            Ticket::factory()->create([
                'ticket_number' => $ticketNumber,
                'created_by' => $creator->id,
                'assigned_to' => null,
                'status' => 'Open',
            ]);
        }

        $servingTicket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-LIVE-SERVING',
            'created_by' => $creator->id,
            'assigned_to' => null,
            'status' => 'In Progress',
        ]);
        $closedTicket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-LIVE-CLOSED',
            'created_by' => $creator->id,
            'assigned_to' => null,
            'status' => 'Closed',
        ]);

        $response = $this->get(route('queues.live-tv'))
            ->assertOk()
            ->assertSeeText($servingTicket->ticket_number)
            ->assertDontSeeText($closedTicket->ticket_number);

        foreach ($waitingTicketNumbers as $ticketNumber) {
            $response->assertSeeText($ticketNumber);
        }
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
