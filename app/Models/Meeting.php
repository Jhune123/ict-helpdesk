<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'start_time',
        'end_time',
        'location',
        'facilitator',
        'participants',
        'remarks',
    ];

    /**
     * Many-to-Many: Meeting <-> IT Personnel (users)
     *
     * Note: controller and views use "itPersonnels" (plural).
     */
    public function itPersonnels()
    {
        return $this->belongsToMany(User::class, 'meeting_user', 'meeting_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Backwards-compatible alias (in case some code calls itPersonnel).
     */
    public function itPersonnel()
    {
        return $this->itPersonnels();
    }
}
