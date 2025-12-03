<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Retrieve all tickets with related data
     */
    public function collection()
    {
        return Ticket::with(['category', 'assignee', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'Ticket ID',
            'Title',
            'Description',
            'Category',
            'Department',
            'IT Personnel',
            'Priority',
            'Client Name',
            'Contact Number',
            'Remarks',
            'Status',
            'Date Submitted',
            'Date Finished',
            'Created By',
        ];
    }

    /**
     * Map each ticket to row data
     */
    public function map($ticket): array
    {
        return [
            $ticket->id,
            $ticket->title,
            $ticket->description,
            $ticket->category?->name ?? 'N/A',
            $ticket->department ?? 'N/A',
            $ticket->assignee?->name ?? 'Unassigned',
            $ticket->priority ?? 'Normal',
            $ticket->client_name,
            $ticket->contact_number ?? '',
            $ticket->remarks ?? '',
            $ticket->status,
            $ticket->date_submitted?->format('Y-m-d H:i'),
            $ticket->date_finished?->format('Y-m-d H:i') ?? '',
            $ticket->creator?->name ?? 'System',
        ];
    }
}
