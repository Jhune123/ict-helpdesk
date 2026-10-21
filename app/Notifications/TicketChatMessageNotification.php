<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketChatMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Ticket $ticket,
        private readonly User $client,
        private readonly string $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'New client message',
            'message' => $this->client->name.' sent a message about ticket '.$this->ticket->ticket_number.': '.$this->message,
            'client_name' => $this->client->name,
            'client_message' => $this->message,
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New client message for ticket '.$this->ticket->ticket_number)
            ->greeting('Hello '.$notifiable->name.',')
            ->line($this->client->name.' sent you a message regarding ticket '.$this->ticket->ticket_number.'.')
            ->line('Ticket: '.$this->ticket->title)
            ->line('Message: '.$this->message)
            ->action('View Ticket', route('tickets.show', $this->ticket))
            ->line('Please sign in to the ICT Helpdesk to reply.');
    }
}
