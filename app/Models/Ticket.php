<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\Feedback;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
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
        'remarks',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_submitted' => 'datetime',
        'date_finished' => 'datetime',
    ];

    // RELATIONSHIPS
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function category() { return $this->belongsTo(Category::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function attachments() { return $this->hasMany(Attachment::class); }
    public function feedback() { return $this->hasOne(Feedback::class); }

    // ACCESSORS
    public function getAssigneeNameAttribute(): string { return $this->assignee?->name ?? 'Unassigned'; }
    public function getCategoryNameAttribute(): string { return $this->category?->name ?? 'N/A'; }
    public function getContactNumberAttribute($value) { return $value ?: 'N/A'; }
    public function getDepartmentAttribute($value) { return $value ?: 'N/A'; }
}
