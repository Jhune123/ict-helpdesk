<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActivityLogsExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
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
            $log->subject?->title ?? $log->subject?->name ?? '-',
            $log->subject_id ?? '-',
            $log->description ?? '-',
            $log->created_at->timezone('Asia/Manila')->format('M d, Y h:i A'),
        ];
    }

    /**
     * Set column widths for a cleaner look
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 25,  // User
            'C' => 15,  // Action
            'D' => 35,  // Subject
            'E' => 12,  // Subject ID
            'F' => 50,  // Description
            'G' => 25,  // Date & Time
        ];
    }

    /**
     * Apply styling (Bold Header)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}