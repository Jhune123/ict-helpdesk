<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketChatReplyNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Ticket $ticket,
        private readonly User $sender,
        private readonly string $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'New reply from IT',
            'message' => $this->sender->name.' replied to your ticket '.$this->ticket->ticket_number.': '.$this->message,
            'sender_name' => $this->sender->name,
            'reply' => $this->message,
        ];
    }
}
