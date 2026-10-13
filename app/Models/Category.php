<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
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
     * ✅ Automatically generate/update slug whenever the name changes (if column exists)
     */
    protected static function boot()
    {
        parent::boot();

        // Triggered on Category::create()
        static::creating(function ($category) {
            if (empty($category->slug) && Schema::hasColumn('categories', 'slug')) {
                $category->slug = Str::slug($category->name);
            }
        });

        // Triggered on $category->update()
        static::updating(function ($category) {
            // Only regenerate the slug if the name has actually changed
            if ($category->isDirty('name') && Schema::hasColumn('categories', 'slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * ✅ Safe Route Key Binding: Uses slug if available, otherwise falls back to ID
     */
    public function getRouteKey()
    {
        return !empty($this->slug) ? $this->slug : $this->id;
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