<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondemnedEquipment extends Model
{
    use HasFactory;

    protected $table = 'condemned_equipments';

    /**
     * Using guarded = [] allows all columns to be saved automatically.
     * This is perfect for an archive table where fields might change.
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_submitted' => 'datetime',
        'date_condemned' => 'datetime', // Matches the date_condemned sent by TicketController
        'date_finished'  => 'datetime',
    ];

    /**
     * Auto-generate Archive Ticket Number (COND-YYYY-XXXXX)
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $year = now()->year;

            // We force a new 'COND-' reference number even if it came from a 'KSU-ICTO' ticket
            if (empty($model->ticket_number) || !str_starts_with($model->ticket_number, 'COND-')) {
                
                // Save the original ticket number in description or a separate field if needed before overwriting
                if (!empty($model->ticket_number) && empty($model->notes)) {
                    $model->notes = "Original Ticket Reference: " . $model->ticket_number;
                }

                $lastTicket = self::where('ticket_number', 'like', "COND-{$year}-%")
                                  ->orderBy('id', 'desc')
                                  ->first();
                
                $nextNumber = 1;

                if ($lastTicket && $lastTicket->ticket_number) {
                    // Safely extract the last 5 digits
                    if (preg_match('/-(\d{5})$/', $lastTicket->ticket_number, $matches)) {
                        $nextNumber = intval($matches[1]) + 1;
                    }
                }

                // Generates format: COND-2026-00001
                $model->ticket_number = 'COND-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }

            // Ensure date_condemned is set if the controller forgot it
            if (empty($model->date_condemned)) {
                $model->date_condemned = now();
            }
        });
    }
}