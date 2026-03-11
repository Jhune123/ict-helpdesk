<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'office',
        'contact_number',
        'request_type',
        'request_type_others',
        'location',
        'mac_address',
        'device',
        'device_others',
        'start_date',
        'completion_date',
        'remarks',
    ];

    // Relationship: A network request belongs to a specific ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Relationship: A network request belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
