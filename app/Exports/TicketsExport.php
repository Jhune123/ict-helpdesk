<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Carbon;

class TicketsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    /**
     * Return collection
     */
    public function collection()
    {
        return $this->tickets;
    }

    /**
     * Map each row (SAFE for Excel & NULL dates)
     */
    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->title,
            $ticket->description,
            $ticket->category?->name ?? '-',
            $ticket->department ?? '-',
            $ticket->assignee?->name ?? '-',
            $ticket->client_name ?? '-',
            $ticket->priority ?? '-',
            $ticket->contact_number ?? '-',
            $ticket->remarks ?? '-',
            ucfirst($ticket->status),

            // ✅ Dates (NULL-safe + PH timezone)
            $this->formatDate($ticket->created_at),
            $this->formatDate($ticket->updated_at),
            $this->formatDate($ticket->date_finished),
        ];
    }

    /**
     * Column headers
     */
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
            'Date Created',
            'Last Updated',
            'Date Finished',
        ];
    }

    /**
     * Helper: Excel-safe date formatter
     */
    private function formatDate($date): string
    {
        if (!$date) {
            return '-';
        }

        return Carbon::parse($date)
            ->timezone('Asia/Manila')
            ->format('M d, Y h:i A');
    }
}
