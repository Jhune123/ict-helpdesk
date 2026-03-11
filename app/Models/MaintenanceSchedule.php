<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Maps to: Office/College, Frequency, Brand, Model, Property No, and Serial No.
     */
    protected $fillable = [
        'office_college',   // Name of Office/College
        'frequency',        // Frequency (daily, weekly, etc.)
        'title',            // Devices/Brand
        'device_model',     // Model
        'property_number',  // Property No.
        'serial_number',    // Serial No.
        'last_run_date',    // Date Performed
        'next_run_date',    // Next Schedule (Calculated)
        'description',      // Internal Checklist / Notes
        'priority',         // Logic metadata (Low, Normal, High, Critical)
        'category'          // Logic metadata
    ];

    /**
     * The attributes that should be cast to native types.
     * Ensures dates are handled as Carbon instances for easy formatting in views.
     */
    protected $casts = [
        'last_run_date' => 'date', 
        'next_run_date' => 'date', 
    ];

    /**
     * Relationship: Name of ICT in charge
     * Supports multiple staff assigned to a single maintenance task.
     * Table: maintenance_schedule_user (pivot)
     */
    public function assignees()
    {
        return $this->belongsToMany(User::class, 'maintenance_schedule_user', 'maintenance_schedule_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Legacy single assignee relationship
     * Kept for backward compatibility if the 'assigned_to' column still exists in your migration.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}