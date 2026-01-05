<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivityLogsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    /**
     * Collection of logs to export
     */
    public function collection()
    {
        return $this->logs;
    }

    /**
     * Headings for Excel file
     */
    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Action',
            'Subject',
            'Subject ID',
            'Description',
            'Date & Time',
        ];
    }

    /**
     * Map each log to a row in Excel
     */
    public function map($log): array
    {
        return [
            $log->id,
            $log->user?->name ?? 'System',
            ucfirst($log->action),
            $log->subject?->title ?? '-',
            $log->subject_id ?? '-',
            $log->description ?? '-',
            $log->created_at->timezone('Asia/Manila')->format('M d, Y h:i A'),
        ];
    }
}
