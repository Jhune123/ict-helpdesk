<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'date',
        'description',
        'requested_by',
        'department_id',
        'location',
        'start_time',
        'end_time',
        'assigned_to',
        'remarks',
        'ticket_id',
    ];

    /**
     * Relationship: Task belongs to a Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relationship: Get the ticket automatically generated for this task.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}