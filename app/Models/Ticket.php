<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable; // ✅ REQUIRED for sending Emails/SMS
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\Feedback;

class Ticket extends Model
{
    use HasFactory, Notifiable; // ✅ REQUIRED

    /**
     * =======================
     * MASS ASSIGNABLE FIELDS
     * =======================
     */
    protected $fillable = [
        'ticket_number',
        'title',
        'description',

        // Equipment Fields
        'equipment_type',
        'brand_model',
        'serial_no',

        'status',
        'priority',
        'category_id',
        'client_name',
        'department',
        'date_submitted',
        'date_finished',
        'contact_number', // Acts as Phone OR Email
        'assigned_to',
        'created_by',
        'remarks',
    ];

    /**
     * =======================
     * CASTS
     * =======================
     */
    protected $casts = [
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'date_submitted' => 'datetime',
        'date_finished'  => 'datetime',
    ];

    /**
     * =======================
     * RELATIONSHIPS
     * =======================
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class)->orderBy('created_at', 'asc');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    /**
     * =======================
     * ACCESSORS / HELPERS
     * =======================
     */
    public function getAssigneeNameAttribute(): string
    {
        return $this->assignee?->name ?? 'Unassigned';
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->category?->name ?? 'N/A';
    }

    public function getContactNumberAttribute($value): string
    {
        return $value ?: 'N/A';
    }

    public function getDepartmentAttribute($value): string
    {
        return $value ?: 'N/A';
    }

    public function getEquipmentTypeAttribute($value): string
    {
        return $value ?: 'N/A';
    }

    public function getBrandModelAttribute($value): string
    {
        return $value ?: 'N/A';
    }

    public function getSerialNoAttribute($value): string
    {
        return $value ?: 'N/A';
    }

    /**
     * =======================
     * 🧠 SMART NOTIFICATION ROUTING
     * =======================
     */

    /**
     * 📧 Route for Email:
     * Only returns the address if it looks like a valid email.
     */
    public function routeNotificationForMail($notification)
    {
        // Get the raw value (ignoring the "N/A" accessor)
        $contact = $this->getRawOriginal('contact_number');

        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            return $contact;
        }

        return null; // Not an email? Don't send email.
    }

    /**
     * 📱 Route for Semaphore (SMS):
     * Only returns the number if it is NOT an email.
     */
    public function routeNotificationForSemaphore()
    {
        $contact = $this->getRawOriginal('contact_number');

        // If it's an email or empty, don't send SMS
        if (empty($contact) || filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        return $contact;
    }
}