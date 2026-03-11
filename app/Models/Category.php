<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',         // Added for archival URL routing
        'description',
        'is_active',    // Added for category visibility management
    ];

    /**
     * ✅ Automatically generate/update slug whenever the name changes
     */
    protected static function boot()
    {
        parent::boot();

        // Triggered on Category::create()
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        // Triggered on $category->update()
        static::updating(function ($category) {
            // Only regenerate the slug if the name has actually changed
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * ✅ Casts for better data handling
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * ✅ Relationship: A category has many tickets
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}