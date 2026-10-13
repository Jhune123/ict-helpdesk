<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OjtAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'date',
        'am_in',
        'am_out',
        'pm_in',
        'pm_out',
        'hours_rendered',
        'total_hours',
        'proof_photo',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Fallback accessor to ensure hours_rendered and total_hours stay synchronized
     */
    public function getHoursRenderedAttribute($value)
    {
        return $value ?? $this->attributes['total_hours'] ?? 0;
    }

    public function calculateRenderedMinutes(): int
    {
        $date = Carbon::parse($this->date ?? now(), 'Asia/Manila')->toDateString();
        $totalSeconds = 0;
        $sessions = [
            ['am_in', 'am_out', '08:00:00', '12:00:00'],
            ['pm_in', 'pm_out', '13:00:00', '17:00:00'],
        ];

        foreach ($sessions as [$inField, $outField, $windowStart, $windowEnd]) {
            $timeIn = $this->getAttribute($inField);
            $timeOut = $this->getAttribute($outField);

            if (!$timeIn || !$timeOut) {
                continue;
            }

            $start = Carbon::parse("{$date} {$timeIn}", 'Asia/Manila');
            $end = Carbon::parse("{$date} {$timeOut}", 'Asia/Manila');
            $startLimit = Carbon::parse("{$date} {$windowStart}", 'Asia/Manila');
            $endLimit = Carbon::parse("{$date} {$windowEnd}", 'Asia/Manila');

            if ($start->lt($startLimit)) {
                $start = $startLimit;
            }

            if ($end->gt($endLimit)) {
                $end = $endLimit;
            }

            if ($end->gt($start)) {
                $totalSeconds += $end->getTimestamp() - $start->getTimestamp();
            }
        }

        return min(intdiv($totalSeconds, 60), 8 * 60);
    }

    /**
     * Preserve the decimal-hours value for existing integrations and storage.
     */
    public function calculateDailyHours(): float
    {
        return round($this->calculateRenderedMinutes() / 60, 2);
    }

    public static function formatDuration(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return sprintf(
            '%d hr%s %d min%s',
            $hours,
            $hours === 1 ? '' : 's',
            $remainingMinutes,
            $remainingMinutes === 1 ? '' : 's'
        );
    }
}