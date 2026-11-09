<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackPrintTest extends TestCase
{
    use RefreshDatabase;

    public function test_feedback_print_preview_uses_the_a4_report_and_offers_print_and_download_actions(): void
    {
        [$feedback] = $this->createFeedback();

        $this->actingAs($feedback->user)
            ->get(route('feedbacks.print', $feedback))
            ->assertOk()
            ->assertSee('size: A4 portrait', false)
            ->assertSeeText('Client Satisfaction Survey Report')
            ->assertSeeText('Print')
            ->assertSeeText('Download PDF')
            ->assertSeeText('Feedback Print Tester')
            ->assertSee('src="' . asset('image/KSU-logo.png') . '"', false)
            ->assertSee('src="' . asset('image/Bagong-Pilipinas.png') . '"', false)
            ->assertSee('alt="Kalinga State University logo"', false)
            ->assertSee('alt="Bagong Pilipinas logo"', false);
    }

    public function test_feedback_pdf_download_returns_a_pdf(): void
    {
        [$feedback] = $this->createFeedback();

        $this->actingAs($feedback->user)
            ->get(route('feedbacks.download-pdf', $feedback))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader(
                'content-disposition',
                'attachment; filename=KSU-CSM-Feedback-' . $feedback->ticket->ticket_number . '.pdf'
            );
    }

    private function createFeedback(): array
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create([
            'ticket_number' => 'KSU-ICTO-TIC-FEEDBACK-PRINT',
            'created_by' => $user->id,
            'assigned_to' => null,
        ]);
        $feedback = Feedback::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'client_name' => 'Feedback Print Tester',
            'office_visited' => 'ICTO',
            'services_received' => 'Technical support',
            'staff_assisted' => 'ICT Staff',
            'client_type' => 'Citizen',
            'sex' => 'Prefer not to say',
            'age' => 25,
            'cc1' => 1,
            'cc2' => 1,
            'cc3' => 1,
            'sqd0' => 5,
            'sqd1' => 5,
            'sqd2' => 5,
            'sqd3' => 5,
            'sqd4' => 5,
            'sqd5' => 5,
            'sqd6' => 5,
            'sqd7' => 5,
            'sqd8' => 5,
        ]);

        return [$feedback, $user];
    }
}
