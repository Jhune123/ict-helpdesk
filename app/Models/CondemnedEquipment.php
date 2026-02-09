<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondemnedEquipment extends Model
{
    use HasFactory;

    protected $table = 'condemned_equipments';

   protected $fillable = [
    'property_no',
    'item_name',
    'title',
    'description',
    'ticket_number',   // Ensure this is here
    'attachment_path', // <--- ADD THIS LINE
    'equipment_type',
    'brand_model',
    'serial_no',
    'category',
    'department',
    'it_personnel',
    'client_name',
    'priority',
    'contact',
    'status',
    'date_submitted',
    'date_condemned',
];

    protected $casts = [
        'date_submitted' => 'datetime',
        'date_condemned' => 'datetime', // <--- RENAMED HERE TOO
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->ticket_number)) {
                $year = now()->year;
                $lastTicket = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
                $nextNumber = 1;

                if ($lastTicket && $lastTicket->ticket_number) {
                    if (preg_match('/-(\d{5})$/', $lastTicket->ticket_number, $matches)) {
                        $nextNumber = intval($matches[1]) + 1;
                    }
                }
                $model->ticket_number = 'COND-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}