<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',            // Legacy / Quick reference role
        'is_ojt',          // Flag to check if user is an OJT student
        'student_id',      // OJT Student Identification Number
        'department_id',   // Assigned KSU Department ID
        'required_hours',  // Target OJT Hours (e.g., 486)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_ojt'            => 'boolean',
        'required_hours'    => 'integer',
    ];

    /* -------------------------------------------------------------------------- */
    /*                                RELATIONSHIPS                               */
    /* -------------------------------------------------------------------------- */

    /**
     * Tickets created by this user
     */
    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    /**
     * Tickets assigned to this user (IT Personnel)
     */
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    /**
     * Department where this user or OJT student is assigned
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Attendance records logged by this OJT student
     */
    public function ojtAttendances()
    {
        return $this->hasMany(OjtAttendance::class);
    }

    /* -------------------------------------------------------------------------- */
    /*                               HELPER METHODS                               */
    /* -------------------------------------------------------------------------- */

    /**
     * Scope query to only include OJT users
     */
    public function scopeOjt($query)
    {
        return $query->where('is_ojt', true);
    }

    /**
     * Get the exact total rendered OJT hours from attendance timestamps.
     */
    public function getTotalRenderedHoursAttribute(): float
    {
        $attendances = $this->relationLoaded('ojtAttendances')
            ? $this->ojtAttendances
            : $this->ojtAttendances()->get();

        $minutes = $attendances->sum(
            fn (OjtAttendance $attendance) => $attendance->calculateRenderedMinutes()
        );

        return $minutes / 60;
    }

    /**
     * Get remaining OJT hours
     */
    public function getRemainingHoursAttribute(): float
    {
        $required = $this->required_hours ?? 0;
        $rendered = $this->total_rendered_hours;

        return max(0, $required - $rendered);
    }
}