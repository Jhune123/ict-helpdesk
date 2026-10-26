<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TicketAssignmentNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignee_is_notified_by_email_and_dashboard_when_ticket_is_created(): void
    {
        Notification::fake();
        Mail::fake();
        $admin = $this->createUserWithRole('admin');
        $assignee = $this->createUserWithRole('it_staff');

        $this->actingAs($admin)
            ->post(route('tickets.store'), [
                'form_type' => 'generic',
                'title' => 'Network access request',
                'description' => 'Please check the office connection.',
                'client_name' => 'Test Client',
                'contact_number' => 'client@example.com',
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

        Mail::assertSent(\App\Mail\TicketNotificationMail::class, function ($mail) use ($ticket) {
            return $mail->ticket->is($ticket)
                && $mail->hasTo('client@example.com')
                && str_contains($mail->messageBody, 'assigned to')
                && str_contains($mail->messageBody, 'Current status: Open')
                && str_contains($mail->messageBody, 'Messages section');
        });
    }

    public function test_new_assignee_is_notified_when_ticket_is_reassigned(): void
    {
        Notification::fake();
        Mail::fake();
        $admin = $this->createUserWithRole('admin');
        $previousAssignee = $this->createUserWithRole('it_staff');
        $newAssignee = $this->createUserWithRole('it_staff');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-ASSIGN-1',
            'title' => 'Reassign this ticket',
            'created_by' => $admin->id,
            'assigned_to' => $previousAssignee->id,
            'status' => 'In Progress',
            'contact_number' => 'client@example.com',
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

        Mail::assertSent(\App\Mail\TicketNotificationMail::class, function ($mail) use ($ticket, $newAssignee) {
            return $mail->ticket->is($ticket)
                && $mail->hasTo('client@example.com')
                && str_contains($mail->messageBody, 'assigned to '.$newAssignee->name)
                && str_contains($mail->messageBody, 'Current status: In Progress');
        });
    }

    public function test_closing_a_ticket_emails_the_client_and_confirms_closed_status(): void
    {
        Notification::fake();
        $client = $this->createUserWithRole('user');
        $assignee = $this->createUserWithRole('it_staff');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-CLOSED-1',
            'title' => 'Resolved network issue',
            'created_by' => $client->id,
            'assigned_to' => $assignee->id,
            'contact_number' => '09123456789',
            'status' => 'In Progress',
        ]);

        $this->actingAs($assignee)
            ->put(route('tickets.update', $ticket), [
                'title' => $ticket->title,
                'description' => $ticket->description,
                'client_name' => $ticket->client_name,
                'status' => 'Closed',
                'assigned_to' => $assignee->id,
            ])
            ->assertRedirect();

        Notification::assertSentTo($ticket, TicketStatusChanged::class, function (TicketStatusChanged $notification, array $channels) use ($ticket, $assignee) {
            $mail = $notification->toMail($ticket);

            return $channels === ['mail']
                && $mail->subject === '✅ Ticket Closed: '.$ticket->ticket_number
                && in_array('✅ Status: CLOSED', $mail->introLines, true)
                && in_array('Assigned IT staff: '.$assignee->name, $mail->introLines, true);
        });
    }

    private function createUserWithRole(string $role): User
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $user = User::factory()->create(['role' => $role]);
        $user->assignRole($role);

        return $user;
    }
}
