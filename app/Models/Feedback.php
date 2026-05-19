<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'ticket_id', 'user_id', 
        'client_name', 'office_visited', 'services_received', 'staff_assisted', 'other_staff', // 👈 NEW
        'client_type', 'agency_name', 'sex', 'age',
        'cc1', 'cc2', 'cc3',
        'sqd0', 'sqd1', 'sqd2', 'sqd3', 'sqd4', 'sqd5', 'sqd6', 'sqd7', 'sqd8',
        'suggestions'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}