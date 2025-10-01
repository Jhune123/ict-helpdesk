<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Ticket;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // ✅ allow role to be mass assignable
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ✅ Correct: casts should be a property
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Tickets created by this user
     */
    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    /**
     * Tickets assigned to this user
     */
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }
}
