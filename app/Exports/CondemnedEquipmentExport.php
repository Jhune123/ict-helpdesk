<?php

namespace App\Exports;

use App\Models\CondemnedEquipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CondemnedEquipmentExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return CondemnedEquipment::select(
            'ticket_number',
            'property_no',
            'item_name',
            'title',
            'description',
            'equipment_type',
            'brand_model',
            'serial_no',
            'category',
            'department',
            'it_personnel',
            'client_name',
            'priority',
            'contact', // Corrected: Using the 'contact' column from your migration
            'status',
            'date_submitted',
            'date_condemned' 
        )->get();
    }

    /**
     * Define the spreadsheet headers.
     */
    public function headings(): array
    {
        return [
            'Ticket Number',
            'Property No',
            'Item Name',
            'Title',
            'Description',
            'Equipment Type',
            'Brand / Model',
            'Serial No',
            'Category',
            'Department',
            'IT Personnel',
            'Client Name',
            'Priority',
            'Contact Info',
            'Status',
            'Date Submitted',
            'Date Condemned',
        ];
    }
}