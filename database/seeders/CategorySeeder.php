<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            'Hardware',
            'Software',
            'Network',
            'Email & Accounts',
            'Website & Online Services',
            'Printing & Scanning',
            'Multimedia Equipment',
            'Server & Storage',
            'Security',
            'Others',

        ];

        foreach ($categories as $category) {
            Category::firstOrCreate([
                'name' => $category
            ]);
        }

        $this->command->info('✅ Categories seeded successfully.');
    }
}
