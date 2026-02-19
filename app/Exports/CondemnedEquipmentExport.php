<?php

namespace App\Exports;

use App\Models\CondemnedEquipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CondemnedEquipmentExport implements FromCollection, WithHeadings
{
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
            'contact Number / E-mail Address',
            'status',
            'date_submitted',
            'date_condemned' // Fixed: Matches your database column
        )->get();
    }

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
            'Contact',
            'Status',
            'Date Submitted',
            'Date Condemned', // Fixed: Header label
        ];
    }
}