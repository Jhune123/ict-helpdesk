<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Table name
   protected $fillable = [
    'date',
    'description',
    'requested_by',
    'location',
    'start_time',
    'end_time',
    'assigned_to', // ✅ must be here
    'remarks',
]; 
}
