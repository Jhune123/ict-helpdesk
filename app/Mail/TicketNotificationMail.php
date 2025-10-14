<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $subjectText;
    public $messageBody;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, $subjectText, $messageBody)
    {
        $this->ticket = $ticket;
        $this->subjectText = $subjectText;
        $this->messageBody = $messageBody;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subjectText)
                    ->view('emails.ticket_notification')
                    ->with([
                        'ticket' => $this->ticket,
                        'messageBody' => $this->messageBody,
                    ]);
    }
}
