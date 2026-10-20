<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_dashboard_counts_open_and_completed_tickets_and_shows_completion_percentage(): void
    {
        $user = User::factory()->create();

        foreach (['Open', 'open', 'In Progress', 'Closed', 'Finished', 'Condemned', 'Pending'] as $index => $status) {
            Ticket::factory()->create([
                'ticket_number' => 'TICKET-' . $index,
                'created_by' => $user->id,
                'assigned_to' => null,
                'status' => $status,
            ]);
        }

        $this->actingAs($user)
            ->get(route('analytics.index'))
            ->assertOk()
            ->assertViewHas('totalTickets', 6)
            ->assertViewHas('openTickets', 2)
            ->assertViewHas('inProgressTickets', 1)
            ->assertViewHas('closedTickets', 2)
            ->assertViewHas('condemnedTickets', 1)
            ->assertViewHas('closedTicketPercentage', 33.3)
            ->assertSeeText('33.3% of total tickets closed or finished');

        $this->get(route('dashboard.analytics'))
            ->assertOk()
            ->assertViewHas('totalTickets', 6)
            ->assertViewHas('openTickets', 2)
            ->assertViewHas('inProgressTickets', 1)
            ->assertViewHas('closedTickets', 2)
            ->assertViewHas('condemnedTickets', 1)
            ->assertViewHas('closedTicketPercentage', 33.3);
    }

    public function test_analytics_dashboard_shows_zero_completion_when_there_are_no_tickets(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('analytics.index'))
            ->assertOk()
            ->assertViewHas('totalTickets', 0)
            ->assertViewHas('closedTicketPercentage', 0)
            ->assertSeeText('0.0% of total tickets closed or finished');
    }
}
