<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['name' => 'Network', 'description' => 'Connectivity issues, Wi-Fi, switches, routers'],
            ['name' => 'Hardware', 'description' => 'PC, monitor, peripherals, cables'],
            ['name' => 'Software', 'description' => 'Installed applications, OS issues'],
            ['name' => 'Printer', 'description' => 'Printer jams, connection, drivers'],
        ];

        foreach ($defaults as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], ['description' => $cat['description']]);
        }

        $this->command->info('✅ Default categories seeded.');
    }
}
