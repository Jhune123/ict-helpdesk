<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApplication extends Model
{
    public const TYPES = [
        'Vacation Leave',
        'Mandatory/Forced Leave',
        'Sick Leave',
        'Maternity Leave',
        'Paternity Leave',
        'Special Privilege Leave',
        'Solo Parent Leave',
        'Study Leave',
        '10-Day VAWC Leave',
        'Rehabilitation Privilege',
        'Special Leave Benefits for Women',
        'Special Emergency (Calamity) Leave',
        'Adoption Leave',
        "Teacher's Leave",
        'BAR/Board Examination Review',
        'Monetization of Leave Credits',
        'Terminal Leave',
        'Others',
    ];

    protected $fillable = [
        'user_id',
        'applicant_last_name',
        'applicant_first_name',
        'applicant_middle_name',
        'applicant_signature',
        'office_department',
        'position',
        'designation',
        'monthly_basic_salary',
        'filed_at',
        'leave_type',
        'details',
        'vacation_location',
        'vacation_destination',
        'vacation_within_philippines',
        'vacation_within_destination',
        'vacation_abroad',
        'vacation_abroad_destination',
        'sick_treatment',
        'sick_illness',
        'sick_in_hospital',
        'sick_in_hospital_illness',
        'sick_out_patient',
        'sick_out_patient_illness',
        'sick_additional_details',
        'special_leave_illness',
        'special_leave_additional_details',
        'study_purpose',
        'study_completion',
        'study_bar_review',
        'study_other_purpose_selected',
        'study_monetization',
        'study_terminal_leave',
        'other_leave_type',
        'other_purpose',
        'start_date',
        'end_date',
        'working_days',
        'commutation_requested',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected function casts(): array
    {
        return [
            'filed_at' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'reviewed_at' => 'datetime',
            'monthly_basic_salary' => 'decimal:2',
            'working_days' => 'integer',
            'commutation_requested' => 'boolean',
            'vacation_within_philippines' => 'boolean',
            'vacation_abroad' => 'boolean',
            'sick_in_hospital' => 'boolean',
            'sick_out_patient' => 'boolean',
            'study_completion' => 'boolean',
            'study_bar_review' => 'boolean',
            'study_other_purpose_selected' => 'boolean',
            'study_monetization' => 'boolean',
            'study_terminal_leave' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public static function workingDaysBetween(Carbon $start, Carbon $end): int
    {
        $date = $start->copy()->startOfDay();
        $lastDate = $end->copy()->startOfDay();

        if ($date->gt($lastDate)) {
            return 0;
        }

        $totalDays = (int) $date->diffInDays($lastDate) + 1;
        $fullWeeks = intdiv($totalDays, 7);
        $workingDays = $fullWeeks * 5;
        $remainingDays = $totalDays % 7;
        $date->addWeeks($fullWeeks);

        for ($offset = 0; $offset < $remainingDays; $offset++) {
            if ($date->copy()->addDays($offset)->isWeekday()) {
                $workingDays++;
            }
        }

        return $workingDays;
    }

    public function workingDaysWithin(Carbon $start, Carbon $end): int
    {
        $rangeStart = $this->start_date->copy()->max($start->copy()->startOfDay());
        $rangeEnd = $this->end_date->copy()->min($end->copy()->startOfDay());

        if ($rangeStart->gt($rangeEnd)) {
            return 0;
        }

        return self::workingDaysBetween($rangeStart, $rangeEnd);
    }
}
