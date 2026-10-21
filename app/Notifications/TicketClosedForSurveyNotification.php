<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketClosedForSurveyNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'Ticket closed — please complete the satisfaction survey',
            'message' => 'Your ticket '.$this->ticket->ticket_number.' has been closed. Please complete the Client Satisfaction Survey for this ticket.',
            'action_label' => 'Complete survey',
            'action_url' => route('feedbacks.create', $this->ticket),
        ];
    }
}
