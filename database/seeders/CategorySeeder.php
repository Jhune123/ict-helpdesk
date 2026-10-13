<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $pillars = [
            'Email & Accounts',
            'Equipment Borrowing',
            'Equipment Repair',
            'Hardware',
            'Information System',
            'Information System/ Software',
            'Layouting',
            'Multimedia',
            'Multimedia Services',
            'Network',
            'Network / Internet',
            'Others',
            'Printing & Scanning',
            'Scheduled Maintenance',
            'Security',
            'Server & Storage',
            'Software Installation',
            'Support to Operation',
            'Website & Online Services',
        ];

        foreach ($pillars as $pillar) {
            // Adjust 'categories' or 'name' if your table/column is named differently (e.g. 'pillars', 'services')
            DB::table('categories')->updateOrInsert(
                ['name' => $pillar],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $this->command->info('All 19 ICTO Service Pillars restored successfully.');
    }
}