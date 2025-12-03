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
        'ticket_number', // ✅ added for auto-generation
        'title',
        'description',
        'status',
        'priority',
        'category_id',
        'client_name',
        'department', // kept as text field (not relationship)
        'date_submitted',
        'date_finished',
        'contact_number',
        'assigned_to',
        'created_by',
        'remarks',
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
     * Boot method to auto-generate ticket_number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                // Generate format: TCK-YYYYMMDD-0001
                $date = now()->format('Ymd');
                $lastTicket = Ticket::whereDate('created_at', now()->toDateString())
                                    ->latest()
                                    ->first();
                $lastNumber = $lastTicket ? (int)substr($lastTicket->ticket_number, -4) : 0;
                $ticket->ticket_number = 'TCK-' . $date . '-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

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

    /**
     * Relationship: Comments linked to this ticket
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship: Attachments linked to this ticket
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Accessor: Get contact number safely
     */
    public function getContactNumberAttribute($value)
    {
        return $value ?? 'N/A';
    }

    /**
     * Accessor: Get department safely (text field)
     */
    public function getDepartmentAttribute($value)
    {
        return $value ?? 'N/A';
    }

    /**
     * Relationship: Feedback linked to this ticket
     */
    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}
