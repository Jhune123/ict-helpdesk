<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketChatMessageNotification;
use App\Notifications\TicketChatReplyNotification;
use App\Notifications\TicketClosedForSurveyNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_message_selected_it_personnel_about_their_ticket(): void
    {
        Mail::fake();
        $client = $this->createUserWithRole('user');
        $staff = $this->createUserWithRole('it_staff');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-100',
            'created_by' => $client->id,
            'assigned_to' => null,
        ]);

        $this->actingAs($client)
            ->post(route('dashboard.chat.store'), [
                'ticket_id' => $ticket->id,
                'recipient_id' => $staff->id,
                'message' => 'Could you please share an update?',
            ])
            ->assertRedirect(route('dashboard.chat.index'))
            ->assertSessionHas('success');

        $comment = Comment::where('ticket_id', $ticket->id)->firstOrFail();
        $this->assertSame($client->id, $comment->user_id);
        $this->assertSame($staff->id, $comment->recipient_id);
        $this->assertSame('Could you please share an update?', $comment->message);

        $notification = $staff->notifications()->firstOrFail();
        $this->assertSame(TicketChatMessageNotification::class, $notification->type);
        $this->assertSame('Could you please share an update?', $notification->data['client_message']);
        $this->assertStringContainsString('Could you please share an update?', $notification->data['message']);
    }

    public function test_dashboard_shows_only_the_clients_own_ticket_conversations(): void
    {
        $client = $this->createUserWithRole('user');
        $anotherClient = $this->createUserWithRole('user');
        $staff = $this->createUserWithRole('it_staff');
        $ownTicket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-101',
            'created_by' => $client->id,
            'assigned_to' => null,
        ]);
        $otherTicket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-102',
            'created_by' => $anotherClient->id,
            'assigned_to' => null,
        ]);

        Comment::create([
            'ticket_id' => $ownTicket->id,
            'user_id' => $client->id,
            'recipient_id' => $staff->id,
            'message' => 'My private ticket follow-up',
        ]);
        Comment::create([
            'ticket_id' => $otherTicket->id,
            'user_id' => $anotherClient->id,
            'recipient_id' => $staff->id,
            'message' => 'Someone else ticket follow-up',
        ]);

        $this->actingAs($client)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSeeText('My private ticket follow-up')
            ->assertDontSeeText('Someone else ticket follow-up')
            ->assertSeeText('Message Us')
            ->assertSee('href="'.route('dashboard.chat.index').'"', false);

        $this->get(route('dashboard.chat.index'))
            ->assertOk()
            ->assertSeeText('Follow Up')
            ->assertSeeText('Check Your Ticket Status with IT Staff')
            ->assertSeeText('My private ticket follow-up')
            ->assertDontSeeText('Someone else ticket follow-up');
    }

    public function test_client_without_tickets_sees_only_message_us_linking_to_ticket_creation(): void
    {
        $client = $this->createUserWithRole('user');

        $this->actingAs($client)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText('Message Us')
            ->assertSee('href="'.route('dashboard.chat.index').'"', false)
            ->assertDontSeeText('You do not have any tickets to discuss yet.');

        $this->get(route('dashboard.chat.index'))
            ->assertOk()
            ->assertSeeText('Follow Up')
            ->assertSeeText('Check Your Ticket Status with IT Staff')
            ->assertSeeText('You do not have any tickets to discuss yet.')
            ->assertSee('href="'.route('tickets.create').'"', false);
    }

    public function test_it_and_admin_dashboards_do_not_show_client_chat_prompt_or_message_button(): void
    {
        foreach (['it_staff', 'admin'] as $role) {
            $staffUser = $this->createUserWithRole($role);

            $this->actingAs($staffUser)
                ->get(route('dashboard'))
                ->assertOk()
                ->assertDontSeeText('Message Us');

            $this->get(route('dashboard.chat.index'))->assertForbidden();
        }
    }

    public function test_ticket_request_forms_show_internal_processing_to_it_and_admin_accounts(): void
    {
        $this->ensureRolesExist();

        foreach (['it_staff', 'admin'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('tickets.create'))
                ->assertOk()
                ->assertSeeText('IT Internal Processing')
                ->assertSeeText('Assign To (IT Personnel)')
                ->assertSeeText('Internal Remarks');
        }
    }

    public function test_ticket_request_forms_hide_internal_processing_from_clients(): void
    {
        $client = $this->createUserWithRole('user');

        $this->actingAs($client)
            ->get(route('tickets.create'))
            ->assertOk()
            ->assertDontSeeText('IT Internal Processing')
            ->assertDontSeeText('Assign To (IT Personnel)')
            ->assertDontSeeText('Internal Remarks');
    }

    public function test_client_cannot_message_a_ticket_they_do_not_own(): void
    {
        $client = $this->createUserWithRole('user');
        $otherClient = $this->createUserWithRole('user');
        $staff = $this->createUserWithRole('it_staff');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-103',
            'created_by' => $otherClient->id,
            'assigned_to' => null,
        ]);

        $this->actingAs($client)
            ->post(route('dashboard.chat.store'), [
                'ticket_id' => $ticket->id,
                'recipient_id' => $staff->id,
                'message' => 'Unauthorized follow-up',
            ])
            ->assertNotFound();

        $this->assertDatabaseMissing('comments', [
            'ticket_id' => $ticket->id,
            'message' => 'Unauthorized follow-up',
        ]);
    }

    public function test_it_reply_creates_a_client_dashboard_notification_with_the_reply_text(): void
    {
        Mail::fake();
        $client = $this->createUserWithRole('user');
        $staff = $this->createUserWithRole('it_staff');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-104',
            'created_by' => $client->id,
            'assigned_to' => $staff->id,
        ]);

        $this->actingAs($staff)
            ->post(route('tickets.comments.store', $ticket), [
                'message' => 'We are checking your request now.',
            ])
            ->assertRedirect(route('tickets.show', $ticket));

        $notification = $client->notifications()->firstOrFail();
        $this->assertSame(TicketChatReplyNotification::class, $notification->type);
        $this->assertSame('We are checking your request now.', $notification->data['reply']);
        $this->assertStringContainsString('We are checking your request now.', $notification->data['message']);

        $this->actingAs($client)
            ->get(route('dashboard.chat.index'))
            ->assertOk()
            ->assertSeeText('We are checking your request now.');
    }

    public function test_staff_closing_ticket_notifies_client_to_complete_the_satisfaction_survey(): void
    {
        Mail::fake();
        $client = $this->createUserWithRole('user');
        $staff = $this->createUserWithRole('it_staff');
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-105',
            'title' => 'Ticket conversation stays off dashboard',
            'created_by' => $client->id,
            'assigned_to' => $staff->id,
            'contact_number' => $client->email,
            'status' => 'In Progress',
        ]);

        $this->actingAs($staff)
            ->put(route('tickets.update', $ticket), [
                'title' => $ticket->title,
                'description' => $ticket->description,
                'client_name' => $ticket->client_name,
                'status' => 'Closed',
                'assigned_to' => $staff->id,
            ])
            ->assertRedirect(route('tickets.index', ['view' => 'archive']));

        $notification = $client->notifications()->firstOrFail();
        $this->assertSame(TicketClosedForSurveyNotification::class, $notification->type);
        $this->assertSame(
            route('feedbacks.create', $ticket),
            $notification->data['action_url']
        );
        $this->assertStringContainsString(
            'Please complete the Client Satisfaction Survey',
            $notification->data['message']
        );

        $this->actingAs($client)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertSeeText('Please complete the Client Satisfaction Survey')
            ->assertSee(route('feedbacks.create', $ticket), false);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertDontSeeText('Ticket conversation stays off dashboard');
    }

    private function createUserWithRole(string $role): User
    {
        $this->ensureRolesExist();

        $user = User::factory()->create(['role' => $role]);
        $user->assignRole($role);

        return $user;
    }

    private function ensureRolesExist(): void
    {
        foreach (['admin', 'it_staff', 'user', 'ojt', 'supervisor', 'ojt_supervisor'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
