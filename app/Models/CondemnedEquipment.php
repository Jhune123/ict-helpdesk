<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CondemnedEquipment extends Model
{
    use HasFactory;

    protected $table = 'condemned_equipments';

    // 🔓 FIX: Using guarded = [] automatically allows ALL columns (like attachment_path) to be saved.
    // This prevents "Mass Assignment" errors permanently.
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
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
            $year = now()->year;

            // FIX: Check if the ticket number is empty OR if it's the old 'KSU-ICTO' format.
            // We want to force a new 'COND-' number for this table.
            if (empty($model->ticket_number) || !str_starts_with($model->ticket_number, 'COND-')) {
                
                // Find the last COND ticket created this year
                $lastTicket = self::whereYear('created_at', $year)
                                  ->where('ticket_number', 'like', "COND-{$year}-%")
                                  ->orderBy('id', 'desc')
                                  ->first();
                
                $nextNumber = 1;

                if ($lastTicket && $lastTicket->ticket_number) {
                    // Extract the number part safely from COND-2026-00001
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