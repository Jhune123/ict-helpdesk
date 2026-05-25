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
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_submitted' => 'datetime',
        'date_condemned' => 'datetime',
        'date_finished'  => 'datetime',
    ];

    /**
     * Auto-generate Archive Ticket Number (COND-YYYY-XXXXX)
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $year = now()->year;

            // Check if we need to convert an incoming ticket number into the asset archive format
            if (!empty($model->ticket_number) && !str_starts_with($model->ticket_number, 'COND-')) {
                
                $referenceText = "Original Ticket Reference: " . $model->ticket_number;
                
                // FIXED: Changed $model->notes to $model->description to avoid missing column errors
                if (empty($model->description)) {
                    $model->description = $referenceText;
                } else {
                    $model->description .= "\n" . $referenceText;
                }

                // Look for the last generated condemnation voucher for the current year
                $lastTicket = self::where('ticket_number', 'like', "COND-{$year}-%")
                                  ->orderBy('id', 'desc')
                                  ->first();
                
                $nextNumber = 1;

                if ($lastTicket && $lastTicket->ticket_number) {
                    // Safely extract the last 5 trailing sequential digits
                    if (preg_match('/-(\d{5})$/', $lastTicket->ticket_number, $matches)) {
                        $nextNumber = intval($matches[1]) + 1;
                    }
                }

                // Generates standardized format: COND-2026-00001
                $model->ticket_number = 'COND-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }

            // Ensure date_condemned is initialized if not explicitly passed
            if (empty($model->date_condemned)) {
                $model->date_condemned = now();
            }
        });
    }
}