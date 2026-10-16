<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Task extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'title',
        'date',
        'description',
        'requested_by',
        'department_id',
        'category_id',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Get the ticket automatically generated for this task.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}