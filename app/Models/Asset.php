<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_name',
        'fund_cluster',
        'par_no',
        'quantity',
        'unit',
        'description',
        'property_no',
        'date_acquired',
        'amount',
        'purpose',
        'approved_for_issuance',
        'received_from',
        'received_by',
        'date_counted',
    ];
}
