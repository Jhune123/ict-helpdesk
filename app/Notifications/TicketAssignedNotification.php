<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
        return ['database', 'mail'];
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'Ticket Assigned',
            'message' => "You have been assigned ticket {$this->ticket->ticket_number}: {$this->ticket->title}",
        ];
    }

    public function toDatabase($notifiable)
    {
        return $this->toArray($notifiable);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Ticket assigned to you: '.$this->ticket->ticket_number)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('A helpdesk ticket has been assigned to you.')
            ->line('Ticket: '.$this->ticket->ticket_number)
            ->line('Title: '.$this->ticket->title)
            ->line('Client: '.$this->ticket->client_name)
            ->line('Status: '.$this->ticket->status)
            ->action('View Ticket', route('tickets.show', $this->ticket))
            ->line('Please sign in to the ICT Helpdesk to review and process this ticket.');
    }
}
