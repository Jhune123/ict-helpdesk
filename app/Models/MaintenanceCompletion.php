<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceCompletion extends Model
{
    protected $fillable = [
        'maintenance_schedule_id',
        'completed_at',
        'snapshot',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'snapshot' => 'array',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class, 'maintenance_schedule_id');
    }
}
