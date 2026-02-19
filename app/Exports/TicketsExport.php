<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function collection()
    {
        return $this->tickets;
    }

    public function headings(): array
    {
        return [
            'Ticket #',
            'Title',
            'Description',
            'Equipment Type',
            'Brand / Model',
            'Serial No.',
            'Category',
            'Department',
            'IT Personnel',
            'Client',
            'Priority',
            'Contact Number / E-mail Address',
            'Status',
            'Submitted',
            'Finished',
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->title,
            $ticket->description,
            $ticket->equipment_type ?? '-',
            $ticket->brand_model ?? '-',
            $ticket->serial_no ?? '-',
            $ticket->category?->name ?? '-',
            $ticket->department ?? '-',
            $ticket->assignee?->name ?? '-',
            $ticket->client_name,
            $ticket->priority ?? 'Normal',
            $ticket->contact_number ?? '-',
            $ticket->status,
            $ticket->date_submitted?->format('M d, Y h:i A') ?? '-',
            $ticket->date_finished?->format('M d, Y h:i A') ?? '-',
        ];
    }
}
