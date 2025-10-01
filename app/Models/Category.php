<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Allow mass assignment for name
    protected $fillable = [
        'name',
    ];

    // Optional: relationship to tickets
    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }
}
