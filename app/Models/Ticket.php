<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable; 
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\Feedback;
use App\Models\NetworkRequest; 

class Ticket extends Model
{
    use HasFactory, Notifiable;

    /**
     * ✅ Mass-assignable fields.
     */
    protected $fillable = [
        'ticket_number',
        'title',
        'description',
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
        'contact_number', 
        'assigned_to',
        'created_by',
        'remarks',
        'form_data', 
    ];

    /**
     * ✅ Automatic casting. 
     * The 'array' cast is vital for the dynamic form fields in your create/edit views.
     */
    protected $casts = [
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'date_submitted' => 'datetime',
        'date_finished'  => 'datetime',
        'form_data'      => 'array', 
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

    public function networkRequest()
    {
        return $this->hasOne(NetworkRequest::class);
    }

    /**
     * =======================
     * ACCESSORS / HELPERS
     * =======================
     */
    
    // Virtual attributes for cleaner blade syntax
    public function getAssigneeNameAttribute(): string
    {
        return $this->assignee?->name ?? 'Unassigned';
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->category?->name ?? 'N/A';
    }

    /**
     * ✅ UI Fallback Accessors
     * These ensure that if a field is null, it displays "N/A" in your tables/views.
     */
    public function getContactNumberAttribute($value): string { return $value ?: 'N/A'; }
    public function getDepartmentAttribute($value): string    { return $value ?: 'N/A'; }
    public function getEquipmentTypeAttribute($value): string { return $value ?: 'N/A'; }
    public function getBrandModelAttribute($value): string    { return $value ?: 'N/A'; }
    public function getSerialNoAttribute($value): string      { return $value ?: 'N/A'; }

    /**
     * =======================
     * SMART NOTIFICATION ROUTING
     * =======================
     */

    /**
     * Route for Email Notifications.
     * Checks if contact_number contains a valid email address.
     */
    public function routeNotificationForMail($notification)
    {
        $contact = $this->getRawOriginal('contact_number');
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            return $contact;
        }
        return null;
    }

    /**
     * Route for SMS Notifications (Semaphore API).
     * If contact is a phone number, it returns the string for the SMS driver.
     */
    public function routeNotificationForSemaphore()
    {
        $contact = $this->getRawOriginal('contact_number');
        if (empty($contact) || filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            return null;
        }
        return $contact;
    }
}