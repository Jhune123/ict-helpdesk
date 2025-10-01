<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Category;

class Ticket extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'category_id',
        'client_name',
        'department',
        'date_submitted',
        'date_finished',
        'contact_number',
        'assigned_to',
        'created_by',
        'remarks', // ✅ Added
    ];

    /**
     * Casts for attributes
     */
    protected $casts = [
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'date_submitted' => 'datetime',
        'date_finished'  => 'datetime',
    ];

    /**
     * Relationship: The user who created the ticket
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: The IT personnel assigned to the ticket
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Accessor: Get assigned IT personnel name or "Unassigned"
     */
    public function getAssigneeNameAttribute(): string
    {
        return $this->assignee?->name ?? 'Unassigned';
    }

    /**
     * Relationship: Category linked to this ticket
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Accessor: Get category name or "N/A"
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->category?->name ?? 'N/A';
    }
}
