<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketAssignedNotification extends Notification
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database']; // Storing in the database
    }

    public function toDatabase($notifiable)
    {
        // These keys map directly to $notification->data['...'] in your Blade nav
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'Ticket Assigned',
            'message' => "You have been assigned to ticket #{$this->ticket->id}: {$this->ticket->title}",
        ];
    }
}