<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_id', 
        'user_id', 
        'client_name', 
        'office_visited', 
        'services_received', 
        'staff_assisted', 
        'other_staff',
        'client_type', 
        'agency_name', 
        'sex', 
        'age',
        'cc1', 
        'cc2', 
        'cc3',
        'sqd0', 'sqd1', 'sqd2', 'sqd3', 'sqd4', 'sqd5', 'sqd6', 'sqd7', 'sqd8',
        'suggestions'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'age' => 'integer',
        'cc1' => 'integer',
        'cc2' => 'integer',
        'cc3' => 'integer',
        'sqd0' => 'integer',
        'sqd1' => 'integer',
        'sqd2' => 'integer',
        'sqd3' => 'integer',
        'sqd4' => 'integer',
        'sqd5' => 'integer',
        'sqd6' => 'integer',
        'sqd7' => 'integer',
        'sqd8' => 'integer',
    ];

    /**
     * Get the ticket associated with the feedback.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user (ICT Personnel/Admin) who managed the feedback.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}