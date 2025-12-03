<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'feedbacks';

    protected $fillable = [
        'ticket_id',
        'client_name',
        'rating',
        'comments',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
