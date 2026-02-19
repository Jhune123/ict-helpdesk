<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Channels\SemaphoreChannel;

class TicketUpdatedSMS extends Notification
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return [SemaphoreChannel::class]; // Use our custom channel
    }

    public function toSms($notifiable)
    {
        // Limit message to 160 chars to save credit
        return substr($this->message, 0, 160);
    }
}