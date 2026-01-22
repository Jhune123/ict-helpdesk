<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Carbon;

class TicketsExport implements FromCollection, WithHeadings
{
    protected $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function collection()
    {
        return $this->tickets->map(function($ticket){
            return [
                'Ticket #' => $ticket->ticket_number,
                'Title' => $ticket->title,
                'Description' => $ticket->description,
                'Category' => $ticket->category?->name ?? '-',
                'Department' => $ticket->department ?? '-',
                'IT Personnel' => $ticket->assignee?->name ?? '-',
                'Client Name' => $ticket->client_name,
                'Priority' => $ticket->priority,
                'Contact Number' => $ticket->contact_number,
                'Remarks' => $ticket->remarks,
                'Status' => $ticket->status,
                'Date Submitted' => optional($ticket->date_submitted)->timezone('Asia/Manila')->format('M d, Y h:i A'),
                'Date Finished' => optional($ticket->date_finished)->timezone('Asia/Manila')->format('M d, Y h:i A'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Ticket #',
            'Title',
            'Description',
            'Category',
            'Department',
            'IT Personnel',
            'Client Name',
            'Priority',
            'Contact Number',
            'Remarks',
            'Status',
            'Date Submitted',
            'Date Finished',
        ];
    }
}
