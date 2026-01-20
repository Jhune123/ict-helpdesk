<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * Allow mass assignment
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Relationship to tickets
     * Ticket.department (string) → Department.name
     */
    public function tickets()
    {
        return $this->hasMany(
            \App\Models\Ticket::class,
            'department', // foreign key in tickets table
            'name'        // local key in departments table
        );
    }
}
