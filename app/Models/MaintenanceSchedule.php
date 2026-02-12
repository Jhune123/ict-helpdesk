<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'office_college',   
        'device_model',     
        'property_number',  
        'serial_number',    
        'description',
        'frequency',        
        'last_run_date',    
        'next_run_date',
        // 'assigned_to', <-- REMOVED this to prevent saving errors
        'priority',
        'category'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'last_run_date' => 'date', 
        'next_run_date' => 'date', 
    ];

    /**
     * Relationship for Multiple Staff Assignment
     * This links to the pivot table: maintenance_schedule_user
     */
    public function assignees()
    {
        return $this->belongsToMany(User::class, 'maintenance_schedule_user', 'maintenance_schedule_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Legacy single assignee relationship (Fallback Only)
     * Useful for backward compatibility during transition
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}