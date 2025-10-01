<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    // Allow mass assignment for name
    protected $fillable = [
        'name',
    ];

    // Optional: If you want relationship to tickets
    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'department');
    }
}
