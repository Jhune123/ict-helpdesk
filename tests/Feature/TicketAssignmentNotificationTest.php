<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TicketAssignmentNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignee_is_notified_by_email_and_dashboard_when_ticket_is_created(): void
    {
        Notification::fake();
        $admin = $this->createUserWithRole('admin');
        $assignee = $this->createUserWithRole('it_staff');

        $this->actingAs($admin)
            ->post(route('tickets.store'), [
                'form_type' => 'generic',
                'title' => 'Network access request',
                'description' => 'Please check the office connection.',
                'client_name' => 'Test Client',
                'assigned_to' => $assignee->id,
                'status' => 'Open',
            ])
            ->assertRedirect();

        $ticket = Ticket::where('title', 'Network access request')->firstOrFail();

        Notification::assertSentTo($assignee, TicketAssignedNotification::class, function ($notification, $channels) use ($assignee, $ticket) {
            $mail = $notification->toMail($assignee);

            return $channels === ['database', 'mail']
                && $notification->toArray($assignee)['ticket_id'] === $ticket->id
                && $mail->subject === 'Ticket assigned to you: '.$ticket->ticket_number
                && in_array('Title: '.$ticket->title, $mail->introLines, true);
        });
    }

    public function test_new_assignee_is_notified_when_ticket_is_reassigned(): void
    {
        Notification::fake();
        $admin = $this->createUserWithRole('admin');
        $previousAssignee = $this->createUserWithRole('it_staff');
        $newAssignee = $this->createUserWithRole('it_staff');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-ASSIGN-1',
            'title' => 'Reassign this ticket',
            'created_by' => $admin->id,
            'assigned_to' => $previousAssignee->id,
            'status' => 'In Progress',
        ]);

        $this->actingAs($admin)
            ->put(route('tickets.update', $ticket), [
                'title' => $ticket->title,
                'description' => $ticket->description,
                'client_name' => $ticket->client_name,
                'status' => $ticket->status,
                'assigned_to' => $newAssignee->id,
            ])
            ->assertRedirect();

        Notification::assertSentTo($newAssignee, TicketAssignedNotification::class, function ($notification, $channels) use ($newAssignee, $ticket) {
            return $channels === ['database', 'mail']
                && $notification->toMail($newAssignee)->subject === 'Ticket assigned to you: '.$ticket->ticket_number;
        });

        Notification::assertNotSentTo($previousAssignee, TicketAssignedNotification::class);
    }

    private function createUserWithRole(string $role): User
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);

        $user = User::factory()->create(['role' => $role]);
        $user->assignRole($role);

        return $user;
    }
}
