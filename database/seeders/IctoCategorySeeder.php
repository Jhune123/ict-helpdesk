<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class IctoCategorySeeder extends Seeder
{
    /**
     * Run the database seeds based on the provided dropdown image.
     */
    public function run(): void
    {
        // ✅ Match the exact list provided in your image
        $categories = [
            'Email & Accounts',
            'Equipment Repair',
            'Hardware',
            'Information System',
            'Multimedia Equipment',
            'Network',
            'Others',
            'Printing & Scanning',
            'Security',
            'Server & Storage',
            'Software',
            'Website & Online Services',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['name' => $name], // Use 'name' to check for existing entries
                [
                    // Generate a URL-friendly slug (e.g., 'email-accounts')
                    'slug' => Str::slug($name),
                    'description' => 'Archival category for all ' . $name . ' tickets.',
                    'is_active' => true,
                ]
            );
        }
    }
}