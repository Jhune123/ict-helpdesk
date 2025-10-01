<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketStatusUpdated extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database']; // you can also add 'mail' if needed
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'Ticket Updated',
            'message' => 'Your ticket #' . $this->ticket->id . ' status has been updated to ' . $this->ticket->status,
        ];
    }
}
