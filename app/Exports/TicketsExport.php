<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    /**
     * Create a new export instance.
     * Accepts null, an HTTP Request object, or a Collection/Array of Tickets.
     *
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // 1. Return direct collection if passed directly
        if ($this->data instanceof Collection) {
            return $this->data;
        }

        if (is_array($this->data)) {
            return collect($this->data);
        }

        // 2. Otherwise build query dynamically
        $query = Ticket::with(['category', 'assignee']);

        // 3. Apply active filters if a Request object was passed
        if ($this->data instanceof Request) {
            if ($search = $this->data->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('ticket_number', 'like', "%{$search}%")
                      ->orWhere('serial_no', 'like', "%{$search}%")
                      ->orWhere('department', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($catQuery) use ($search) {
                          $catQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            if ($this->data->filled('month')) {
                $query->whereMonth('date_submitted', $this->data->month);
            }

            if ($this->data->filled('year')) {
                $query->whereYear('date_submitted', $this->data->year);
            }

            if ($this->data->input('view') === 'archive') {
                $query->whereIn('status', ['Closed', 'Finished', 'closed', 'finished']);
            }
        }

        return $query->latest()->get();
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
        $submittedAt = $ticket->date_submitted 
            ? Carbon::parse($ticket->date_submitted)->format('M d, Y h:i A') 
            : '-';

        $finishedAt = $ticket->date_finished 
            ? Carbon::parse($ticket->date_finished)->format('M d, Y h:i A') 
            : '-';

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
            $submittedAt,
            $finishedAt,
        ];
    }
}