<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Helpers\ActivityLogger;

class TicketObserver
{
    public function created(Ticket $ticket)
    {
        ActivityLogger::log('Created Ticket', $ticket, "Ticket #{$ticket->ticket_number} created");
    }

    public function updated(Ticket $ticket)
    {
        ActivityLogger::log('Updated Ticket', $ticket, "Ticket #{$ticket->ticket_number} updated");
    }

    public function deleted(Ticket $ticket)
    {
        ActivityLogger::log('Deleted Ticket', $ticket, "Ticket #{$ticket->ticket_number} deleted");
    }

    public function restored(Ticket $ticket)
    {
        ActivityLogger::log('Restored Ticket', $ticket, "Ticket #{$ticket->ticket_number} restored");
    }
}
