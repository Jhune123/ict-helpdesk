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
        'ticket_number',
        'attachment_path',
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

    /**
     * The attributes that should be cast to native types.
     * This fixes the "Call to member function format() on string" error.
     */
    protected $casts = [
        'date_submitted' => 'datetime',
        'date_condemned' => 'datetime',
    ];

    /**
     * Auto-generate Ticket Number (COND-YYYY-XXXXX)
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->ticket_number)) {
                $year = now()->year;
                
                // Find the last ticket created this year
                $lastTicket = self::whereYear('created_at', $year)
                                  ->whereNotNull('ticket_number')
                                  ->orderBy('id', 'desc')
                                  ->first();
                
                $nextNumber = 1;

                if ($lastTicket && $lastTicket->ticket_number) {
                    // Extract the number part from COND-2026-00001
                    if (preg_match('/-(\d{5})$/', $lastTicket->ticket_number, $matches)) {
                        $nextNumber = intval($matches[1]) + 1;
                    }
                }

                // Format: COND-2026-00001
                $model->ticket_number = 'COND-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}