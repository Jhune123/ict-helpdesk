<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'unit_status',
        'property_no',
        'date_acquired',
        'amount',
        'purpose',
        'approved_for_issuance',
        'received_from',
        'received_by',
        'date_counted',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date_acquired' => 'date',
        'date_counted'  => 'date',
        'amount'        => 'decimal:2',
        'quantity'      => 'integer',
    ];

    /**
     * Mutator: Clean up 'amount' before saving.
     * If the user leaves it blank, save as 0.00 instead of a crash.
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => is_numeric($value) ? $value : 0,
        );
    }

    /**
     * Mutator: Clean up 'date_acquired' before saving.
     * Prevents error if the date string is empty.
     */
    protected function dateAcquired(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => empty($value) ? null : $value,
        );
    }

    /**
     * Mutator: Clean up 'date_counted' before saving.
     */
    protected function dateCounted(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => empty($value) ? null : $value,
        );
    }

    /**
     * Helper: Get formatted amount (Example: ₱1,500.00)
     * Usage in Blade: {{ $asset->formatted_amount }}
     */
    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => '₱' . number_format($this->amount ?? 0, 2),
        );
    }
}