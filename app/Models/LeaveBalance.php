<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    protected $fillable = [
        'user_id',
        'vacation_days',
        'sick_days',
        'teachers_days',
        'mandatory_days',
        'special_privilege_days',
        'wellness_break_days',
    ];

    protected function casts(): array
    {
        return [
            'vacation_days' => 'decimal:2',
            'sick_days' => 'decimal:2',
            'teachers_days' => 'decimal:2',
            'mandatory_days' => 'decimal:2',
            'special_privilege_days' => 'decimal:2',
            'wellness_break_days' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
